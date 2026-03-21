<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Contracts\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        return view('admin.customers.index', ['customers' => Customer::withCount('orders')->latest()->paginate(15)]);
    }

    public function show(Customer $customer): View
    {
        return view('admin.customers.show', ['customer' => $customer->load('orders')]);
    }
}
