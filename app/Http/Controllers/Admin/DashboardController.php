<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Support\AdminPanelScope;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        [, $adminStore, $isSuperAdmin] = AdminPanelScope::fromRequest($request);

        $categoriesQuery = AdminPanelScope::scopeCategories(Category::query(), $adminStore, $isSuperAdmin);
        $storesQuery = AdminPanelScope::scopeStores(Store::query(), $adminStore, $isSuperAdmin);
        $productsQuery = AdminPanelScope::scopeProducts(Product::query(), $adminStore, $isSuperAdmin);
        $ordersQuery = AdminPanelScope::scopeOrders(Order::query(), $adminStore, $isSuperAdmin);
        $customersQuery = AdminPanelScope::scopeCustomers(Customer::query(), $adminStore, $isSuperAdmin);

        $sales = (float) (clone $ordersQuery)->sum('total');

        return view('admin.dashboard', [
            'stats' => [
                'categories' => (clone $categoriesQuery)->count(),
                'stores' => (clone $storesQuery)->count(),
                'products' => (clone $productsQuery)->count(),
                'active_products' => (clone $productsQuery)->where('is_active', true)->count(),
                'customers' => (clone $customersQuery)->count(),
                'orders' => (clone $ordersQuery)->count(),
                'pending_orders' => (clone $ordersQuery)->where('status', 'pending')->count(),
                'low_stock' => (clone $productsQuery)->where('stock', '<=', 5)->count(),
                'sales' => $sales,
                'avg_ticket' => (float) (clone $ordersQuery)->avg('total'),
            ],
            'latestOrders' => AdminPanelScope::scopeOrders(Order::with('customer'), $adminStore, $isSuperAdmin)->latest()->take(8)->get(),
            'lowStockProducts' => AdminPanelScope::scopeProducts(Product::with('category'), $adminStore, $isSuperAdmin)->where('stock', '<=', 5)->orderBy('stock')->take(8)->get(),
            'recentCustomers' => AdminPanelScope::scopeCustomers(Customer::query(), $adminStore, $isSuperAdmin)->latest()->take(6)->get(),
        ]);
    }
}
