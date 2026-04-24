@extends('layouts.admin', [
    'breadcrumb' => 'Productos',
    'pageTitle' => 'Gestion de productos',
    'pageDescription' => 'Administra precios, stock, descripciones, imagenes y estado comercial de cada producto del catalogo.',
])

@php
    $hasActiveFilters = collect($filters)->filter(fn ($value) => $value !== null && $value !== '')->isNotEmpty();
@endphp

@section('page_actions')
    @if($currentAdminIsSuperAdmin ?? false)
        <a href="{{ route('admin.categories.create') }}" class="admin-btn">Nueva categoria</a>
    @endif
    <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn--primary">Nuevo producto</a>
@endsection

@section('content')
    <div class="admin-grid-4" style="margin-bottom: 1.8rem;">
        @foreach($stats as $stat)
            <article class="admin-stat-card admin-stat-card--enhanced">
                <div class="admin-stat-top">
                    <div>
                        <span class="admin-stat-label">{{ $stat['label'] }}</span>
                        <strong class="admin-stat-value">{{ $stat['value'] }}</strong>
                    </div>
                    <span class="admin-stat-icon" aria-hidden="true">
                        <i class="{{ $stat['icon'] }}"></i>
                    </span>
                </div>
                <span class="admin-stat-help">{{ $stat['help'] }}</span>
                <div class="admin-stat-trend admin-stat-trend--{{ $stat['trend']['direction'] }}">
                    <i class="fas {{ $stat['trend']['direction'] === 'up' ? 'fa-arrow-up' : ($stat['trend']['direction'] === 'down' ? 'fa-arrow-down' : 'fa-minus') }}" aria-hidden="true"></i>
                    <span>{{ $stat['trend']['text'] }}</span>
                </div>
            </article>
        @endforeach
    </div>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar-copy">
                <h2>Catalogo cargado</h2>
                <p>Revisa categoria, precio, inventario y estado comercial.</p>
            </div>
        </div>

        <details class="admin-filter-accordion" style="margin-bottom: 1.6rem;" @if($hasActiveFilters) open @endif>
            <summary class="admin-filter-summary">
                <span class="admin-filter-summary__title">Filtros de productos</span>
                <span class="admin-filter-summary__meta">
                    @if($hasActiveFilters)
                        {{ collect($filters)->filter(fn ($value) => $value !== null && $value !== '')->count() }} activos
                    @else
                        Abrir filtros
                    @endif
                </span>
            </summary>

            <form method="GET" action="{{ route('admin.products.index') }}" class="admin-filter-panel">
                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="search">Producto o SKU</label>
                        <input id="search" type="text" name="search" class="form-control" value="{{ $filters['search'] }}" placeholder="Busca por nombre o SKU">
                    </div>
                    <div class="admin-field">
                        <label for="store_id">Tienda</label>
                        <select id="store_id" name="store_id" class="form-control">
                            <option value="">Todas las tiendas</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" @selected((string) $filters['store_id'] === (string) $store->id)>{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="category_id">Categoria</label>
                        <select id="category_id" name="category_id" class="form-control">
                            <option value="">Todas las categorias</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) $filters['category_id'] === (string) $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-field">
                        <label for="status">Estado</label>
                        <select id="status" name="status" class="form-control">
                            <option value="">Todos</option>
                            <option value="active" @selected($filters['status'] === 'active')>Activo</option>
                            <option value="inactive" @selected($filters['status'] === 'inactive')>Inactivo</option>
                        </select>
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="featured">Destacado</label>
                        <select id="featured" name="featured" class="form-control">
                            <option value="">Todos</option>
                            <option value="yes" @selected($filters['featured'] === 'yes')>Solo destacados</option>
                            <option value="no" @selected($filters['featured'] === 'no')>Solo no destacados</option>
                        </select>
                    </div>
                    <div class="admin-field">
                        <label for="stock_min">Stock minimo</label>
                        <input id="stock_min" type="number" name="stock_min" class="form-control" min="0" step="1" value="{{ $filters['stock_min'] }}">
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="price_min_display">Precio minimo</label>
                        <input id="price_min" type="hidden" name="price_min" value="{{ $filters['price_min'] }}">
                        <input id="price_min_display" type="text" class="form-control js-money-filter" inputmode="numeric" data-target="price_min" value="{{ $filters['price_min'] !== null ? number_format($filters['price_min'], 0, ',', '.') : '' }}" placeholder="Ej: 15.000">
                    </div>
                    <div class="admin-field">
                        <label for="price_max_display">Precio maximo</label>
                        <input id="price_max" type="hidden" name="price_max" value="{{ $filters['price_max'] }}">
                        <input id="price_max_display" type="text" class="form-control js-money-filter" inputmode="numeric" data-target="price_max" value="{{ $filters['price_max'] !== null ? number_format($filters['price_max'], 0, ',', '.') : '' }}" placeholder="Ej: 150.000">
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="stock_max">Stock maximo</label>
                        <input id="stock_max" type="number" name="stock_max" class="form-control" min="0" step="1" value="{{ $filters['stock_max'] }}">
                    </div>
                    <div class="admin-field admin-filter-actions">
                        <button type="submit" class="admin-btn admin-btn--primary">Filtrar</button>
                        <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn--secondary">Limpiar</a>
                    </div>
                </div>
            </form>
        </details>

        @if($products->isEmpty())
            <div class="admin-empty">
                {{ $hasActiveFilters
                    ? 'No encontramos productos con los filtros aplicados. Ajusta los criterios o limpia la busqueda para ver todo el catalogo.'
                    : 'Aun no hay productos en el catalogo. Carga el primero para comenzar a vender.' }}
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
                                <div class="admin-action-icons" aria-label="Acciones del producto {{ $product->name }}">
                                    <a
                                        href="{{ route('admin.products.show', $product) }}"
                                        class="admin-icon-action"
                                        data-tooltip="Ver producto"
                                        aria-label="Ver producto"
                                    >
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                    </a>
                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        class="admin-icon-action"
                                        data-tooltip="Editar producto"
                                        aria-label="Editar producto"
                                    >
                                        <i class="fas fa-pen" aria-hidden="true"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('¿Eliminar este producto?');">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="admin-icon-action admin-icon-action--danger"
                                            data-tooltip="Eliminar producto"
                                            aria-label="Eliminar producto"
                                            style="padding: 0;"
                                        >
                                            <i class="fas fa-trash-alt" aria-hidden="true"></i>
                                        </button>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const moneyFilters = document.querySelectorAll('.js-money-filter');

            function formatMoneyDigits(value) {
                if (!value) {
                    return '';
                }

                return new Intl.NumberFormat('es-CO', {
                    maximumFractionDigits: 0,
                }).format(Number(value));
            }

            function syncMoneyInput(input) {
                const target = document.getElementById(input.dataset.target);

                if (!target) {
                    return;
                }

                const digits = input.value.replace(/\D+/g, '');
                target.value = digits;
                input.value = formatMoneyDigits(digits);
            }

            moneyFilters.forEach((input) => {
                input.addEventListener('input', () => syncMoneyInput(input));
                input.addEventListener('blur', () => syncMoneyInput(input));
                syncMoneyInput(input);
            });
        });
    </script>
@endpush
