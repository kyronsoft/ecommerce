@extends('layouts.admin', [
    'breadcrumb' => 'Detalle producto',
    'pageTitle' => $product->name,
    'pageDescription' => 'Consulta la configuracion comercial y operativa del producto seleccionado.',
])

@php
    $imageUrl = filled($product->image)
        ? (\Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://'])
            ? $product->image
            : (\Illuminate\Support\Str::startsWith($product->image, 'storage/')
                ? route('admin.products.media', [$product, 'image'])
                : asset($product->image)))
        : null;
@endphp

@section('page_actions')
    <a href="{{ route('admin.products.edit', $product) }}" class="admin-btn admin-btn--primary">Editar producto</a>
@endsection

@section('content')
    <div class="admin-grid-2">
        <section class="admin-panel">
            <div class="admin-hero-media">
                @if($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
                @endif
                <div>
                    <h2>{{ $product->name }}</h2>
                    <div class="admin-meta-row">
                        <span class="admin-status admin-status--{{ $product->is_active ? 'active' : 'inactive' }}">
                            {{ $product->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                        @if($product->is_featured)
                            <span class="admin-status admin-status--featured">Destacado</span>
                        @endif
                        <span class="admin-status admin-status--processing">SKU: {{ $product->sku }}</span>
                    </div>
                    <div class="admin-price" style="margin-top: 1.4rem;">
                        ${{ number_format($product->price, 0, ',', '.') }}
                    </div>
                    <p style="margin-top: 1.2rem;">{{ $product->short_description ?: 'Sin descripcion corta.' }}</p>
                </div>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-kv">
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Tienda</span>
                    <span class="admin-kv-value">{{ $product->store?->name ?? 'Sin tienda asignada' }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Categoria</span>
                    <span class="admin-kv-value">{{ $product->category?->name ?? 'Sin categoria' }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Slug</span>
                    <span class="admin-kv-value">{{ $product->slug }}</span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Precio comparativo</span>
                    <span class="admin-kv-value">
                        {{ $product->compare_price ? '$'.number_format($product->compare_price, 0, ',', '.') : 'No configurado' }}
                    </span>
                </div>
                <div class="admin-kv-item">
                    <span class="admin-kv-label">Stock</span>
                    <span class="admin-kv-value">{{ $product->stock }} unidades</span>
                </div>
            </div>
        </section>
    </div>

    <section class="admin-panel" style="margin-top: 1.8rem;">
        <div class="admin-section-head">
            <div>
                <h3>Descripcion</h3>
                <p>Contenido completo mostrado en la ficha del producto.</p>
            </div>
        </div>
        <p>{{ $product->description ?: 'Este producto todavia no tiene descripcion detallada.' }}</p>
    </section>

    <section class="admin-panel" style="margin-top: 1.8rem;">
        <div class="admin-section-head">
            <div>
                <h3>Galeria</h3>
                <p>Imagenes adicionales configuradas para la ficha del producto.</p>
            </div>
        </div>

        @if(empty($product->gallery))
            <div class="admin-empty">
                No hay imagenes adicionales cargadas.
            </div>
        @else
            <div class="admin-grid-4">
                @foreach($product->gallery as $galleryImage)
                    @php
                        $galleryUrl = \Illuminate\Support\Str::startsWith($galleryImage, ['http://', 'https://'])
                            ? $galleryImage
                            : (\Illuminate\Support\Str::startsWith($galleryImage, 'storage/')
                                ? route('admin.products.media', [$product, 'gallery', $loop->index])
                                : asset($galleryImage));
                    @endphp
                    <img src="{{ $galleryUrl }}" alt="{{ $product->name }}" class="admin-image-preview">
                @endforeach
            </div>
        @endif
    </section>
@endsection
