@extends('layouts.admin', [
    'breadcrumb' => 'Comision por venta',
    'pageTitle' => 'Comision por venta',
    'pageDescription' => 'Consulta venta por venta los descuentos aplicados al emprendedor por comision marketplace, IVA sobre la comision y costos de ePayco.',
])

@php
    $statusLabels = [
        'pending' => 'Pendiente',
        'approved' => 'Aprobado',
        'rejected' => 'Rechazado',
        'failed_validation' => 'Validacion fallida',
    ];
@endphp

@section('content')
    <div class="admin-grid-4" style="margin-bottom: 1.8rem;">
        <article class="admin-stat-card">
            <span class="admin-stat-label">Ventas</span>
            <strong class="admin-stat-value">{{ $stats['total'] }}</strong>
            <span class="admin-stat-help">Liquidaciones registradas</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Aprobadas</span>
            <strong class="admin-stat-value">{{ $stats['approved'] }}</strong>
            <span class="admin-stat-help">Con pago confirmado</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Descuentos</span>
            <strong class="admin-stat-value">${{ number_format($stats['deductions_amount'], 0, ',', '.') }}</strong>
            <span class="admin-stat-help">Retenido por el marketplace</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Neto emprendedor</span>
            <strong class="admin-stat-value">${{ number_format($stats['net_amount'], 0, ',', '.') }}</strong>
            <span class="admin-stat-help">Valor despues de descuentos</span>
        </article>
    </div>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar-copy">
                <h2>Comision por venta</h2>
                <p>
                    {{ $isSuperAdmin ? 'Vista global de todas las ventas aprobadas y sus descuentos por producto.' : 'Vista de las ventas de tu tienda y sus descuentos por producto.' }}
                    ePayco se esta calculando con {{ $epaycoRateLabel }} + {{ $epaycoFixedLabel }} por transaccion exitosa, prorrateando el cargo fijo entre los productos de cada orden.
                </p>
            </div>
        </div>

        @if($sales->isEmpty())
            <div class="admin-empty">
                Todavia no hay ventas liquidadas para mostrar.
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Venta</th>
                        <th>Producto</th>
                        <th>Tienda</th>
                        <th>Valor venta</th>
                        <th>Comision 5%</th>
                        <th>IVA 19%</th>
                        <th>ePayco %</th>
                        <th>ePayco fijo</th>
                        <th>Total descuento</th>
                        <th>Neto emprendedor</th>
                        <th>Pago</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sales as $sale)
                        @php
                            $orderItem = $sale->orderItem;
                            $productName = $sale->product?->name ?? ($orderItem?->name ?? 'Producto');
                            $sku = $sale->product?->sku ?? ($orderItem?->sku ?? 'Sin SKU');
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $sale->order?->number ?? ($sale->meta['order_number'] ?? 'Sin pedido') }}</strong>
                                <div class="admin-muted">{{ optional($sale->order?->created_at ?? $sale->created_at)->format('d/m/Y H:i') }}</div>
                                <div class="admin-muted">{{ $sale->order?->customer?->full_name ?? 'Sin cliente' }}</div>
                            </td>
                            <td>
                                <strong>{{ $productName }}</strong>
                                <div class="admin-muted">SKU: {{ $sku }}</div>
                                <div class="admin-muted">Cant. {{ $orderItem?->quantity ?? ($sale->meta['quantity'] ?? 1) }}</div>
                            </td>
                            <td>{{ $sale->store?->name ?? 'Sin tienda' }}</td>
                            <td>${{ number_format((float) $sale->sale_amount, 0, ',', '.') }}</td>
                            <td>
                                ${{ number_format((float) $sale->marketplace_commission_amount, 0, ',', '.') }}
                                <div class="admin-muted">{{ rtrim(rtrim(number_format((float) $sale->marketplace_commission_rate, 4, ',', '.'), '0'), ',') }}%</div>
                            </td>
                            <td>
                                ${{ number_format((float) $sale->marketplace_commission_vat_amount, 0, ',', '.') }}
                                <div class="admin-muted">{{ rtrim(rtrim(number_format((float) $sale->marketplace_commission_vat_rate, 4, ',', '.'), '0'), ',') }}%</div>
                            </td>
                            <td>
                                ${{ number_format((float) $sale->epayco_percentage_amount, 0, ',', '.') }}
                                <div class="admin-muted">{{ rtrim(rtrim(number_format((float) $sale->epayco_percentage_rate, 4, ',', '.'), '0'), ',') }}%</div>
                            </td>
                            <td>${{ number_format((float) $sale->epayco_fixed_fee_amount, 0, ',', '.') }}</td>
                            <td>${{ number_format((float) $sale->total_deduction_amount, 0, ',', '.') }}</td>
                            <td>${{ number_format((float) $sale->entrepreneur_net_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="admin-status admin-status--{{ $sale->payment_status }}">
                                    {{ $statusLabels[$sale->payment_status] ?? ucfirst($sale->payment_status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                {{ $sales->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </section>
@endsection
