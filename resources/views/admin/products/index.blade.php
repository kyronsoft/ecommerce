@extends('layouts.admin', ['breadcrumb' => 'Productos'])
@section('content')
<div class="mb-4"><a href="{{ route('admin.products.create') }}" class="btn btn-dark btn-rounded">Nuevo producto</a></div>
<table class="shop-table account-orders-table"><thead><tr><th>Producto</th><th>Categoría</th><th>Precio</th><th>Stock</th><th></th></tr></thead><tbody>@foreach($products as $product)<tr><td>{{ $product->name }}</td><td>{{ $product->category->name }}</td><td>${{ number_format($product->price, 0, ',', '.') }}</td><td>{{ $product->stock }}</td><td><a href="{{ route('admin.products.edit', $product) }}">Editar</a></td></tr>@endforeach</tbody></table>
<div class="mt-4">{{ $products->links() }}</div>
@endsection
