<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
            ->with('category')
            ->when($request->filled('category'), fn ($query) => $query->whereHas('category', fn ($q) => $q->where('slug', $request->string('category'))))
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
}
