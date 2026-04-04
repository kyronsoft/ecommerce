@extends('layouts.admin', [
    'breadcrumb' => 'Suscripciones emprendedores',
    'pageTitle' => 'Planes de emprendedores',
    'pageDescription' => 'Consulta las solicitudes de planes, el valor pagado y el estado comercial de cada suscripcion registrada en el marketplace.',
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
            <span class="admin-stat-label">Total</span>
            <strong class="admin-stat-value">{{ $stats['total'] }}</strong>
            <span class="admin-stat-help">Suscripciones registradas</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Aprobadas</span>
            <strong class="admin-stat-value">{{ $stats['approved'] }}</strong>
            <span class="admin-stat-help">Pagos confirmados</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Pendientes</span>
            <strong class="admin-stat-value">{{ $stats['pending'] }}</strong>
            <span class="admin-stat-help">Aun esperan confirmacion</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Valor aprobado</span>
            <strong class="admin-stat-value">${{ number_format($stats['sales'], 0, ',', '.') }}</strong>
            <span class="admin-stat-help">Ingresos por planes pagados</span>
        </article>
    </div>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar-copy">
                <h2>Suscripciones de emprendedores</h2>
                <p>Esta vista solo esta disponible para el administrador general del marketplace.</p>
            </div>
        </div>

        @if($subscriptions->isEmpty())
            <div class="admin-empty">
                Todavia no hay suscripciones de emprendedores registradas.
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Solicitud</th>
                        <th>Emprendedor</th>
                        <th>Tienda</th>
                        <th>Plan</th>
                        <th>Valor</th>
                        <th>Estado pago</th>
                        <th>Categoria</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subscriptions as $subscription)
                        @php
                            $payload = $subscription->request_payload ?? [];
                            $entrepreneur = $payload['entrepreneur'] ?? [];
                            $plan = $payload['plan'] ?? [];
                            $fullName = trim(($entrepreneur['first_name'] ?? '').' '.($entrepreneur['last_name'] ?? ''));
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $subscription->order_ref }}</strong>
                                <div class="admin-muted">{{ $subscription->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td>
                                <strong>{{ $fullName !== '' ? $fullName : ($subscription->order?->customer?->full_name ?? 'Sin nombre') }}</strong>
                                <div class="admin-muted">{{ $entrepreneur['email'] ?? ($subscription->order?->customer?->email ?? 'Sin correo') }}</div>
                                @if(!empty($entrepreneur['phone']))
                                    <div class="admin-muted">{{ $entrepreneur['phone'] }}</div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $entrepreneur['store_name'] ?? 'Sin tienda' }}</strong>
                                @if(!empty($entrepreneur['city']) || !empty($entrepreneur['department']))
                                    <div class="admin-muted">
                                        {{ trim(($entrepreneur['city'] ?? '').', '.($entrepreneur['department'] ?? ''), ', ') }}
                                    </div>
                                @endif
                            </td>
                            <td>{{ $plan['name'] ?? 'Plan no disponible' }}</td>
                            <td>${{ number_format((float) ($subscription->amount ?? ($plan['price'] ?? 0)), 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $status = $subscription->effective_status ?? $subscription->status;
                                @endphp
                                <span class="admin-status admin-status--{{ $status }}">
                                    {{ $statusLabels[$status] ?? ucfirst($status) }}
                                </span>
                            </td>
                            <td>{{ $entrepreneur['business_category'] ?? 'Sin categoria' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                {{ $subscriptions->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </section>
@endsection
