<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const KEY = 'store_cart';

    public function items(): Collection
    {
        return collect(Session::get(self::KEY, []));
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $items = $this->items()->keyBy('product_id');

        if ($items->has($product->id)) {
            $current = $items->get($product->id);
            $current['quantity'] += $quantity;
            $current['line_total'] = $current['quantity'] * $current['unit_price'];
            $items->put($product->id, $current);
        } else {
            $items->put($product->id, [
                'product_id' => $product->id,
                'slug' => $product->slug,
                'name' => $product->name,
                'image' => $product->image,
                'sku' => $product->sku,
                'quantity' => $quantity,
                'unit_price' => (float) $product->price,
                'line_total' => $quantity * (float) $product->price,
            ]);
        }

        Session::put(self::KEY, $items->values()->all());
    }

    public function update(Product $product, int $quantity): void
    {
        $items = $this->items()->keyBy('product_id');

        if ($quantity <= 0) {
            $items->forget($product->id);
        } elseif ($items->has($product->id)) {
            $current = $items->get($product->id);
            $current['quantity'] = $quantity;
            $current['line_total'] = $quantity * $current['unit_price'];
            $items->put($product->id, $current);
        }

        Session::put(self::KEY, $items->values()->all());
    }

    public function remove(Product $product): void
    {
        $items = $this->items()->reject(fn ($item) => (int) $item['product_id'] === $product->id)->values();
        Session::put(self::KEY, $items->all());
    }

    public function removePurchasedProducts(iterable $productIds): void
    {
        $ids = collect($productIds)
            ->map(fn ($productId) => (int) $productId)
            ->filter(fn (int $productId) => $productId > 0)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return;
        }

        $items = $this->items()
            ->reject(fn ($item) => $ids->contains((int) ($item['product_id'] ?? 0)))
            ->values();

        Session::put(self::KEY, $items->all());
    }

    public function count(): int
    {
        return (int) $this->items()->sum('quantity');
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('line_total');
    }

    public function tax(): float
    {
        return 0.0;
    }

    public function shipping(): float
    {
        return $this->subtotal() >= 200000 ? 0.0 : 15000.0;
    }

    public function total(): float
    {
        return $this->subtotal() + $this->tax() + $this->shipping();
    }

    public function clear(): void
    {
        Session::forget(self::KEY);
    }
}
