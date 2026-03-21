<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store as MarketplaceStore;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function index(): View
    {
        return view('store.home', [
            'categories' => Category::query()->withCount('products')->take(6)->get(),
            'featuredProducts' => Product::query()->where('is_featured', true)->take(8)->get(),
            'latestProducts' => Product::query()->latest()->take(8)->get(),
            'cartCount' => $this->cart->count(),
        ]);
    }

    public function shop(Request $request): View
    {
        $products = Product::query()
            ->with(['category', 'store'])
            ->when($request->filled('category'), fn ($query) => $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category'))))
            ->when($request->filled('store'), fn ($query) => $query->whereHas('store', fn ($q) => $q->where('slug', $request->string('store'))))
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search').'%'))
            ->where('is_active', true)
            ->paginate(12)
            ->withQueryString();

        return view('store.shop', [
            'products' => $products,
            'categories' => Category::query()->orderBy('name')->get(),
            'cartCount' => $this->cart->count(),
        ]);
    }

    public function stores(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $sort = (string) $request->string('sort', 'featured');
        $location = trim((string) $request->string('location'));
        $featuredOnly = $request->boolean('featured');
        $withCatalogOnly = $request->boolean('with_catalog');

        $storesQuery = MarketplaceStore::query()
            ->withCount([
                'products as products_count' => fn ($query) => $query->where('is_active', true),
                'products as featured_products_count' => fn ($query) => $query->where('is_featured', true)->where('is_active', true),
            ])
            ->where('is_active', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($storeQuery) use ($search) {
                    $storeQuery
                        ->where('name', 'like', '%'.$search.'%')
                        ->orWhere('owner_name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('location', 'like', '%'.$search.'%');
                });
            })
            ->when($location !== '', fn ($query) => $query->where('location', $location))
            ->when($featuredOnly, fn ($query) => $query->where('is_featured', true))
            ->when($withCatalogOnly, fn ($query) => $query->has('products'));

        $storesQuery = match ($sort) {
            'newest' => $storesQuery->latest(),
            'oldest' => $storesQuery->oldest(),
            'name_asc' => $storesQuery->orderBy('name'),
            'name_desc' => $storesQuery->orderByDesc('name'),
            'products' => $storesQuery->orderByDesc('products_count')->orderByDesc('is_featured')->orderBy('name'),
            default => $storesQuery->orderByDesc('is_featured')->orderByDesc('products_count')->orderBy('name'),
        };

        $stores = $storesQuery
            ->paginate(10)
            ->withQueryString()
            ->through(fn (MarketplaceStore $store) => $this->toStoreCardData($store));

        $locations = MarketplaceStore::query()
            ->where('is_active', true)
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->orderBy('location')
            ->pluck('location');

        return view('store.stores', [
            'stores' => $stores,
            'search' => $search,
            'sort' => $sort,
            'selectedLocation' => $location,
            'featuredOnly' => $featuredOnly,
            'withCatalogOnly' => $withCatalogOnly,
            'locations' => $locations,
            'storeStats' => [
                'stores' => MarketplaceStore::query()->where('is_active', true)->count(),
                'products' => Product::query()->where('is_active', true)->count(),
                'featured_products' => Product::query()->where('is_active', true)->where('is_featured', true)->count(),
                'categories' => Category::query()->count(),
            ],
        ]);
    }

    public function storeShow(MarketplaceStore $store): View
    {
        $products = Product::query()
            ->with('category')
            ->whereBelongsTo($store)
            ->where('is_active', true)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('store.store-show', [
            'store' => $this->toStoreCardData(
                $store->loadCount([
                    'products',
                    'products as featured_products_count' => fn ($query) => $query->where('is_featured', true)->where('is_active', true),
                ])
            ),
            'products' => $products,
            'categories' => Category::query()
                ->withCount(['products' => fn ($query) => $query->whereBelongsTo($store)->where('is_active', true)])
                ->having('products_count', '>', 0)
                ->orderByDesc('products_count')
                ->take(8)
                ->get(),
            'featuredProducts' => Product::query()->with('category')->whereBelongsTo($store)->where('is_active', true)->where('is_featured', true)->take(3)->get(),
            'latestProducts' => Product::query()->with('category')->whereBelongsTo($store)->where('is_active', true)->latest()->take(3)->get(),
            'storeMetrics' => [
                'products' => Product::query()->whereBelongsTo($store)->where('is_active', true)->count(),
                'featured_products' => Product::query()->whereBelongsTo($store)->where('is_active', true)->where('is_featured', true)->count(),
                'categories' => Category::query()
                    ->whereHas('products', fn ($query) => $query->whereBelongsTo($store)->where('is_active', true))
                    ->count(),
            ],
        ]);
    }

    private function toStoreCardData(MarketplaceStore $store): array
    {
        $productsCount = $store->products_count ?? $store->products()->where('is_active', true)->count();
        $featuredProductsCount = $store->featured_products_count ?? $store->products()->where('is_active', true)->where('is_featured', true)->count();
        $categoriesCount = Category::query()
            ->whereHas('products', fn ($query) => $query->whereBelongsTo($store)->where('is_active', true))
            ->count();

        $highlights = collect($store->highlights ?? [])
            ->filter()
            ->values()
            ->all();

        if ($highlights === []) {
            $highlights = Category::query()
                ->whereHas('products', fn ($query) => $query->whereBelongsTo($store)->where('is_active', true))
                ->withCount('products')
                ->orderByDesc('products_count')
                ->take(3)
                ->pluck('name')
                ->filter()
                ->values()
                ->all();
        }

        return [
            'slug' => $store->slug,
            'name' => $store->name,
            'owner_name' => $store->owner_name,
            'email' => $store->email,
            'phone' => $store->phone,
            'location' => $store->location ?: 'Ubicación pendiente',
            'short_description' => $store->short_description,
            'description' => $store->description,
            'banner' => $this->resolveStoreMedia($store->banner, 'wolmart/assets/images/vendor/dokan/1.jpg'),
            'logo' => $this->resolveStoreMedia($store->logo, 'wolmart/assets/images/la-tienda-de-mi-abue-logo.png'),
            'label' => $store->is_featured ? 'Destacada' : 'Registrada',
            'products_count' => $productsCount,
            'featured_products_count' => $featuredProductsCount,
            'categories_count' => $categoriesCount,
            'highlights' => $highlights,
            'joined_at' => optional($store->created_at)?->format('d/m/Y') ?: 'Activa',
            'catalog_url' => route('store.store.show', $store->slug),
            'shop_url' => route('store.shop', ['store' => $store->slug]),
            'website' => $store->website,
            'instagram_url' => $store->instagram_url,
            'facebook_url' => $store->facebook_url,
            'whatsapp' => $store->whatsapp,
            'business_hours' => collect(preg_split('/[\r\n]+/', (string) ($store->business_hours ?? '')))
                ->map(fn ($line) => trim($line))
                ->filter()
                ->values()
                ->all(),
        ];
    }

    private function resolveStoreMedia(?string $path, string $fallback): string
    {
        if (blank($path)) {
            return asset($fallback);
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');

        if (Str::startsWith($normalizedPath, 'public/')) {
            $normalizedPath = Str::after($normalizedPath, 'public/');
        }

        if (Str::startsWith($normalizedPath, 'storage/')) {
            return asset($normalizedPath);
        }

        if (Storage::disk('public')->exists($normalizedPath)) {
            return Storage::url($normalizedPath);
        }

        return asset($normalizedPath);
    }
}
