@extends('layouts.admin', [
    'breadcrumb' => $product->exists ? 'Editar producto' : 'Nuevo producto',
    'pageTitle' => $product->exists ? 'Editar producto' : 'Crear producto',
    'pageDescription' => 'Completa informacion comercial, inventario, imagenes y descripciones para publicar correctamente en la tienda.',
])

@php
    $imageInput = old('image', $product->image);
    $imageUrl = filled($imageInput)
        ? (\Illuminate\Support\Str::startsWith($imageInput, ['http://', 'https://'])
            ? $imageInput
            : (($product->exists && \Illuminate\Support\Str::startsWith($imageInput, 'storage/'))
                ? route('admin.products.media', [$product, 'image'])
                : asset($imageInput)))
        : null;
    $galleryItems = collect(old('gallery_existing', $product->gallery ?? []))
        ->map(fn ($item) => trim((string) $item))
        ->filter()
        ->values();
    $isCreate = ! $product->exists;
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
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="sku">SKU</label>
                        <input
                            id="sku"
                            type="text"
                            name="sku"
                            class="form-control"
                            value="{{ old('sku', $product->sku) }}"
                            @if($isCreate) readonly @endif
                            required
                        >
                        @if($isCreate)
                            <small>Se genera automaticamente segun la tienda seleccionada y su consecutivo disponible.</small>
                        @endif
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
                        <input id="price" type="hidden" name="price" value="{{ (int) old('price', $product->price) }}">
                        <input
                            id="price_display"
                            type="text"
                            class="form-control js-money-display"
                            inputmode="numeric"
                            data-target="price"
                            value="{{ number_format((float) old('price', $product->price), 0, ',', '.') }}"
                            required
                        >
                        <small>Se visualiza en pesos colombianos sin centavos.</small>
                    </div>
                    <div class="admin-field">
                        <label for="compare_price">Precio comparativo</label>
                        <input id="compare_price" type="hidden" name="compare_price" value="{{ filled(old('compare_price', $product->compare_price)) ? (int) old('compare_price', $product->compare_price) : '' }}">
                        <input
                            id="compare_price_display"
                            type="text"
                            class="form-control js-money-display"
                            inputmode="numeric"
                            data-target="compare_price"
                            value="{{ filled(old('compare_price', $product->compare_price)) ? number_format((float) old('compare_price', $product->compare_price), 0, ',', '.') : '' }}"
                        >
                        <small>Usa este valor como precio de referencia o precio anterior.</small>
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
                    <input id="gallery" type="file" name="gallery_files[]" class="form-control @error('gallery_files') is-invalid @enderror @error('gallery_files.*') is-invalid @enderror" accept="image/png,image/jpeg,image/webp" multiple>
                    <small>Sube una o varias imagenes alternas del producto. Puedes previsualizarlas antes de guardar.</small>
                    @error('gallery_files')
                        <small class="admin-field-error">{{ $message }}</small>
                    @enderror
                    @error('gallery_files.*')
                        <small class="admin-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="admin-field">
                    <label>Imagenes adicionales</label>
                    <div class="admin-gallery-manager" id="gallery-existing-list">
                        @forelse($galleryItems as $galleryImage)
                            @php
                                $galleryUrl = \Illuminate\Support\Str::startsWith($galleryImage, ['http://', 'https://'])
                                    ? $galleryImage
                                    : (($product->exists && \Illuminate\Support\Str::startsWith($galleryImage, 'storage/'))
                                        ? route('admin.products.media', [$product, 'gallery', $loop->index])
                                        : asset($galleryImage));
                            @endphp
                            <div class="admin-gallery-card" data-existing-gallery-item>
                                <input type="hidden" name="gallery_existing[]" value="{{ $galleryImage }}">
                                <img src="{{ $galleryUrl }}" alt="{{ old('name', $product->name ?: 'Producto') }}" class="admin-gallery-thumb">
                                <button type="button" class="admin-gallery-remove" data-remove-gallery-item>Quitar</button>
                            </div>
                        @empty
                            <div class="admin-empty" id="gallery-empty-state">
                                Aun no se han agregado imagenes alternas.
                            </div>
                        @endforelse
                    </div>

                    <div class="admin-gallery-manager admin-gallery-manager--pending" id="gallery-preview-list"></div>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const isCreate = @json($isCreate);
            const storeSkuMeta = @json($storeSkuMeta ?? []);
            const nameInput = document.getElementById('name');
            const skuInput = document.getElementById('sku');
            const storeSelect = document.getElementById('store_id');
            const galleryInput = document.getElementById('gallery');
            const existingList = document.getElementById('gallery-existing-list');
            const previewList = document.getElementById('gallery-preview-list');
            const emptyState = document.getElementById('gallery-empty-state');
            const moneyInputs = document.querySelectorAll('.js-money-display');

            function formatMoneyDigits(value) {
                if (!value) {
                    return '';
                }

                return new Intl.NumberFormat('es-CO', {
                    maximumFractionDigits: 0,
                }).format(Number(value));
            }

            function syncMoneyInput(input) {
                const target = document.getElementById(input.dataset.target);

                if (!target) {
                    return;
                }

                const digits = input.value.replace(/\D+/g, '');
                target.value = digits;
                input.value = formatMoneyDigits(digits);
            }

            function syncSku() {
                if (!isCreate) {
                    return;
                }

                const selectedStoreId = storeSelect.value;
                const productName = nameInput.value.trim();
                const skuMeta = storeSkuMeta[selectedStoreId];

                if (!selectedStoreId || !productName || !skuMeta?.next) {
                    skuInput.value = '';
                    return;
                }

                skuInput.value = skuMeta.next;
            }

            function syncEmptyState() {
                const hasExisting = existingList.querySelector('[data-existing-gallery-item]');
                if (!emptyState) {
                    return;
                }

                emptyState.style.display = hasExisting ? 'none' : '';
            }

            existingList.addEventListener('click', (event) => {
                const removeButton = event.target.closest('[data-remove-gallery-item]');

                if (!removeButton) {
                    return;
                }

                const item = removeButton.closest('[data-existing-gallery-item]');
                item?.remove();
                syncEmptyState();
            });

            galleryInput.addEventListener('change', () => {
                previewList.innerHTML = '';

                Array.from(galleryInput.files || []).forEach((file) => {
                    const card = document.createElement('div');
                    card.className = 'admin-gallery-card';

                    const image = document.createElement('img');
                    image.className = 'admin-gallery-thumb';
                    image.alt = file.name;
                    image.src = URL.createObjectURL(file);

                    const caption = document.createElement('span');
                    caption.className = 'admin-gallery-caption';
                    caption.textContent = file.name;

                    card.appendChild(image);
                    card.appendChild(caption);
                    previewList.appendChild(card);
                });
            });

            moneyInputs.forEach((input) => {
                input.addEventListener('input', () => syncMoneyInput(input));
                input.addEventListener('blur', () => syncMoneyInput(input));
                syncMoneyInput(input);
            });

            if (isCreate) {
                nameInput.addEventListener('input', syncSku);
                storeSelect.addEventListener('change', syncSku);
                syncSku();
            }

            syncEmptyState();
        });
    </script>
@endpush
