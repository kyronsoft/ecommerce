@extends('layouts.admin', ['breadcrumb' => 'Pedidos'])
@section('content')
<table class="shop-table account-orders-table"><thead><tr><th>Número</th><th>Cliente</th><th>Estado</th><th>Total</th><th></th></tr></thead><tbody>@foreach($orders as $order)<tr><td>{{ $order->number }}</td><td>{{ $order->customer->full_name }}</td><td>{{ $order->status }}</td><td>${{ number_format($order->total, 0, ',', '.') }}</td><td><a href="{{ route('admin.orders.show', $order) }}">Ver</a></td></tr>@endforeach</tbody></table>
<div class="mt-4">{{ $orders->links() }}</div>
@endsection
