@extends('layouts.admin', ['breadcrumb' => 'Dashboard'])
@section('content')
<div class="row cols-md-4 cols-sm-2 cols-1 mb-8">
    <div class="icon-box text-center"><span class="icon-box-icon icon-sales"><i class="w-icon-box"></i></span><div class="icon-box-content"><h4>Productos</h4><p>{{ $stats['products'] }}</p></div></div>
    <div class="icon-box text-center"><span class="icon-box-icon icon-orders"><i class="w-icon-orders"></i></span><div class="icon-box-content"><h4>Clientes</h4><p>{{ $stats['customers'] }}</p></div></div>
    <div class="icon-box text-center"><span class="icon-box-icon icon-dollar"><i class="w-icon-money"></i></span><div class="icon-box-content"><h4>Pedidos</h4><p>{{ $stats['orders'] }}</p></div></div>
    <div class="icon-box text-center"><span class="icon-box-icon icon-visit"><i class="w-icon-cart"></i></span><div class="icon-box-content"><h4>Ventas</h4><p>${{ number_format($stats['sales'], 0, ',', '.') }}</p></div></div>
</div>
<div class="row">
    <div class="col-lg-8 mb-6"><h4 class="title title-sm">Últimos pedidos</h4><table class="shop-table account-orders-table mb-6"><thead><tr><th>Pedido</th><th>Cliente</th><th>Estado</th><th>Total</th><th></th></tr></thead><tbody>@foreach($latestOrders as $order)<tr><td>{{ $order->number }}</td><td>{{ $order->customer->full_name }}</td><td>{{ $order->status }}</td><td>${{ number_format($order->total, 0, ',', '.') }}</td><td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-dark btn-rounded btn-sm">Ver</a></td></tr>@endforeach</tbody></table></div>
    <div class="col-lg-4 mb-6"><h4 class="title title-sm">Stock bajo</h4><ul class="product-lists">@foreach($lowStockProducts as $product)<li><a href="{{ route('admin.products.edit', $product) }}">{{ $product->name }}</a> <span class="text-danger">({{ $product->stock }})</span></li>@endforeach</ul></div>
</div>
@endsection
