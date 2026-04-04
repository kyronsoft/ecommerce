@extends('layouts.admin', [
    'breadcrumb' => 'Tiendas',
    'pageTitle' => 'Gestion de tiendas',
    'pageDescription' => 'Registra negocios, emprendimientos y marcas que harán parte del marketplace y asigna sus productos dentro de la plataforma.',
])

@section('page_actions')
    @if($currentAdminIsSuperAdmin ?? false)
        <a href="{{ route('admin.stores.create') }}" class="admin-btn admin-btn--primary">Nueva tienda</a>
    @endif
@endsection

@section('content')
    <div class="admin-grid-4" style="margin-bottom: 1.8rem;">
        <article class="admin-stat-card">
            <span class="admin-stat-label">Total</span>
            <strong class="admin-stat-value">{{ $stats['total'] }}</strong>
            <span class="admin-stat-help">Tiendas registradas</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Activas</span>
            <strong class="admin-stat-value">{{ $stats['active'] }}</strong>
            <span class="admin-stat-help">Visibles para clientes</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Destacadas</span>
            <strong class="admin-stat-value">{{ $stats['featured'] }}</strong>
            <span class="admin-stat-help">Con mayor visibilidad</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Con catálogo</span>
            <strong class="admin-stat-value">{{ $stats['with_products'] }}</strong>
            <span class="admin-stat-help">Ya tienen productos asignados</span>
        </article>
    </div>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar-copy">
                <h2>Directorio de tiendas</h2>
                <p>Administra información comercial, canales de contacto y estado de publicación.</p>
            </div>
        </div>

        @if($stores->isEmpty())
            <div class="admin-empty">
                Aún no hay tiendas registradas. Crea la primera para empezar a poblar el marketplace.
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Tienda</th>
                        <th>Responsable</th>
                        <th>Contacto</th>
                        <th>Productos</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stores as $store)
                        <tr>
                            <td>
                                <strong>{{ $store->name }}</strong>
                                <div class="admin-muted">{{ $store->location ?: 'Sin ubicación' }}</div>
                            </td>
                            <td>{{ $store->owner_name }}</td>
                            <td>{{ $store->email ?: ($store->phone ?: 'Sin contacto') }}</td>
                            <td>{{ $store->products_count }}</td>
                            <td>
                                <div class="admin-actions">
                                    <span class="admin-status admin-status--{{ $store->is_active ? 'active' : 'inactive' }}">
                                        {{ $store->is_active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                    @if($store->is_featured)
                                        <span class="admin-status admin-status--featured">Destacada</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.stores.show', $store) }}" class="admin-link">Ver</a>
                                    <a href="{{ route('admin.stores.edit', $store) }}" class="admin-link">Editar</a>
                                    <form method="POST" action="{{ route('admin.stores.destroy', $store) }}" onsubmit="return confirm('¿Eliminar esta tienda?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-link" style="border:0; background:transparent; padding:0;">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                {{ $stores->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </section>
@endsection
