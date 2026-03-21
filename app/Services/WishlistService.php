<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class WishlistService
{
    private const KEY = 'store_wishlist';

    public function ids(): Collection
    {
        return collect(Session::get(self::KEY, []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();
    }

    public function productIds(): array
    {
        return $this->ids()->all();
    }

    public function items(): Collection
    {
        $ids = $this->ids();

        if ($ids->isEmpty()) {
            return collect();
        }

        $products = Product::query()
            ->with('category')
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        return $ids
            ->map(fn (int $id) => $products->get($id))
            ->filter()
            ->values();
    }

    public function add(Product $product): void
    {
        Session::put(self::KEY, $this->ids()->push($product->id)->unique()->values()->all());
    }

    public function remove(Product $product): void
    {
        Session::put(
            self::KEY,
            $this->ids()->reject(fn (int $id) => $id === $product->id)->values()->all()
        );
    }

    public function contains(Product $product): bool
    {
        return $this->ids()->contains($product->id);
    }

    public function count(): int
    {
        return $this->ids()->count();
    }
}
