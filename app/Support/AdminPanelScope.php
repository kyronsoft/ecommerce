<?php

namespace App\Support;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AdminPanelScope
{
    public static function isSuperAdmin(?User $user): bool
    {
        if ($user === null || ! $user->is_admin) {
            return false;
        }

        if (strcasecmp((string) $user->email, (string) env('ADMIN_DEFAULT_EMAIL', 'jaruizr74@gmail.com')) === 0) {
            return true;
        }

        return ! Store::query()->where('email', $user->email)->exists();
    }

    public static function resolveStoreForUser(?User $user): ?Store
    {
        if (! $user || self::isSuperAdmin($user)) {
            return null;
        }

        return Store::query()
            ->where('email', $user->email)
            ->first();
    }

    public static function scopeStores(Builder $query, ?Store $store, bool $isSuperAdmin): Builder
    {
        return $isSuperAdmin || ! $store ? $query : $query->whereKey($store->id);
    }

    public static function scopeProducts(Builder $query, ?Store $store, bool $isSuperAdmin): Builder
    {
        return $isSuperAdmin || ! $store ? $query : $query->where('store_id', $store->id);
    }

    public static function scopeCategories(Builder $query, ?Store $store, bool $isSuperAdmin): Builder
    {
        return $isSuperAdmin || ! $store
            ? $query
            : $query->whereHas('products', fn (Builder $products) => $products->where('store_id', $store->id));
    }

    public static function scopeOrders(Builder $query, ?Store $store, bool $isSuperAdmin): Builder
    {
        return $isSuperAdmin || ! $store
            ? $query
            : $query->whereHas('items.product', fn (Builder $products) => $products->where('store_id', $store->id));
    }

    public static function scopeCustomers(Builder $query, ?Store $store, bool $isSuperAdmin): Builder
    {
        return $isSuperAdmin || ! $store
            ? $query
            : $query->whereHas('orders.items.product', fn (Builder $products) => $products->where('store_id', $store->id));
    }

    public static function ensureStoreAccess(Store $targetStore, ?Store $currentStore, bool $isSuperAdmin): void
    {
        abort_unless($isSuperAdmin || ($currentStore && $targetStore->is($currentStore)), 403);
    }

    public static function ensureProductAccess(Product $product, ?Store $currentStore, bool $isSuperAdmin): void
    {
        abort_unless($isSuperAdmin || ($currentStore && (int) $product->store_id === (int) $currentStore->id), 403);
    }

    public static function ensureOrderAccess(Order $order, ?Store $currentStore, bool $isSuperAdmin): void
    {
        if ($isSuperAdmin) {
            return;
        }

        abort_unless(
            $currentStore
            && $order->items()->whereHas('product', fn (Builder $products) => $products->where('store_id', $currentStore->id))->exists(),
            403
        );
    }

    public static function ensureCustomerAccess(Customer $customer, ?Store $currentStore, bool $isSuperAdmin): void
    {
        if ($isSuperAdmin) {
            return;
        }

        abort_unless(
            $currentStore
            && $customer->orders()->whereHas('items.product', fn (Builder $products) => $products->where('store_id', $currentStore->id))->exists(),
            403
        );
    }

    public static function ensureCategoryAccess(Category $category, ?Store $currentStore, bool $isSuperAdmin): void
    {
        if ($isSuperAdmin) {
            return;
        }

        abort_unless(
            $currentStore
            && $category->products()->where('store_id', $currentStore->id)->exists(),
            403
        );
    }

    public static function fromRequest(Request $request): array
    {
        $user = $request->attributes->get('adminUser');
        $store = $request->attributes->get('adminStore');
        $isSuperAdmin = (bool) $request->attributes->get('adminIsSuperAdmin');

        return [$user, $store, $isSuperAdmin];
    }
}
