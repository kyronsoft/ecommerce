<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $sales = (float) Order::sum('total');

        return view('admin.dashboard', [
            'stats' => [
                'categories' => Category::count(),
                'stores' => Store::count(),
                'products' => Product::count(),
                'active_products' => Product::where('is_active', true)->count(),
                'customers' => Customer::count(),
                'orders' => Order::count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'low_stock' => Product::where('stock', '<=', 5)->count(),
                'sales' => $sales,
                'avg_ticket' => (float) Order::avg('total'),
            ],
            'latestOrders' => Order::with('customer')->latest()->take(8)->get(),
            'lowStockProducts' => Product::with('category')->where('stock', '<=', 5)->orderBy('stock')->take(8)->get(),
            'recentCustomers' => Customer::latest()->take(6)->get(),
        ]);
    }
}
