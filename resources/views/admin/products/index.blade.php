@extends('layouts.admin', [
    'breadcrumb' => 'Productos',
    'pageTitle' => 'Gestion de productos',
    'pageDescription' => 'Administra precios, stock, descripciones, imagenes y estado comercial de cada producto del catalogo.',
])

@section('page_actions')
    <a href="{{ route('admin.categories.create') }}" class="admin-btn">Nueva categoria</a>
    <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn--primary">Nuevo producto</a>
@endsection

@section('content')
    <div class="admin-grid-4" style="margin-bottom: 1.8rem;">
        <article class="admin-stat-card">
            <span class="admin-stat-label">Total</span>
            <strong class="admin-stat-value">{{ $stats['total'] }}</strong>
            <span class="admin-stat-help">Productos creados</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Activos</span>
            <strong class="admin-stat-value">{{ $stats['active'] }}</strong>
            <span class="admin-stat-help">Disponibles para venta</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Destacados</span>
            <strong class="admin-stat-value">{{ $stats['featured'] }}</strong>
            <span class="admin-stat-help">Empujan la visibilidad de la tienda</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Stock bajo</span>
            <strong class="admin-stat-value">{{ $stats['low_stock'] }}</strong>
            <span class="admin-stat-help">Revisar para no perder ventas</span>
        </article>
    </div>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar-copy">
                <h2>Catalogo cargado</h2>
                <p>Revisa categoria, precio, inventario y estado comercial.</p>
            </div>
        </div>

        @if($products->isEmpty())
            <div class="admin-empty">
                Aun no hay productos en el catalogo. Carga el primero para comenzar a vender.
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Tienda</th>
                        <th>Categoria</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <strong>{{ $product->name }}</strong>
                                <div class="admin-muted">SKU: {{ $product->sku }}</div>
                            </td>
                            <td>{{ $product->store?->name ?? 'Sin tienda' }}</td>
                            <td>{{ $product->category?->name ?? 'Sin categoria' }}</td>
                            <td>${{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <div class="admin-actions">
                                    <span class="admin-status admin-status--{{ $product->is_active ? 'active' : 'inactive' }}">
                                        {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                    @if($product->is_featured)
                                        <span class="admin-status admin-status--featured">Destacado</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.products.show', $product) }}" class="admin-link">Ver</a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="admin-link">Editar</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('¿Eliminar este producto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-link" style="border: 0; background: transparent; padding: 0;">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </section>
@endsection
