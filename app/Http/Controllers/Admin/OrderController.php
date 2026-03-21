<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('admin.orders.index', ['orders' => Order::with('customer')->latest()->paginate(15)]);
    }

    public function show(Order $order): View
    {
        return view('admin.orders.show', ['order' => $order->load('customer', 'items.product')]);
    }

    public function edit(Order $order): View
    {
        return view('admin.orders.edit', ['order' => $order]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $order->update($request->validate([
            'status' => ['required', 'in:pending,paid,processing,shipped,completed,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]));

        return redirect()->route('admin.orders.show', $order)->with('status', 'Pedido actualizado.');
    }
}
