<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    public function media(Product $product, string $field = 'image', ?int $index = null): BinaryFileResponse
    {
        abort_unless(in_array($field, ['image', 'gallery'], true), 404);

        $path = $field === 'gallery'
            ? data_get($product->gallery ?? [], $index)
            : $product->image;

        $normalizedPath = Product::normalizeMediaPath($path);

        abort_if(blank($normalizedPath) || ! Storage::disk('public')->exists($normalizedPath), 404);

        return response()->file(Storage::disk('public')->path($normalizedPath));
    }
}
