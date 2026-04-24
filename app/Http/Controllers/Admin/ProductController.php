<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Support\AdminPanelScope;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        [, $adminStore, $isSuperAdmin] = AdminPanelScope::fromRequest($request);
        $filters = [
            'search' => trim((string) $request->string('search')),
            'store_id' => $isSuperAdmin
                ? ($request->filled('store_id') ? (int) $request->integer('store_id') : null)
                : $adminStore?->id,
            'category_id' => $request->filled('category_id') ? (int) $request->integer('category_id') : null,
            'status' => (string) $request->string('status'),
            'featured' => (string) $request->string('featured'),
            'price_min' => $request->filled('price_min') ? (int) preg_replace('/\D+/', '', (string) $request->input('price_min')) : null,
            'price_max' => $request->filled('price_max') ? (int) preg_replace('/\D+/', '', (string) $request->input('price_max')) : null,
            'stock_min' => $request->filled('stock_min') ? (int) $request->integer('stock_min') : null,
            'stock_max' => $request->filled('stock_max') ? (int) $request->integer('stock_max') : null,
        ];

        $productStats = $this->productStats();

        return view('admin.products.index', [
            'products' => AdminPanelScope::scopeProducts(Product::query(), $adminStore, $isSuperAdmin)
                ->with(['category', 'store'])
                ->when($filters['search'] !== '', function ($query) use ($filters) {
                    $query->where(function ($nestedQuery) use ($filters) {
                        $nestedQuery
                            ->where('name', 'like', '%'.$filters['search'].'%')
                            ->orWhere('sku', 'like', '%'.$filters['search'].'%');
                    });
                })
                ->when($filters['store_id'], fn ($query) => $query->where('store_id', $filters['store_id']))
                ->when($filters['category_id'], fn ($query) => $query->where('category_id', $filters['category_id']))
                ->when($filters['status'] === 'active', fn ($query) => $query->where('is_active', true))
                ->when($filters['status'] === 'inactive', fn ($query) => $query->where('is_active', false))
                ->when($filters['featured'] === 'yes', fn ($query) => $query->where('is_featured', true))
                ->when($filters['featured'] === 'no', fn ($query) => $query->where('is_featured', false))
                ->when($filters['price_min'] !== null, fn ($query) => $query->where('price', '>=', $filters['price_min']))
                ->when($filters['price_max'] !== null, fn ($query) => $query->where('price', '<=', $filters['price_max']))
                ->when($filters['stock_min'] !== null, fn ($query) => $query->where('stock', '>=', $filters['stock_min']))
                ->when($filters['stock_max'] !== null, fn ($query) => $query->where('stock', '<=', $filters['stock_max']))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'stats' => $productStats,
            'stores' => AdminPanelScope::scopeStores(Store::query(), $adminStore, $isSuperAdmin)->orderBy('name')->get(['id', 'name']),
            'categories' => AdminPanelScope::scopeCategories(Category::query(), $adminStore, $isSuperAdmin)->orderBy('name')->get(['id', 'name']),
            'filters' => $filters,
        ]);
    }

    private function productStats(): array
    {
        [, $adminStore, $isSuperAdmin] = AdminPanelScope::fromRequest(request());

        return [
            'total' => $this->makeProductStat(
                label: 'Total',
                help: 'Productos creados',
                icon: 'fas fa-box-open',
                totalQuery: AdminPanelScope::scopeProducts(Product::query(), $adminStore, $isSuperAdmin),
                periodQuery: AdminPanelScope::scopeProducts(Product::query(), $adminStore, $isSuperAdmin),
            ),
            'active' => $this->makeProductStat(
                label: 'Activos',
                help: 'Disponibles para venta',
                icon: 'fas fa-bolt',
                totalQuery: AdminPanelScope::scopeProducts(Product::query(), $adminStore, $isSuperAdmin)->where('is_active', true),
                periodQuery: AdminPanelScope::scopeProducts(Product::query(), $adminStore, $isSuperAdmin)->where('is_active', true),
            ),
            'featured' => $this->makeProductStat(
                label: 'Destacados',
                help: 'Empujan la visibilidad de la tienda',
                icon: 'fas fa-star',
                totalQuery: AdminPanelScope::scopeProducts(Product::query(), $adminStore, $isSuperAdmin)->where('is_featured', true),
                periodQuery: AdminPanelScope::scopeProducts(Product::query(), $adminStore, $isSuperAdmin)->where('is_featured', true),
            ),
            'low_stock' => $this->makeProductStat(
                label: 'Stock bajo',
                help: 'Revisar para no perder ventas',
                icon: 'fas fa-exclamation-triangle',
                totalQuery: AdminPanelScope::scopeProducts(Product::query(), $adminStore, $isSuperAdmin)->where('stock', '<=', 5),
                periodQuery: AdminPanelScope::scopeProducts(Product::query(), $adminStore, $isSuperAdmin)->where('stock', '<=', 5),
            ),
        ];
    }

    private function makeProductStat(string $label, string $help, string $icon, $totalQuery, $periodQuery): array
    {
        [$currentStart, $currentEnd, $previousStart, $previousEnd] = $this->monthToDateWindows();

        $value = (clone $totalQuery)->count();
        $currentPeriod = (clone $periodQuery)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();
        $previousPeriod = (clone $periodQuery)
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->count();

        return [
            'label' => $label,
            'value' => $value,
            'help' => $help,
            'icon' => $icon,
            'trend' => $this->buildTrend($currentPeriod, $previousPeriod),
        ];
    }

    private function monthToDateWindows(): array
    {
        $now = Carbon::now();
        $currentStart = $now->copy()->startOfMonth();
        $currentEnd = $now->copy();

        $previousReference = $now->copy()->subMonthNoOverflow();
        $previousStart = $previousReference->copy()->startOfMonth();
        $previousEnd = $previousStart->copy()
            ->day(min($now->day, $previousStart->daysInMonth))
            ->setTime($now->hour, $now->minute, $now->second);

        return [$currentStart, $currentEnd, $previousStart, $previousEnd];
    }

    private function buildTrend(int $currentPeriod, int $previousPeriod): array
    {
        if ($previousPeriod === 0 && $currentPeriod === 0) {
            return [
                'direction' => 'neutral',
                'text' => '0% frente al mes corrido anterior',
            ];
        }

        if ($previousPeriod === 0) {
            return [
                'direction' => 'up',
                'text' => '+100% frente al mes corrido anterior',
            ];
        }

        $change = (($currentPeriod - $previousPeriod) / $previousPeriod) * 100;
        $direction = $change > 0 ? 'up' : ($change < 0 ? 'down' : 'neutral');
        $prefix = $change > 0 ? '+' : '';

        return [
            'direction' => $direction,
            'text' => $prefix.number_format($change, 1, ',', '.').'% frente al mes corrido anterior',
        ];
    }

    public function create(): View
    {
        [, $adminStore, $isSuperAdmin] = AdminPanelScope::fromRequest(request());
        $stores = AdminPanelScope::scopeStores(Store::where('is_active', true), $adminStore, $isSuperAdmin)->orderBy('name')->get();

        return view('admin.products.form', [
            'product' => new Product([
                'category_id' => request()->integer('category'),
                'store_id' => $isSuperAdmin ? request()->integer('store') : $adminStore?->id,
                'is_active' => true,
                'is_featured' => false,
            ]),
            'categories' => AdminPanelScope::scopeCategories(Category::query(), $adminStore, $isSuperAdmin)->orderBy('name')->get(),
            'stores' => $stores,
            'storeSkuMeta' => $this->storeSkuMeta($stores),
        ]);
    }

    public function show(Product $product): View
    {
        [, $adminStore, $isSuperAdmin] = AdminPanelScope::fromRequest(request());
        AdminPanelScope::ensureProductAccess($product, $adminStore, $isSuperAdmin);

        return view('admin.products.show', [
            'product' => $product->load(['category', 'store']),
        ]);
    }

    public function media(Product $product, string $field, ?int $index = null): BinaryFileResponse
    {
        [, $adminStore, $isSuperAdmin] = AdminPanelScope::fromRequest(request());
        AdminPanelScope::ensureProductAccess($product, $adminStore, $isSuperAdmin);
        abort_unless(in_array($field, ['image', 'gallery'], true), 404);

        $path = $field === 'image'
            ? (string) $product->image
            : (string) data_get($product->gallery ?? [], $index);

        $path = $this->normalizeStoredPath($path);

        abort_if(blank($path) || ! Storage::disk('public')->exists($path), 404);

        return response()->file(Storage::disk('public')->path($path));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        Product::create($this->storeImage($request, $data));

        return redirect()->route('admin.products.index')->with('status', 'Producto creado.');
    }

    public function edit(Product $product): View
    {
        [, $adminStore, $isSuperAdmin] = AdminPanelScope::fromRequest(request());
        AdminPanelScope::ensureProductAccess($product, $adminStore, $isSuperAdmin);
        $stores = AdminPanelScope::scopeStores(Store::where('is_active', true), $adminStore, $isSuperAdmin)->orderBy('name')->get();

        return view('admin.products.form', [
            'product' => $product,
            'categories' => AdminPanelScope::scopeCategories(Category::query(), $adminStore, $isSuperAdmin)->orderBy('name')->get(),
            'stores' => $stores,
            'storeSkuMeta' => $this->storeSkuMeta($stores, $product),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        [, $adminStore, $isSuperAdmin] = AdminPanelScope::fromRequest($request);
        AdminPanelScope::ensureProductAccess($product, $adminStore, $isSuperAdmin);
        $data = $this->validated($request);

        $product->update($this->storeImage($request, $data, $product));

        return redirect()->route('admin.products.index')->with('status', 'Producto actualizado.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        [, $adminStore, $isSuperAdmin] = AdminPanelScope::fromRequest(request());
        AdminPanelScope::ensureProductAccess($product, $adminStore, $isSuperAdmin);
        $this->deleteStoredImage($product->image);
        $this->deleteStoredImages($product->gallery ?? []);
        $product->delete();
        return redirect()->route('admin.products.index')->with('status', 'Producto eliminado.');
    }

    private function validated(Request $request): array
    {
        [, $adminStore, $isSuperAdmin] = AdminPanelScope::fromRequest($request);
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
            'gallery_existing' => ['nullable', 'array'],
            'gallery_existing.*' => ['string'],
            'gallery_files' => ['nullable', 'array'],
            'gallery_files.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $data['gallery_existing'] = collect($data['gallery_existing'] ?? [])
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();
        if (! $isSuperAdmin && $adminStore) {
            $data['store_id'] = $adminStore->id;
        }
        $data['sku'] = $this->resolveSku($data, $request->route('product'));
        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);

        return $data;
    }

    private function resolveSku(array $data, ?Product $product = null): string
    {
        if ($product && filled($data['sku'] ?? null)) {
            return trim((string) $data['sku']);
        }

        $store = filled($data['store_id'] ?? null)
            ? Store::query()->find($data['store_id'])
            : null;

        if (! $store) {
            return trim((string) ($data['sku'] ?? ''));
        }

        return $this->nextSkuForStore($store, $product);
    }

    private function storeSkuMeta($stores, ?Product $product = null): array
    {
        return $stores
            ->mapWithKeys(fn (Store $store) => [
                $store->id => [
                    'prefix' => Str::slug($store->name),
                    'next' => $this->nextSkuForStore($store, $product),
                ],
            ])
            ->all();
    }

    private function nextSkuForStore(Store $store, ?Product $product = null): string
    {
        $prefix = Str::slug($store->name);
        $ignoreId = $product?->id;

        $nextSequence = Product::query()
            ->where('store_id', $store->id)
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->pluck('sku')
            ->map(function ($sku) use ($prefix) {
                $sku = trim((string) $sku);

                if (! Str::startsWith(Str::lower($sku), Str::lower($prefix).'-')) {
                    return 0;
                }

                if (! preg_match('/-(\d{3,})$/', $sku, $matches)) {
                    return 0;
                }

                return (int) $matches[1];
            })
            ->max() + 1;

        return sprintf('%s-%03d', $prefix, max($nextSequence, 1));
    }

    private function storeImage(Request $request, array $data, ?Product $product = null): array
    {
        $existingGallery = collect($data['gallery_existing'] ?? []);
        $currentGallery = collect($product?->gallery ?? []);

        if ($request->hasFile('image')) {
            $this->deleteStoredImage($product?->image);

            $mainImage = $request->file('image');
            $extension = $mainImage->getClientOriginalExtension() ?: $mainImage->extension();
            $filename = $this->productMediaFilename($data, 'principal', strtolower((string) $extension));
            $relativePath = $mainImage->storeAs($this->productMediaDirectory($data), $filename, 'public');

            $data['image'] = 'storage/'.$relativePath;
        } else {
            $data['image'] = $product?->image;
        }

        $removedGallery = $currentGallery->diff($existingGallery);
        $this->deleteStoredImages($removedGallery->all());

        $uploadedGallery = collect($request->file('gallery_files', []))
            ->values()
            ->map(function ($file, int $index) use ($data) {
                $extension = $file->getClientOriginalExtension() ?: $file->extension();
                $filename = $this->productMediaFilename($data, 'galeria-'.($index + 1).'-'.Str::random(6), strtolower((string) $extension));
                $relativePath = $file->storeAs($this->productMediaDirectory($data), $filename, 'public');

                return 'storage/'.$relativePath;
            });

        $data['gallery'] = $existingGallery
            ->merge($uploadedGallery)
            ->unique()
            ->values()
            ->all();

        unset($data['gallery_existing'], $data['gallery_files']);

        return $data;
    }

    private function productMediaDirectory(array $data): string
    {
        $store = filled($data['store_id'] ?? null)
            ? Store::query()->find($data['store_id'])
            : null;

        return 'stores/'.($store?->slug ?: 'sin-tienda').'/products';
    }

    private function productMediaFilename(array $data, string $suffix, string $extension): string
    {
        $productSlug = Str::slug((string) ($data['slug'] ?: $data['name']));

        return $productSlug.'-'.$suffix.'.'.$extension;
    }

    private function deleteStoredImage(?string $path): void
    {
        $storagePath = $this->normalizeStoredPath($path);

        if (blank($storagePath)) {
            return;
        }

        if (Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }
    }

    private function deleteStoredImages(array $paths): void
    {
        foreach ($paths as $path) {
            $this->deleteStoredImage($path);
        }
    }

    private function normalizeStoredPath(?string $path): ?string
    {
        if (blank($path) || Str::startsWith((string) $path, ['http://', 'https://'])) {
            return null;
        }

        $normalizedPath = ltrim((string) $path, '/');

        if (Str::startsWith($normalizedPath, 'public/')) {
            $normalizedPath = Str::after($normalizedPath, 'public/');
        }

        if (Str::startsWith($normalizedPath, 'storage/')) {
            $normalizedPath = Str::after($normalizedPath, 'storage/');
        }

        return $normalizedPath;
    }
}
