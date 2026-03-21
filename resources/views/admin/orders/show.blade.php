@extends('layouts.admin', ['breadcrumb' => 'Detalle pedido'])
@section('content')
<h4>{{ $order->number }}</h4>
<p><strong>Cliente:</strong> {{ $order->customer->full_name }} | <strong>Email:</strong> {{ $order->customer->email }}</p>
<p><strong>Estado:</strong> {{ $order->status }} | <strong>Total:</strong> ${{ number_format($order->total, 0, ',', '.') }}</p>
<p><strong>Dirección:</strong> {{ $order->shipping_address }}</p>
<table class="shop-table account-orders-table"><thead><tr><th>Producto</th><th>SKU</th><th>Cantidad</th><th>Total</th></tr></thead><tbody>@foreach($order->items as $item)<tr><td>{{ $item->name }}</td><td>{{ $item->sku }}</td><td>{{ $item->quantity }}</td><td>${{ number_format($item->total, 0, ',', '.') }}</td></tr>@endforeach</tbody></table>
<div class="mt-4"><a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-dark btn-rounded">Actualizar estado</a></div>
@endsection
