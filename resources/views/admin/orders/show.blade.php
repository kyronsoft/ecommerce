@extends('layouts.admin', [
    'breadcrumb' => 'Detalle pedido',
    'pageTitle' => $order->number,
    'pageDescription' => 'Revisa cliente, items, totales y estado de pago del pedido seleccionado.',
])

@php
    $orderStatusClass = [
        'pending' => 'pending',
        'paid' => 'paid',
        'processing' => 'processing',
        'shipped' => 'shipped',
        'completed' => 'completed',
        'cancelled' => 'cancelled',
    ][$order->status] ?? 'pending';
@endphp

@section('page_actions')
    <a href="{{ route('admin.orders.edit', $order) }}" class="admin-btn">Editar pedido</a>
    <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('¿Eliminar este pedido?');" style="display: inline-flex;">
        @csrf
        @method('DELETE')
        <button type="submit" class="admin-btn admin-btn--danger">Eliminar</button>
    </form>
@endsection

@section('content')
    <div class="admin-grid-3">
        <section class="admin-panel">
            <div class="admin-section-head">
                <div>
                    <h2>Estado del pedido</h2>
                    <p>Situacion general y metodo de pago.</p>
                </div>
            </div>
            <div class="admin-kv">
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Estado</span>
                    <span class="admin-kv-value">
                        <span class="admin-status admin-status--{{ $orderStatusClass }}">{{ $statusLabels[$order->status] ?? ucfirst($order->status) }}</span>
                    </span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Metodo de pago</span>
                    <span class="admin-kv-value">{{ $paymentLabels[$order->payment_method] ?? ($order->payment_method ?: 'No definido') }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Creado</span>
                    <span class="admin-kv-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Ultima actualizacion</span>
                    <span class="admin-kv-value">{{ $order->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-section-head">
                <div>
                    <h2>Cliente</h2>
                    <p>Datos usados para contacto y envio.</p>
                </div>
            </div>
            <div class="admin-kv">
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Nombre</span>
                    <span class="admin-kv-value">{{ $order->customer->full_name }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Email</span>
                    <span class="admin-kv-value">{{ $order->customer->email }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Telefono</span>
                    <span class="admin-kv-value">{{ $order->customer->phone ?: 'No registrado' }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Direccion de envio</span>
                    <span class="admin-kv-value">{{ $order->shipping_address ?: 'No registrada' }}</span>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-section-head">
                <div>
                    <h2>Totales</h2>
                    <p>Resumen economico del pedido.</p>
                </div>
            </div>
            <div class="admin-kv">
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Subtotal</span>
                    <span class="admin-kv-value">${{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Impuestos</span>
                    <span class="admin-kv-value">${{ number_format($order->tax, 0, ',', '.') }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Envio</span>
                    <span class="admin-kv-value">${{ number_format($order->shipping, 0, ',', '.') }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Total</span>
                    <span class="admin-kv-value">${{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </section>
    </div>

    <section class="admin-panel" style="margin-top: 1.8rem;">
        <div class="admin-section-head">
            <div>
                <h2>Items del pedido</h2>
                <p>Detalle de productos, cantidades y subtotales.</p>
            </div>
        </div>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                <tr>
                    <th>Producto</th>
                    <th>SKU</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->sku }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->unit_price, 0, ',', '.') }}</td>
                        <td>${{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="admin-panel" style="margin-top: 1.8rem;">
        <div class="admin-section-head">
            <div>
                <h2>Transacciones de pago</h2>
                <p>Seguimiento del intento de cobro asociado al pedido.</p>
            </div>
        </div>

        @if($order->paymentTransactions->isEmpty())
            <div class="admin-empty">
                Este pedido no tiene transacciones registradas.
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Gateway</th>
                        <th>Estado</th>
                        <th>Monto</th>
                        <th>Moneda</th>
                        <th>Notificacion</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->paymentTransactions as $transaction)
                        <tr>
                            <td>{{ strtoupper($transaction->gateway) }}</td>
                            <td><span class="admin-status admin-status--{{ $transaction->status }}">{{ ucfirst($transaction->status) }}</span></td>
                            <td>${{ number_format($transaction->amount, 0, ',', '.') }}</td>
                            <td>{{ $transaction->currency }}</td>
                            <td>
                                {{ $transaction->customer_notification_status ?: 'Sin envio' }}
                                @if($transaction->customer_notified_at)
                                    <div class="admin-muted">{{ $transaction->customer_notified_at->format('d/m/Y H:i') }}</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    @if($order->notes)
        <section class="admin-panel" style="margin-top: 1.8rem;">
            <div class="admin-section-head">
                <div>
                    <h2>Notas internas</h2>
                    <p>Observaciones registradas para este pedido.</p>
                </div>
            </div>
            <p>{{ $order->notes }}</p>
        </section>
    @endif
@endsection
