@extends('layouts.store')

@section('content')
@php
    $gatewayData = $data ?? [];
    $responseText = $gatewayData['x_response'] ?? ucfirst((string) ($transaction->status ?? 'Sin respuesta'));
    $reasonText = $gatewayData['x_response_reason_text'] ?? 'Sin detalle reportado por ePayco.';
    $reference = $responseReference ?: ($gatewayData['x_ref_payco'] ?? null);
    $transactionId = $gatewayData['x_transaction_id'] ?? null;
    $franchise = $gatewayData['x_franchise'] ?? null;
    $transactionDate = $gatewayData['x_transaction_date'] ?? null;
    $business = $gatewayData['x_business'] ?? 'ePayco';
    $customerEmail = $gatewayData['x_customer_email'] ?? $order?->customer?->email;
    $rawAmount = $gatewayData['x_amount'] ?? $transaction?->amount ?? $order?->total;
    $displayAmount = $rawAmount !== null ? '$'.number_format((float) $rawAmount, 0, ',', '.').' '.($gatewayData['x_currency_code'] ?? $transaction?->currency ?? 'COP') : 'Sin dato';
@endphp

<main class="main">
    <div class="page-content epayco-response-page pt-10 pb-10">
        <div class="container">
            <div class="epayco-response-shell epayco-response-shell--{{ $statusView['key'] }}">
                <div class="epayco-response-hero">
                    <span class="epayco-response-eyebrow">{{ $statusView['eyebrow'] }}</span>
                    <div class="epayco-response-heading">
                        <div>
                            <h1 class="epayco-response-title">{{ $statusView['title'] }}</h1>
                            <p class="epayco-response-message">{{ $statusView['message'] }}</p>
                        </div>
                        <span class="epayco-response-badge">{{ $statusView['badge'] }}</span>
                    </div>
                </div>

                @if($lookupFailed)
                    <div class="epayco-response-alert epayco-response-alert--warning">
                        No fue posible consultar el detalle completo de la transacción con `ref_payco`. Mostramos la información disponible recibida en la redirección del navegador.
                    </div>
                @endif

                <div class="epayco-response-grid">
                    <section class="epayco-response-card">
                        <h2 class="epayco-response-card-title">Resumen del pago</h2>

                        <div class="epayco-response-list">
                            <div class="epayco-response-row">
                                <span>Pedido</span>
                                <strong>{{ $order?->number ?? $transaction?->order_ref ?? 'Sin asociar' }}</strong>
                            </div>
                            <div class="epayco-response-row">
                                <span>Estado reportado</span>
                                <strong>{{ $responseText }}</strong>
                            </div>
                            <div class="epayco-response-row">
                                <span>Total</span>
                                <strong>{{ $displayAmount }}</strong>
                            </div>
                            <div class="epayco-response-row">
                                <span>Motivo</span>
                                <strong>{{ $reasonText }}</strong>
                            </div>
                        </div>
                    </section>

                    <section class="epayco-response-card">
                        <h2 class="epayco-response-card-title">Detalle de la transacción</h2>

                        <div class="epayco-response-list">
                            <div class="epayco-response-row">
                                <span>Referencia ePayco</span>
                                <strong>{{ $reference ?: 'Sin dato' }}</strong>
                            </div>
                            <div class="epayco-response-row">
                                <span>ID transacción</span>
                                <strong>{{ $transactionId ?: 'Sin dato' }}</strong>
                            </div>
                            <div class="epayco-response-row">
                                <span>Fecha</span>
                                <strong>{{ $transactionDate ?: 'Sin dato' }}</strong>
                            </div>
                            <div class="epayco-response-row">
                                <span>Medio de pago</span>
                                <strong>{{ $franchise ?: 'Sin dato' }}</strong>
                            </div>
                            <div class="epayco-response-row">
                                <span>Comercio</span>
                                <strong>{{ $business }}</strong>
                            </div>
                            <div class="epayco-response-row">
                                <span>Correo del cliente</span>
                                <strong>{{ $customerEmail ?: 'Sin dato' }}</strong>
                            </div>
                        </div>
                    </section>
                </div>

                @if(in_array($transaction?->status, ['approved', 'rejected'], true) && $customerEmail)
                    <div class="epayco-response-alert">
                        Tambien enviamos un correo de confirmacion a <strong>{{ $customerEmail }}</strong> con el resumen de esta transaccion y el detalle del pedido.
                    </div>
                @endif

                <div class="epayco-response-actions">
                    @if($transaction?->status === 'approved')
                        <a href="{{ route('store.shop') }}" class="btn btn-primary">Seguir comprando</a>
                        <a href="{{ route('store.home') }}" class="btn btn-outline">Volver al inicio</a>
                    @else
                        <a href="{{ route('store.checkout.index') }}" class="btn btn-primary">Intentar nuevamente</a>
                        <a href="{{ route('store.cart.index') }}" class="btn btn-outline">Volver al carrito</a>
                    @endif
                </div>

                <div class="epayco-response-alert">
                    ePayco indica que la validación definitiva del pago debe apoyarse en la URL de confirmación del backend. Por eso esta pantalla informa al cliente, pero el cierre real del estado queda respaldado por el webhook.
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
