<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::count(),
                'customers' => Customer::count(),
                'orders' => Order::count(),
                'sales' => (float) Order::sum('total'),
            ],
            'latestOrders' => Order::with('customer')->latest()->take(8)->get(),
            'lowStockProducts' => Product::where('stock', '<=', 5)->orderBy('stock')->take(8)->get(),
        ]);
    }
}
