<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use App\Services\WishlistService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class WishlistController extends Controller
{
    public function __construct(
        private readonly WishlistService $wishlist,
        private readonly CartService $cart,
    ) {
    }

    public function index(): View
    {
        return view('store.wishlist', [
            'wishlistItems' => $this->wishlist->items(),
            'suggestedProducts' => Product::query()
                ->with('category')
                ->where('is_active', true)
                ->whereNotIn('id', $this->wishlist->productIds())
                ->latest()
                ->take(4)
                ->get(),
            'cartCount' => $this->cart->count(),
        ]);
    }

    public function add(Product $product): RedirectResponse
    {
        $this->wishlist->add($product);

        return redirect()->back()->with('status', 'Producto agregado a favoritos.');
    }

    public function remove(Product $product): RedirectResponse
    {
        $this->wishlist->remove($product);

        return redirect()->back()->with('status', 'Producto eliminado de favoritos.');
    }
}
