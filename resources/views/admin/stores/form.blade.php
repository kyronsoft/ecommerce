@extends('layouts.admin', [
    'breadcrumb' => $store->exists ? 'Editar tienda' : 'Nueva tienda',
    'pageTitle' => $store->exists ? 'Editar tienda' : 'Crear tienda',
    'pageDescription' => 'Completa la ficha del negocio para que aparezca correctamente en el listado público y tenga su propio perfil dentro del marketplace.',
])

@php
    $resolveStoreMedia = function (?string $path): ?string {
        if (blank($path)) {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $normalizedPath = ltrim($path, '/');

        if (\Illuminate\Support\Str::startsWith($normalizedPath, 'public/')) {
            $normalizedPath = \Illuminate\Support\Str::after($normalizedPath, 'public/');
        }

        if (\Illuminate\Support\Str::startsWith($normalizedPath, 'storage/')) {
            return asset($normalizedPath);
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($normalizedPath)) {
            return \Illuminate\Support\Facades\Storage::url($normalizedPath);
        }

        return asset($normalizedPath);
    };

    $logoUrl = $resolveStoreMedia($store->logo);
    $bannerUrl = $resolveStoreMedia($store->banner);
@endphp

@section('page_actions')
    <a href="{{ route('admin.stores.index') }}" class="admin-btn">Volver al listado</a>
    @if($store->exists)
        <a href="{{ route('admin.stores.show', $store) }}" class="admin-btn admin-btn--primary">Ver detalle</a>
    @endif
@endsection

@section('content')
    <form method="POST" action="{{ $store->exists ? route('admin.stores.update', $store) : route('admin.stores.store') }}" enctype="multipart/form-data">
        @csrf
        @if($store->exists)
            @method('PUT')
        @endif

        <div class="admin-grid-2">
            <section class="admin-panel">
                <div class="admin-section-head">
                    <div>
                        <h2>Datos del negocio</h2>
                        <p>Información principal para el perfil público de la tienda y su operación dentro del marketplace.</p>
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="name">Nombre de la tienda</label>
                        <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $store->name) }}" required>
                        @error('name')
                            <small class="admin-field-error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="admin-field">
                        <label for="owner_name">Responsable</label>
                        <input id="owner_name" type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror" value="{{ old('owner_name', $store->owner_name) }}" required>
                        @error('owner_name')
                            <small class="admin-field-error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="slug">Slug</label>
                        <input id="slug" type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $store->slug) }}">
                        @error('slug')
                            <small class="admin-field-error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="admin-field">
                        <label for="location">Ubicación</label>
                        <input id="location" type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $store->location) }}">
                        @error('location')
                            <small class="admin-field-error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="email">Correo</label>
                        <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $store->email) }}">
                        @error('email')
                            <small class="admin-field-error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="admin-field">
                        <label for="phone">Teléfono</label>
                        <input id="phone" type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $store->phone) }}">
                        @error('phone')
                            <small class="admin-field-error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="website">Sitio web</label>
                        <input id="website" type="url" name="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $store->website) }}">
                        @error('website')
                            <small class="admin-field-error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="admin-field">
                        <label for="whatsapp">WhatsApp</label>
                        <input id="whatsapp" type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $store->whatsapp) }}">
                        @error('whatsapp')
                            <small class="admin-field-error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="facebook_url">Facebook</label>
                        <input id="facebook_url" type="url" name="facebook_url" class="form-control @error('facebook_url') is-invalid @enderror" value="{{ old('facebook_url', $store->facebook_url) }}">
                        @error('facebook_url')
                            <small class="admin-field-error">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="admin-field">
                        <label for="instagram_url">Instagram</label>
                        <input id="instagram_url" type="url" name="instagram_url" class="form-control @error('instagram_url') is-invalid @enderror" value="{{ old('instagram_url', $store->instagram_url) }}">
                        @error('instagram_url')
                            <small class="admin-field-error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="admin-field">
                    <label for="short_description">Descripción corta</label>
                    <input id="short_description" type="text" name="short_description" class="form-control @error('short_description') is-invalid @enderror" value="{{ old('short_description', $store->short_description) }}">
                    @error('short_description')
                        <small class="admin-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="admin-field">
                    <label for="description">Descripción completa</label>
                    <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="6">{{ old('description', $store->description) }}</textarea>
                    @error('description')
                        <small class="admin-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="admin-field">
                    <label for="highlights">Highlights</label>
                    <textarea id="highlights" name="highlights" class="form-control @error('highlights') is-invalid @enderror" rows="4">{{ old('highlights', collect($store->highlights ?? [])->implode(', ')) }}</textarea>
                    <small>Separa los beneficios con coma o salto de línea.</small>
                    @error('highlights')
                        <small class="admin-field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="admin-field">
                    <label for="business_hours">Horario de atención</label>
                    <textarea id="business_hours" name="business_hours" class="form-control @error('business_hours') is-invalid @enderror" rows="4">{{ old('business_hours', $store->business_hours) }}</textarea>
                    @error('business_hours')
                        <small class="admin-field-error">{{ $message }}</small>
                    @enderror
                </div>
            </section>

            <section class="admin-panel">
                <div class="admin-section-head">
                    <div>
                        <h2>Imagen y estado</h2>
                        <p>Recursos gráficos y visibilidad pública dentro del marketplace.</p>
                    </div>
                </div>

                <div class="admin-field">
                    <label for="logo">Logo</label>
                    <input id="logo" type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/png,image/jpeg,image/webp,image/svg+xml">
                    <small>Sube el logo de la tienda. Se guardará en `storage/stores` usando el nombre del negocio.</small>
                    @error('logo')
                        <small class="admin-field-error">{{ $message }}</small>
                    @enderror

                    <div style="margin-top: 1rem;">
                        <small style="display:block; margin-bottom: .8rem; color: var(--admin-muted); font-weight: 700;">Logo actual</small>
                        @if($logoUrl)
                            <img
                                id="store-logo-preview"
                                src="{{ $logoUrl }}"
                                alt="Logo de tienda"
                                class="admin-image-preview"
                                style="width:12rem; height:12rem; object-fit:cover; border-radius:50%; margin-bottom: 0;">
                        @else
                            <div id="store-logo-placeholder" class="admin-empty" style="margin-bottom: 0;">
                                Aún no hay un logo guardado para esta tienda.
                            </div>
                            <img
                                id="store-logo-preview"
                                src=""
                                alt="Logo de tienda"
                                class="admin-image-preview"
                                style="display:none; width:12rem; height:12rem; object-fit:cover; border-radius:50%; margin-bottom: 0;">
                        @endif
                    </div>
                </div>

                <div class="admin-field">
                    <label for="banner">Banner</label>
                    <input id="banner" type="file" name="banner" class="form-control @error('banner') is-invalid @enderror" accept="image/png,image/jpeg,image/webp">
                    <small>Sube una imagen horizontal para el encabezado público y el listado de tiendas.</small>
                    @error('banner')
                        <small class="admin-field-error">{{ $message }}</small>
                    @enderror

                    <div style="margin-top: 1rem;">
                        <small style="display:block; margin-bottom: .8rem; color: var(--admin-muted); font-weight: 700;">Banner actual</small>
                        @if($bannerUrl)
                            <img
                                id="store-banner-preview"
                                src="{{ $bannerUrl }}"
                                alt="Banner de tienda"
                                class="admin-image-preview"
                                style="max-width:100%; margin-bottom: 0;">
                        @else
                            <div id="store-banner-placeholder" class="admin-empty" style="margin-bottom: 0;">
                                Aún no hay un banner guardado para esta tienda.
                            </div>
                            <img
                                id="store-banner-preview"
                                src=""
                                alt="Banner de tienda"
                                class="admin-image-preview"
                                style="display:none; max-width:100%; margin-bottom: 0;">
                        @endif
                    </div>
                </div>

                <div class="admin-checkbox-row" style="margin-bottom: 1.4rem;">
                    <label class="admin-checkbox">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $store->is_active))>
                        Tienda activa
                    </label>
                    <label class="admin-checkbox">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $store->is_featured))>
                        Marcar como destacada
                    </label>
                </div>

                <div class="admin-kv">
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Visibilidad</span>
                        <span class="admin-kv-value">{{ old('is_active', $store->is_active) ? 'Visible en el marketplace' : 'Oculta del marketplace' }}</span>
                    </div>
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Tipo</span>
                        <span class="admin-kv-value">{{ old('is_featured', $store->is_featured) ? 'Tienda destacada' : 'Tienda estándar' }}</span>
                    </div>
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Consejo</span>
                        <span class="admin-kv-value">Después de crear la tienda, asigna sus productos desde el CRUD de productos para que su catálogo público se vea completo.</span>
                    </div>
                </div>
            </section>
        </div>

        <div class="admin-actions" style="margin-top: 1.8rem;">
            <button type="submit" class="admin-btn admin-btn--primary">Guardar tienda</button>
            <a href="{{ route('admin.stores.index') }}" class="admin-btn admin-btn--secondary">Cancelar</a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const bindImagePreview = (inputId, imageId, placeholderId) => {
            const input = document.getElementById(inputId);
            const image = document.getElementById(imageId);
            const placeholder = placeholderId ? document.getElementById(placeholderId) : null;

            if (!input || !image) {
                return;
            }

            input.addEventListener('change', (event) => {
                const [file] = event.target.files || [];

                if (!file) {
                    return;
                }

                const reader = new FileReader();

                reader.onload = ({ target }) => {
                    image.src = target?.result || '';
                    image.style.display = 'block';

                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }
                };

                reader.readAsDataURL(file);
            });
        };

        bindImagePreview('logo', 'store-logo-preview', 'store-logo-placeholder');
        bindImagePreview('banner', 'store-banner-preview', 'store-banner-placeholder');
    </script>
@endpush
