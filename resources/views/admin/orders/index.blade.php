@extends('layouts.admin', [
    'breadcrumb' => 'Pedidos',
    'pageTitle' => 'Gestion de pedidos',
    'pageDescription' => 'Controla el estado de cada orden, su cliente, el total vendido y el avance operativo de la compra.',
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
    <a href="{{ route('admin.customers.create') }}" class="admin-btn">Nuevo cliente</a>
    <a href="{{ route('admin.orders.create') }}" class="admin-btn admin-btn--primary">Nuevo pedido</a>
@endsection

@section('content')
    <div class="admin-grid-4" style="margin-bottom: 1.8rem;">
        <article class="admin-stat-card">
            <span class="admin-stat-label">Total</span>
            <strong class="admin-stat-value">{{ $stats['total'] }}</strong>
            <span class="admin-stat-help">Pedidos creados</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Pendientes</span>
            <strong class="admin-stat-value">{{ $stats['pending'] }}</strong>
            <span class="admin-stat-help">Requieren seguimiento</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Pagados</span>
            <strong class="admin-stat-value">{{ $stats['paid'] }}</strong>
            <span class="admin-stat-help">Con confirmacion de pago</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Ventas</span>
            <strong class="admin-stat-value">${{ number_format($stats['sales'], 0, ',', '.') }}</strong>
            <span class="admin-stat-help">Valor acumulado</span>
        </article>
    </div>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar-copy">
                <h2>Pedidos registrados</h2>
                <p>Consulta estado, total, cliente y actividad de cada orden.</p>
            </div>
        </div>

        @if($orders->isEmpty())
            <div class="admin-empty">
                Todavia no hay pedidos cargados.
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Pago</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        @php
                            $paymentStatus = $order->paymentTransactions->sortByDesc('created_at')->first()?->status;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $order->number }}</strong>
                                <div class="admin-muted">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>{{ $order->customer->full_name }}</td>
                            <td>
                                <span class="admin-status admin-status--{{ $statusClasses[$order->status] ?? 'pending' }}">
                                    {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ $order->items_count }}</td>
                            <td>${{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>
                                @if($paymentStatus)
                                    <span class="admin-status admin-status--{{ $paymentStatus }}">
                                        {{ ucfirst($paymentStatus) }}
                                    </span>
                                @else
                                    <span class="admin-muted">Sin transaccion</span>
                                @endif
                            </td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="admin-link">Ver</a>
                                    <a href="{{ route('admin.orders.edit', $order) }}" class="admin-link">Editar</a>
                                    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('¿Eliminar este pedido?');">
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
                {{ $orders->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </section>
@endsection
