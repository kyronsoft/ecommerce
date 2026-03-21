@extends('layouts.admin', [
    'breadcrumb' => 'Dashboard',
    'pageTitle' => 'Panel general del ecommerce',
    'pageDescription' => 'Visualiza el estado operativo de la tienda, detecta pendientes y entra rapido a las acciones de carga mas importantes.',
])

@php
    $statusClasses = [
        'pending' => 'pending',
        'paid' => 'paid',
        'processing' => 'processing',
        'shipped' => 'shipped',
        'completed' => 'completed',
        'cancelled' => 'cancelled',
    ];
@endphp

@section('page_actions')
    <a href="{{ route('admin.orders.create') }}" class="admin-btn">Crear pedido</a>
    <a href="{{ route('admin.stores.create') }}" class="admin-btn">Crear tienda</a>
    <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn--primary">Cargar producto</a>
@endsection

@section('content')
    <div class="admin-stats-grid">
        <article class="admin-stat-card">
            <span class="admin-stat-label">Categorias</span>
            <strong class="admin-stat-value">{{ $stats['categories'] }}</strong>
            <span class="admin-stat-help">{{ $stats['stores'] }} tiendas registradas</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Productos</span>
            <strong class="admin-stat-value">{{ $stats['products'] }}</strong>
            <span class="admin-stat-help">{{ $stats['active_products'] }} activos para venta</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Pedidos</span>
            <strong class="admin-stat-value">{{ $stats['orders'] }}</strong>
            <span class="admin-stat-help">{{ $stats['pending_orders'] }} pendientes por gestionar</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Ventas acumuladas</span>
            <strong class="admin-stat-value">${{ number_format($stats['sales'], 0, ',', '.') }}</strong>
            <span class="admin-stat-help">Ticket promedio: ${{ number_format($stats['avg_ticket'] ?? 0, 0, ',', '.') }}</span>
        </article>
    </div>

    <div class="admin-grid-2">
        <section class="admin-panel">
            <div class="admin-section-head">
                <div>
                    <h2>Ultimos pedidos</h2>
                    <p>Seguimiento rapido de las compras mas recientes.</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="admin-link">Ver todos</a>
            </div>

            @if($latestOrders->isEmpty())
                <div class="admin-empty">
                    Todavia no hay pedidos registrados.
                </div>
            @else
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($latestOrders as $order)
                            <tr>
                                <td>
                                    <strong>{{ $order->number }}</strong>
                                    <div class="admin-muted">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>{{ $order->customer->full_name }}</td>
                                <td>
                                    <span class="admin-status admin-status--{{ $statusClasses[$order->status] ?? 'pending' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>${{ number_format($order->total, 0, ',', '.') }}</td>
                                <td><a href="{{ route('admin.orders.show', $order) }}" class="admin-link">Ver detalle</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <div class="admin-stack">
            <section class="admin-panel">
                <div class="admin-section-head">
                    <div>
                        <h3>Alertas operativas</h3>
                        <p>Productos con inventario critico y acciones sugeridas.</p>
                    </div>
                </div>

                @if($lowStockProducts->isEmpty())
                    <div class="admin-empty">
                        No hay productos en stock bajo.
                    </div>
                @else
                    <ul class="admin-list">
                        @foreach($lowStockProducts as $product)
                            <li>
                                <div>
                                    <strong>{{ $product->name }}</strong>
                                    <div class="admin-muted">{{ $product->category?->name ?? 'Sin categoria' }}</div>
                                </div>
                                <div class="admin-actions">
                                    <span class="admin-status admin-status--cancelled">{{ $product->stock }} und.</span>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="admin-link">Actualizar</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="admin-panel">
                <div class="admin-section-head">
                    <div>
                        <h3>Clientes recientes</h3>
                        <p>Ultimos contactos creados en la base comercial.</p>
                    </div>
                    <a href="{{ route('admin.customers.index') }}" class="admin-link">Gestionar</a>
                </div>

                @if($recentCustomers->isEmpty())
                    <div class="admin-empty">
                        Aun no hay clientes registrados.
                    </div>
                @else
                    <ul class="admin-list">
                        @foreach($recentCustomers as $customer)
                            <li>
                                <div>
                                    <strong>{{ $customer->full_name }}</strong>
                                    <div class="admin-muted">{{ $customer->email }}</div>
                                </div>
                                <a href="{{ route('admin.customers.show', $customer) }}" class="admin-link">Ver</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>

            <section class="admin-panel">
                <div class="admin-section-head">
                    <div>
                        <h3>Accesos rapidos</h3>
                        <p>Crea la informacion minima para que la tienda opere correctamente.</p>
                    </div>
                </div>
                <div class="admin-actions">
                    <a href="{{ route('admin.categories.create') }}" class="admin-btn">Nueva categoria</a>
                    <a href="{{ route('admin.stores.create') }}" class="admin-btn">Nueva tienda</a>
                    <a href="{{ route('admin.products.create') }}" class="admin-btn">Nuevo producto</a>
                    <a href="{{ route('admin.customers.create') }}" class="admin-btn">Nuevo cliente</a>
                    <a href="{{ route('admin.orders.create') }}" class="admin-btn admin-btn--primary">Nuevo pedido</a>
                </div>
            </section>
        </div>
    </div>
@endsection
