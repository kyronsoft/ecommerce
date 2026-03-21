@extends('layouts.admin', [
    'breadcrumb' => $category->exists ? 'Editar categoria' : 'Nueva categoria',
    'pageTitle' => $category->exists ? 'Editar categoria' : 'Crear categoria',
    'pageDescription' => 'Completa la informacion base para que los productos se organicen correctamente en la tienda.',
])

@php
    $imageUrl = filled(old('image', $category->image))
        ? (\Illuminate\Support\Str::startsWith(old('image', $category->image), ['http://', 'https://']) ? old('image', $category->image) : asset(old('image', $category->image)))
        : null;
@endphp

@section('page_actions')
    <a href="{{ route('admin.categories.index') }}" class="admin-btn">Volver al listado</a>
    @if($category->exists)
        <a href="{{ route('admin.categories.show', $category) }}" class="admin-btn admin-btn--primary">Ver detalle</a>
    @endif
@endsection

@section('content')
    <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
        @csrf
        @if($category->exists)
            @method('PUT')
        @endif

        <div class="admin-grid-2">
            <section class="admin-panel">
                <div class="admin-section-head">
                    <div>
                        <h2>Informacion principal</h2>
                        <p>Usa nombres claros y un slug limpio para URLs amigables.</p>
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="name">Nombre</label>
                        <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                    </div>

                    <div class="admin-field">
                        <label for="slug">Slug</label>
                        <input id="slug" type="text" name="slug" class="form-control" value="{{ old('slug', $category->slug) }}">
                        <small>Si lo dejas vacio, se genera automaticamente.</small>
                    </div>
                </div>

                <div class="admin-field">
                    <label for="image">Imagen o ruta publica</label>
                    <input id="image" type="text" name="image" class="form-control" value="{{ old('image', $category->image) }}">
                    <small>Ejemplo: `wolmart/assets/images/categories/cocina.jpg` o una URL completa.</small>
                </div>

                <div class="admin-field">
                    <label for="description">Descripcion</label>
                    <textarea id="description" name="description" class="form-control" rows="6">{{ old('description', $category->description) }}</textarea>
                </div>
            </section>

            <section class="admin-panel">
                <div class="admin-section-head">
                    <div>
                        <h2>Vista previa</h2>
                        <p>Verifica la imagen de referencia y el contenido descriptivo.</p>
                    </div>
                </div>

                @if($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ old('name', $category->name ?: 'Categoria') }}" class="admin-image-preview">
                @else
                    <div class="admin-empty" style="margin-bottom: 1.4rem;">
                        La categoria aun no tiene imagen configurada.
                    </div>
                @endif

                <div class="admin-kv">
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Nombre visible</span>
                        <span class="admin-kv-value">{{ old('name', $category->name ?: 'Sin definir') }}</span>
                    </div>
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Slug</span>
                        <span class="admin-kv-value">{{ old('slug', $category->slug ?: 'Se generara automaticamente') }}</span>
                    </div>
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Uso recomendado</span>
                        <span class="admin-kv-value">Agrupar productos con la misma familia comercial.</span>
                    </div>
                </div>
            </section>
        </div>

        <div class="admin-actions" style="margin-top: 1.8rem;">
            <button type="submit" class="admin-btn admin-btn--primary">Guardar categoria</button>
            <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn--secondary">Cancelar</a>
        </div>
    </form>
@endsection
