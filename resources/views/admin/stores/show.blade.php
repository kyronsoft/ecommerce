@extends('layouts.admin', [
    'breadcrumb' => 'Detalle tienda',
    'pageTitle' => $store->name,
    'pageDescription' => 'Consulta la ficha del negocio, sus canales de contacto y los productos asociados dentro de la plataforma.',
])

@php
    $logoUrl = filled($store->logo) ? (\Illuminate\Support\Str::startsWith($store->logo, ['http://', 'https://']) ? $store->logo : asset($store->logo)) : null;
    $bannerUrl = filled($store->banner) ? (\Illuminate\Support\Str::startsWith($store->banner, ['http://', 'https://']) ? $store->banner : asset($store->banner)) : null;
@endphp

@section('page_actions')
    <a href="{{ route('store.store.show', $store->slug) }}" class="admin-btn">Ver pública</a>
    <a href="{{ route('admin.products.create', ['store' => $store->id]) }}" class="admin-btn">Agregar producto</a>
    <a href="{{ route('admin.stores.edit', $store) }}" class="admin-btn admin-btn--primary">Editar tienda</a>
@endsection

@section('content')
    <div class="admin-grid-2">
        <section class="admin-panel">
            @if($bannerUrl)
                <img src="{{ $bannerUrl }}" alt="{{ $store->name }}" class="admin-image-preview" style="max-width:100%; margin-bottom:1.6rem;">
            @endif

            <div class="admin-hero-media">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $store->name }}" style="border-radius:50%;">
                @endif
                <div>
                    <h2>{{ $store->name }}</h2>
                    <div class="admin-meta-row">
                        <span class="admin-status admin-status--{{ $store->is_active ? 'active' : 'inactive' }}">
                            {{ $store->is_active ? 'Activa' : 'Inactiva' }}
                        </span>
                        @if($store->is_featured)
                            <span class="admin-status admin-status--featured">Destacada</span>
                        @endif
                    </div>
                    <p style="margin-top:1.2rem;">{{ $store->short_description ?: 'Sin descripción corta.' }}</p>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kv">
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Responsable</span>
                    <span class="admin-kv-value">{{ $store->owner_name }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Correo</span>
                    <span class="admin-kv-value">{{ $store->email ?: 'No registrado' }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Teléfono</span>
                    <span class="admin-kv-value">{{ $store->phone ?: 'No registrado' }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Ubicación</span>
                    <span class="admin-kv-value">{{ $store->location ?: 'No registrada' }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Sitio web</span>
                    <span class="admin-kv-value">{{ $store->website ?: 'No registrado' }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Productos asociados</span>
                    <span class="admin-kv-value">{{ $store->products->count() }}</span>
                </div>
            </div>
        </section>
    </div>

    <section class="admin-panel" style="margin-top: 1.8rem;">
        <div class="admin-section-head">
            <div>
                <h3>Descripción</h3>
                <p>Detalle comercial mostrado en el perfil público de la tienda.</p>
            </div>
        </div>
        <p>{{ $store->description ?: 'Esta tienda todavía no tiene descripción completa.' }}</p>
    </section>

    <section class="admin-panel" style="margin-top: 1.8rem;">
        <div class="admin-section-head">
            <div>
                <h3>Productos recientes</h3>
                <p>Últimos productos asociados a este negocio.</p>
            </div>
        </div>

        @if($store->products->isEmpty())
            <div class="admin-empty">
                Esta tienda aún no tiene productos asignados.
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($store->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>${{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }}</td>
                            <td><a href="{{ route('admin.products.show', $product) }}" class="admin-link">Ver producto</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
