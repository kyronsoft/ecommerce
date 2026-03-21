<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::with(['category', 'store'])->latest()->paginate(15),
            'stats' => [
                'total' => Product::count(),
                'active' => Product::where('is_active', true)->count(),
                'featured' => Product::where('is_featured', true)->count(),
                'low_stock' => Product::where('stock', '<=', 5)->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product([
                'category_id' => request()->integer('category'),
                'store_id' => request()->integer('store'),
                'is_active' => true,
                'is_featured' => false,
            ]),
            'categories' => Category::orderBy('name')->get(),
            'stores' => Store::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(Product $product): View
    {
        return view('admin.products.show', [
            'product' => $product->load(['category', 'store']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        Product::create($this->storeImage($request, $data));

        return redirect()->route('admin.products.index')->with('status', 'Producto creado.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'stores' => Store::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request);

        $product->update($this->storeImage($request, $data, $product));

        return redirect()->route('admin.products.index')->with('status', 'Producto actualizado.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteStoredImage($product->image);
        $product->delete();
        return redirect()->route('admin.products.index')->with('status', 'Producto eliminado.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'store_id' => ['nullable', 'exists:stores,id'],
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'nullable',
                'string',
                'max:180',
                Rule::unique('products', 'slug')->ignore($request->route('product')),
            ],
            'sku' => [
                'required',
                'string',
                'max:80',
                Rule::unique('products', 'sku')->ignore($request->route('product')),
            ],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0', 'gte:price'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'gallery' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $data['gallery'] = filled($data['gallery'] ?? null)
            ? collect(explode(',', $data['gallery']))->map(fn ($item) => trim($item))->filter()->values()->all()
            : [];
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);

        return $data;
    }

    private function storeImage(Request $request, array $data, ?Product $product = null): array
    {
        if (! $request->hasFile('image')) {
            $data['image'] = $product?->image;

            return $data;
        }

        $store = filled($data['store_id'] ?? null)
            ? Store::query()->find($data['store_id'])
            : null;

        $storeSlug = $store?->slug ?: 'sin-tienda';
        $productSlug = Str::slug((string) ($data['slug'] ?: $data['name']));

        $this->deleteStoredImage($product?->image);

        $extension = $request->file('image')->getClientOriginalExtension() ?: $request->file('image')->extension();
        $filename = $productSlug.'-principal.'.strtolower($extension);
        $relativePath = $request->file('image')->storeAs('stores/'.$storeSlug.'/products', $filename, 'public');

        $data['image'] = 'storage/'.$relativePath;

        return $data;
    }

    private function deleteStoredImage(?string $path): void
    {
        if (blank($path) || ! Str::startsWith($path, 'storage/')) {
            return;
        }

        $storagePath = Str::after($path, 'storage/');

        if (Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }
    }
}
