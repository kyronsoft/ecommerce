@extends('layouts.admin', [
    'breadcrumb' => $product->exists ? 'Editar producto' : 'Nuevo producto',
    'pageTitle' => $product->exists ? 'Editar producto' : 'Crear producto',
    'pageDescription' => 'Completa informacion comercial, inventario, imagenes y descripciones para publicar correctamente en la tienda.',
])

@php
    $imageInput = old('image', $product->image);
    $imageUrl = filled($imageInput)
        ? (\Illuminate\Support\Str::startsWith($imageInput, ['http://', 'https://']) ? $imageInput : asset($imageInput))
        : null;
@endphp

@section('page_actions')
    <a href="{{ route('admin.products.index') }}" class="admin-btn">Volver al listado</a>
    @if($product->exists)
        <a href="{{ route('admin.products.show', $product) }}" class="admin-btn admin-btn--primary">Ver detalle</a>
    @endif
@endsection

@section('content')
    <form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        @if($product->exists)
            @method('PUT')
        @endif

        <div class="admin-grid-2">
            <section class="admin-panel">
                <div class="admin-section-head">
                    <div>
                        <h2>Datos del producto</h2>
                        <p>Informacion obligatoria para catalogo, checkout y control de inventario.</p>
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="name">Nombre</label>
                        <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>
                    <div class="admin-field">
                        <label for="sku">SKU</label>
                        <input id="sku" type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}" required>
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="store_id">Tienda</label>
                        <select id="store_id" name="store_id" class="form-control">
                            <option value="">Sin asignar</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" @selected((string) old('store_id', $product->store_id) === (string) $store->id)>
                                    {{ $store->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-field">
                        <label for="category_id">Categoria</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">Selecciona una categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-field">
                        <label for="slug">Slug</label>
                        <input id="slug" type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug) }}">
                        <small>Se genera automaticamente si lo dejas vacio.</small>
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="price">Precio</label>
                        <input id="price" type="number" name="price" class="form-control" min="0" step="0.01" value="{{ old('price', $product->price) }}" required>
                    </div>
                    <div class="admin-field">
                        <label for="compare_price">Precio comparativo</label>
                        <input id="compare_price" type="number" name="compare_price" class="form-control" min="0" step="0.01" value="{{ old('compare_price', $product->compare_price) }}">
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="stock">Stock</label>
                        <input id="stock" type="number" name="stock" class="form-control" min="0" step="1" value="{{ old('stock', $product->stock) }}" required>
                    </div>
                    <div class="admin-field">
                        <label for="image">Imagen principal</label>
                        <input id="image" type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/png,image/jpeg,image/webp">
                        <small>La imagen se guardará en `storage/stores/{tienda}/products` según la tienda seleccionada.</small>
                        @error('image')
                            <small class="admin-field-error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="admin-field">
                    <label for="gallery">Galeria</label>
                    <input id="gallery" type="text" name="gallery" class="form-control" value="{{ old('gallery', is_array($product->gallery) ? implode(', ', $product->gallery) : '') }}">
                    <small>Separa varias rutas o URLs con coma.</small>
                </div>

                <div class="admin-field">
                    <label for="short_description">Descripcion corta</label>
                    <input id="short_description" type="text" name="short_description" class="form-control" value="{{ old('short_description', $product->short_description) }}">
                </div>

                <div class="admin-field">
                    <label for="description">Descripcion completa</label>
                    <textarea id="description" name="description" class="form-control" rows="7">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="admin-checkbox-row">
                    <label class="admin-checkbox">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))>
                        Producto activo
                    </label>
                    <label class="admin-checkbox">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>
                        Mostrar como destacado
                    </label>
                </div>
            </section>

            <section class="admin-panel">
                <div class="admin-section-head">
                    <div>
                        <h2>Revision rapida</h2>
                        <p>Comprueba como quedaran precio, estado y recurso grafico.</p>
                    </div>
                </div>

                @if($imageUrl)
                    <img src="{{ $imageUrl }}" alt="{{ old('name', $product->name ?: 'Producto') }}" class="admin-image-preview" style="margin-bottom: 1.4rem;">
                @else
                    <div class="admin-empty" style="margin-bottom: 1.4rem;">
                        Aun no se ha definido una imagen principal.
                    </div>
                @endif

                <div class="admin-kv">
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Tienda</span>
                        <span class="admin-kv-value">
                            {{ $stores->firstWhere('id', (int) old('store_id', $product->store_id))?->name ?? 'Sin asignar' }}
                        </span>
                    </div>
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Precio de venta</span>
                        <span class="admin-kv-value">${{ number_format((float) old('price', $product->price), 0, ',', '.') }}</span>
                    </div>
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Stock disponible</span>
                        <span class="admin-kv-value">{{ old('stock', $product->stock ?? 0) }} unidades</span>
                    </div>
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Estado comercial</span>
                        <span class="admin-kv-value">{{ old('is_active', $product->is_active) ? 'Activo' : 'Inactivo' }}</span>
                    </div>
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Notas</span>
                        <span class="admin-kv-value">Recuerda cargar categoria, SKU, precio e imagen para que el producto quede listo para vender.</span>
                    </div>
                </div>
            </section>
        </div>

        <div class="admin-actions" style="margin-top: 1.8rem;">
            <button type="submit" class="admin-btn admin-btn--primary">Guardar producto</button>
            <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn--secondary">Cancelar</a>
        </div>
    </form>
@endsection
