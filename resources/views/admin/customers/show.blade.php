@extends('layouts.admin', ['breadcrumb' => 'Detalle cliente'])
@section('content')
<h4>{{ $customer->full_name }}</h4>
<p><strong>Email:</strong> {{ $customer->email }}</p>
<p><strong>Teléfono:</strong> {{ $customer->phone }}</p>
<p><strong>Ciudad:</strong> {{ $customer->city }}</p>
<p><strong>Dirección:</strong> {{ $customer->address }}</p>
<h5 class="mt-5">Pedidos</h5>
<table class="shop-table account-orders-table"><thead><tr><th>Número</th><th>Estado</th><th>Total</th></tr></thead><tbody>@foreach($customer->orders as $order)<tr><td>{{ $order->number }}</td><td>{{ $order->status }}</td><td>${{ number_format($order->total, 0, ',', '.') }}</td></tr>@endforeach</tbody></table>
@endsection
