@extends('layouts.admin', [
    'breadcrumb' => 'Detalle categoria',
    'pageTitle' => $category->name,
    'pageDescription' => 'Consulta la categoria, su descripcion y los productos mas recientes asociados.',
])

@php
    $imageUrl = filled($category->image)
        ? (\Illuminate\Support\Str::startsWith($category->image, ['http://', 'https://']) ? $category->image : asset($category->image))
        : null;
@endphp

@section('page_actions')
    <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="admin-btn">Crear producto</a>
    <a href="{{ route('admin.categories.edit', $category) }}" class="admin-btn admin-btn--primary">Editar categoria</a>
@endsection

@section('content')
    <div class="admin-grid-2">
        <section class="admin-panel">
            <div class="admin-hero-media">
                @if($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ $category->name }}">
                @endif
                <div>
                    <h2>{{ $category->name }}</h2>
                    <div class="admin-meta-row">
                        <span class="admin-status admin-status--active">{{ $category->products->count() }} productos recientes</span>
                        <span class="admin-status admin-status--processing">Slug: {{ $category->slug }}</span>
                    </div>
                    <p style="margin-top: 1.4rem;">{{ $category->description ?: 'Esta categoria aun no tiene descripcion.' }}</p>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-section-head">
                <div>
                    <h3>Acciones</h3>
                    <p>Completa la carga operativa de esta categoria.</p>
                </div>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="admin-btn admin-btn--primary">Agregar producto</a>
                <a href="{{ route('admin.categories.index') }}" class="admin-btn">Volver al listado</a>
            </div>
        </section>
    </div>

    <section class="admin-panel" style="margin-top: 1.8rem;">
        <div class="admin-section-head">
            <div>
                <h2>Productos recientes</h2>
                <p>Ultimos productos registrados dentro de esta categoria.</p>
            </div>
        </div>

        @if($category->products->isEmpty())
            <div class="admin-empty">
                Esta categoria no tiene productos todavia.
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($category->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>${{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td><a href="{{ route('admin.products.show', $product) }}" class="admin-link">Ver producto</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
