<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function show(Product $product): View
    {
        return view('store.product', [
            'product' => $product->load('category'),
            'relatedProducts' => Product::query()
                ->where('category_id', $product->category_id)
                ->whereKeyNot($product->id)
                ->take(4)
                ->get(),
            'cartCount' => $this->cart->count(),
        ]);
    }
}
