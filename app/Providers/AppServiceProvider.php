<?php

namespace App\Providers;

use App\Models\Category;
use App\Services\CartService;
use App\Services\WishlistService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.store', function ($view): void {
            $cart = app(CartService::class);
            $wishlist = app(WishlistService::class);

            $view->with([
                'headerCategories' => Category::query()->orderBy('name')->get(),
                'cartCount' => $cart->count(),
                'wishlistCount' => $wishlist->count(),
            ]);
        });

        View::composer('store.*', function ($view): void {
            $view->with('wishlistProductIds', app(WishlistService::class)->productIds());
        });
    }
}
