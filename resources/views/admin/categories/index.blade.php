@extends('layouts.admin', ['breadcrumb' => 'Categorías'])
@section('content')
<div class="mb-4"><a href="{{ route('admin.categories.create') }}" class="btn btn-dark btn-rounded">Nueva categoría</a></div>
<table class="shop-table account-orders-table"><thead><tr><th>Nombre</th><th>Slug</th><th>Productos</th><th></th></tr></thead><tbody>@foreach($categories as $category)<tr><td>{{ $category->name }}</td><td>{{ $category->slug }}</td><td>{{ $category->products_count }}</td><td><a href="{{ route('admin.categories.edit', $category) }}">Editar</a></td></tr>@endforeach</tbody></table>
<div class="mt-4">{{ $categories->links() }}</div>
@endsection
