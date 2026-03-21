<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    public function index(): View
    {
        return view('admin.stores.index', [
            'stores' => Store::query()
                ->withCount('products')
                ->orderByDesc('is_featured')
                ->orderBy('name')
                ->paginate(15),
            'stats' => [
                'total' => Store::count(),
                'active' => Store::where('is_active', true)->count(),
                'featured' => Store::where('is_featured', true)->count(),
                'with_products' => Store::has('products')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.stores.form', [
            'store' => new Store([
                'is_active' => true,
                'is_featured' => false,
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $store = Store::query()->create($this->storeImages($request, $data));

        return redirect()
            ->route('admin.stores.show', $store)
            ->with('status', 'Tienda creada correctamente.');
    }

    public function show(Store $store): View
    {
        return view('admin.stores.show', [
            'store' => $store->load(['products' => fn ($query) => $query->latest()->take(8)]),
        ]);
    }

    public function edit(Store $store): View
    {
        return view('admin.stores.form', compact('store'));
    }

    public function update(Request $request, Store $store): RedirectResponse
    {
        $data = $this->validated($request, $store);
        $store->update($this->storeImages($request, $data, $store));

        return redirect()
            ->route('admin.stores.show', $store)
            ->with('status', 'Tienda actualizada correctamente.');
    }

    public function destroy(Store $store): RedirectResponse
    {
        if ($store->products()->exists()) {
            return redirect()
                ->route('admin.stores.show', $store)
                ->with('status', 'No puedes eliminar una tienda que todavía tiene productos asociados. Reasigna o elimina esos productos primero.');
        }

        $this->deleteStoredImage($store->logo);
        $this->deleteStoredImage($store->banner);

        $store->delete();

        return redirect()
            ->route('admin.stores.index')
            ->with('status', 'Tienda eliminada.');
    }

    protected function validated(Request $request, ?Store $store = null): array
    {
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:150'],
                'slug' => [
                    'nullable',
                    'string',
                    'max:180',
                    Rule::unique('stores', 'slug')->ignore($store),
                ],
                'owner_name' => ['required', 'string', 'max:120'],
                'email' => ['nullable', 'email', 'max:150'],
                'phone' => ['nullable', 'string', 'max:60'],
                'location' => ['nullable', 'string', 'max:255'],
                'short_description' => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
                'banner' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
                'website' => ['nullable', 'url', 'max:255'],
                'instagram_url' => ['nullable', 'url', 'max:255'],
                'facebook_url' => ['nullable', 'url', 'max:255'],
                'whatsapp' => ['nullable', 'string', 'max:60'],
                'business_hours' => ['nullable', 'string', 'max:500'],
                'highlights' => ['nullable', 'string'],
                'is_active' => ['nullable', 'boolean'],
                'is_featured' => ['nullable', 'boolean'],
            ],
            $this->messages(),
            $this->attributes(),
        );

        $data['highlights'] = filled($data['highlights'] ?? null)
            ? collect(preg_split('/[\r\n,]+/', (string) $data['highlights']))
                ->map(fn ($item) => trim($item))
                ->filter()
                ->values()
                ->all()
            : [];

        $data['is_active'] = (bool) ($data['is_active'] ?? false);
        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);

        return $data;
    }

    protected function storeImages(Request $request, array $data, ?Store $store = null): array
    {
        $storeSlug = Str::slug((string) ($data['slug'] ?: $store?->slug ?: $data['name']));

        $data['logo'] = $this->persistImage(
            $request->file('logo'),
            'logo',
            $storeSlug,
            $store?->logo,
        );

        $data['banner'] = $this->persistImage(
            $request->file('banner'),
            'banner',
            $storeSlug,
            $store?->banner,
        );

        return $data;
    }

    protected function persistImage(?UploadedFile $file, string $field, string $storeSlug, ?string $currentPath = null): ?string
    {
        if (! $file || ! $file->isValid()) {
            return $currentPath;
        }

        $directory = 'stores/'.$storeSlug;
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'png');
        $filename = $storeSlug.'-'.$field.'.'.$extension;

        Storage::disk('public')->makeDirectory($directory);
        $this->deleteStoredImage($currentPath);

        $relativePath = Storage::disk('public')->putFileAs($directory, $file, $filename);

        return filled($relativePath) ? 'storage/'.$relativePath : $currentPath;
    }

    protected function deleteStoredImage(?string $path): void
    {
        if (blank($path) || ! Str::startsWith($path, 'storage/')) {
            return;
        }

        $storagePath = Str::after($path, 'storage/');

        if (Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }
    }

    protected function messages(): array
    {
        return [
            'required' => 'Debes completar :attribute.',
            'email' => 'Ingresa un correo valido en :attribute.',
            'url' => 'Ingresa una URL valida en :attribute.',
            'unique' => 'Ya existe otra tienda con ese :attribute.',
            'image' => 'El archivo cargado en :attribute debe ser una imagen valida.',
            'mimes' => 'La imagen de :attribute debe estar en formato: :values.',
            'max.string' => ':Attribute no debe superar :max caracteres.',
            'max.file' => 'El archivo de :attribute no debe pesar mas de :max KB.',
        ];
    }

    protected function attributes(): array
    {
        return [
            'name' => 'nombre de la tienda',
            'slug' => 'slug',
            'owner_name' => 'responsable',
            'email' => 'correo',
            'phone' => 'telefono',
            'location' => 'ubicacion',
            'short_description' => 'descripcion corta',
            'description' => 'descripcion completa',
            'logo' => 'logo',
            'banner' => 'banner',
            'website' => 'sitio web',
            'instagram_url' => 'Instagram',
            'facebook_url' => 'Facebook',
            'whatsapp' => 'WhatsApp',
            'business_hours' => 'horario de atencion',
            'highlights' => 'highlights',
        ];
    }
}
