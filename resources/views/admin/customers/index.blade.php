@extends('layouts.admin', ['breadcrumb' => 'Clientes'])
@section('content')
<table class="shop-table account-orders-table"><thead><tr><th>Nombre</th><th>Email</th><th>Ciudad</th><th>Pedidos</th><th></th></tr></thead><tbody>@foreach($customers as $customer)<tr><td>{{ $customer->full_name }}</td><td>{{ $customer->email }}</td><td>{{ $customer->city }}</td><td>{{ $customer->orders_count }}</td><td><a href="{{ route('admin.customers.show', $customer) }}">Ver</a></td></tr>@endforeach</tbody></table>
<div class="mt-4">{{ $customers->links() }}</div>
@endsection
