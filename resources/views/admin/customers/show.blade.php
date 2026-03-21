@extends('layouts.admin', [
    'breadcrumb' => 'Detalle cliente',
    'pageTitle' => $customer->full_name,
    'pageDescription' => 'Consulta la ficha del cliente y su historial reciente de pedidos.',
])

@section('page_actions')
    <a href="{{ route('admin.orders.create', ['customer' => $customer->id]) }}" class="admin-btn">Crear pedido</a>
    <a href="{{ route('admin.customers.edit', $customer) }}" class="admin-btn admin-btn--primary">Editar cliente</a>
@endsection

@section('content')
    <div class="admin-grid-2">
        <section class="admin-panel">
            <div class="admin-section-head">
                <div>
                    <h2>Informacion de contacto</h2>
                    <p>Datos de referencia para soporte, envios y cobro.</p>
                </div>
            </div>

            <div class="admin-kv">
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Email</span>
                    <span class="admin-kv-value">{{ $customer->email }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Telefono</span>
                    <span class="admin-kv-value">{{ $customer->phone ?: 'No registrado' }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Departamento</span>
                    <span class="admin-kv-value">{{ $customer->department ?: 'No registrado' }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Ciudad</span>
                    <span class="admin-kv-value">{{ $customer->city ?: 'No registrada' }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Direccion</span>
                    <span class="admin-kv-value">{{ $customer->address ?: 'No registrada' }}</span>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-section-head">
                <div>
                    <h2>Resumen comercial</h2>
                    <p>Actividad del cliente en la tienda.</p>
                </div>
            </div>

            <div class="admin-grid-2">
                <article class="admin-stat-card">
                    <span class="admin-stat-label">Pedidos</span>
                    <strong class="admin-stat-value">{{ $customer->orders->count() }}</strong>
                    <span class="admin-stat-help">Compras registradas</span>
                </article>
                <article class="admin-stat-card">
                    <span class="admin-stat-label">Facturacion</span>
                    <strong class="admin-stat-value">${{ number_format($customer->orders->sum('total'), 0, ',', '.') }}</strong>
                    <span class="admin-stat-help">Valor total comprado</span>
                </article>
            </div>
        </section>
    </div>

    <section class="admin-panel" style="margin-top: 1.8rem;">
        <div class="admin-section-head">
            <div>
                <h2>Pedidos del cliente</h2>
                <p>Ultimas ordenes asociadas a esta ficha.</p>
            </div>
        </div>

        @if($customer->orders->isEmpty())
            <div class="admin-empty">
                Este cliente aun no tiene pedidos registrados.
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customer->orders as $order)
                        <tr>
                            <td>{{ $order->number }}</td>
                            <td><span class="admin-status admin-status--{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                            <td>${{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td><a href="{{ route('admin.orders.show', $order) }}" class="admin-link">Ver pedido</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
