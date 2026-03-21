@extends('layouts.admin', [
    'breadcrumb' => 'Clientes',
    'pageTitle' => 'Gestion de clientes',
    'pageDescription' => 'Mantiene actualizada la base de clientes con sus datos de contacto y ubicacion oficial por departamento y ciudad.',
])

@section('page_actions')
    <a href="{{ route('admin.customers.create') }}" class="admin-btn admin-btn--primary">Nuevo cliente</a>
@endsection

@section('content')
    <div class="admin-grid-3" style="margin-bottom: 1.8rem;">
        <article class="admin-stat-card">
            <span class="admin-stat-label">Total</span>
            <strong class="admin-stat-value">{{ $stats['total'] }}</strong>
            <span class="admin-stat-help">Clientes registrados</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Con pedidos</span>
            <strong class="admin-stat-value">{{ $stats['with_orders'] }}</strong>
            <span class="admin-stat-help">Con historial comercial</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Sin pedidos</span>
            <strong class="admin-stat-value">{{ $stats['without_orders'] }}</strong>
            <span class="admin-stat-help">Leads listos para activar</span>
        </article>
    </div>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar-copy">
                <h2>Base de clientes</h2>
                <p>Revisa contacto, ubicacion y volumen de pedidos por cliente.</p>
            </div>
        </div>

        @if($customers->isEmpty())
            <div class="admin-empty">
                Todavia no hay clientes creados en el sistema.
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Email</th>
                        <th>Telefono</th>
                        <th>Ubicacion</th>
                        <th>Pedidos</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td><strong>{{ $customer->full_name }}</strong></td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?: 'No registrado' }}</td>
                            <td>{{ collect([$customer->city, $customer->department])->filter()->implode(', ') ?: 'Sin ubicacion' }}</td>
                            <td>{{ $customer->orders_count }}</td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="admin-link">Ver</a>
                                    <a href="{{ route('admin.customers.edit', $customer) }}" class="admin-link">Editar</a>
                                    <form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" onsubmit="return confirm('¿Eliminar este cliente?');">
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
                {{ $customers->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </section>
@endsection
