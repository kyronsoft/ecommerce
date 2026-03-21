<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function index(): View
    {
        return view('store.cart', [
            'items' => $this->cart->items(),
            'cartCount' => $this->cart->count(),
            'subtotal' => $this->cart->subtotal(),
            'tax' => $this->cart->tax(),
            'shipping' => $this->cart->shipping(),
            'total' => $this->cart->total(),
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        $this->cart->add($product, max(1, (int) $request->integer('quantity', 1)));
        return redirect()->back()->with('status', 'Producto agregado al carrito.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->cart->update($product, (int) $request->integer('quantity', 1));
        return redirect()->route('store.cart.index')->with('status', 'Carrito actualizado.');
    }

    public function remove(Product $product): RedirectResponse
    {
        $this->cart->remove($product);
        return redirect()->route('store.cart.index')->with('status', 'Producto eliminado del carrito.');
    }
}
