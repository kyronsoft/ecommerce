@extends('layouts.store')

@section('content')
@php
    $isWishlisted = in_array($product->id, $wishlistProductIds ?? [], true);
    $galleryImages = collect([$product->image_url])
        ->merge(collect($product->gallery_urls ?? []))
        ->filter()
        ->unique()
        ->values();
    $primaryImage = $galleryImages->first();
    $hasComparePrice = filled($product->compare_price) && (float) $product->compare_price > (float) $product->price;
    $discountPercentage = $hasComparePrice
        ? (int) round((1 - ((float) $product->price / (float) $product->compare_price)) * 100)
        : null;
@endphp

@push('styles')
<style>
    .product-detail-page {
        padding: 2.8rem 0 6rem;
        background: linear-gradient(180deg, #ffffff 0%, #fbf1e1 100%);
    }
    .product-detail-breadcrumb {
        margin-bottom: 1.8rem;
        color: #3A241C;
        font-size: 1.3rem;
    }
    .product-detail-breadcrumb a {
        color: #572B1A;
    }
    .product-detail-shell {
        display: grid;
        grid-template-columns: 7.2rem minmax(0, 1.15fr) minmax(34rem, 0.95fr);
        gap: 2rem;
        align-items: start;
    }
    .product-detail-gallery-rail {
        display: grid;
        gap: 1rem;
    }
    .product-detail-thumb {
        display: block;
        width: 100%;
        padding: 0;
        border: 1px solid #E7D4C3;
        border-radius: 18px;
        overflow: hidden;
        background: #FFFFFF;
        cursor: pointer;
        transition: border-color .2s ease, box-shadow .2s ease;
    }
    .product-detail-thumb.is-active,
    .product-detail-thumb:hover {
        border-color: #D05F32;
        box-shadow: 0 10px 18px rgba(208, 95, 50, 0.16);
    }
    .product-detail-thumb img {
        display: block;
        width: 100%;
        height: 7.2rem;
        object-fit: cover;
    }
    .product-detail-media-card,
    .product-detail-summary {
        border: 1px solid #E7D4C3;
        border-radius: 2.8rem;
        background: #FFFFFF;
        box-shadow: 0 18px 34px rgba(87, 43, 26, 0.08);
    }
    .product-detail-media-card {
        overflow: hidden;
        padding: 1.6rem;
    }
    .product-detail-main-image {
        position: relative;
        border-radius: 2.2rem;
        overflow: hidden;
        background: #FBF1E1;
        aspect-ratio: 1 / 1;
    }
    .product-detail-main-image img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .product-detail-main-badge {
        position: absolute;
        top: 1.6rem;
        left: 1.6rem;
        padding: .8rem 1.2rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.92);
        color: #572B1A;
        font-size: 1.15rem;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .product-detail-summary {
        padding: 2.4rem;
    }
    .product-detail-category {
        display: inline-flex;
        align-items: center;
        gap: .8rem;
        margin-bottom: 1rem;
        color: #572B1A;
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
    .product-detail-title {
        margin: 0 0 1.2rem;
        color: #3A241C;
        font-family: "Manrope", sans-serif;
        font-size: clamp(2.8rem, 3.4vw, 4rem);
        line-height: 1.08;
    }
    .product-detail-short {
        margin: 0 0 1.4rem;
        color: #3A241C;
        font-family: "Cormorant Garamond", serif;
        font-size: 2rem;
        line-height: 1.45;
    }
    .product-detail-rating {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.4rem;
        color: #572B1A;
        font-size: 1.35rem;
    }
    .product-detail-stars {
        color: #D05F32;
        letter-spacing: .12em;
    }
    .product-detail-highlight {
        display: inline-flex;
        align-items: center;
        padding: .8rem 1.2rem;
        border-radius: 999px;
        background: #572B1A;
        color: #FBF1E1;
        font-size: 1.2rem;
        font-weight: 700;
    }
    .product-detail-price-row {
        display: flex;
        align-items: end;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.8rem;
    }
    .product-detail-price-old {
        color: rgba(58, 36, 28, 0.55);
        font-size: 2rem;
        font-weight: 700;
        text-decoration: line-through;
    }
    .product-detail-price-current {
        color: #D05F32;
        font-family: "Manrope", sans-serif;
        font-size: 4.2rem;
        font-weight: 800;
        line-height: 1;
    }
    .product-detail-discount {
        display: inline-flex;
        align-items: center;
        padding: .7rem 1rem;
        border-radius: 999px;
        background: #FBF1E1;
        color: #D05F32;
        font-size: 1.25rem;
        font-weight: 800;
    }
    .product-detail-offer {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1.6rem;
        padding: 1.4rem 1.6rem;
        border-radius: 2rem;
        background: linear-gradient(90deg, #D05F32 0%, #AB4D29 100%);
        color: #FFFFFF;
    }
    .product-detail-offer strong {
        font-size: 1.8rem;
        font-family: "Manrope", sans-serif;
    }
    .product-detail-offer span {
        font-size: 1.35rem;
        font-weight: 600;
    }
    .product-detail-purchase {
        padding: 1.8rem;
        border: 1px solid #E7D4C3;
        border-radius: 2.2rem;
        background: #FFFFFF;
    }
    .product-detail-option {
        display: grid;
        grid-template-columns: 6.8rem 1fr;
        gap: 1rem;
        align-items: center;
        margin-bottom: 1.6rem;
    }
    .product-detail-option img {
        width: 6.8rem;
        height: 6.8rem;
        object-fit: cover;
        border-radius: 1.6rem;
        border: 1px solid #E7D4C3;
    }
    .product-detail-option-label {
        margin: 0 0 .4rem;
        color: #572B1A;
        font-size: 1.25rem;
        font-weight: 700;
    }
    .product-detail-option-value {
        margin: 0;
        color: #3A241C;
        font-size: 1.45rem;
        font-weight: 600;
    }
    .product-detail-form-row {
        display: grid;
        grid-template-columns: 10rem 1fr;
        gap: 1.2rem;
        align-items: center;
    }
    .product-detail-qty input {
        width: 100%;
        min-height: 5.2rem;
        border: 1px solid #E7D4C3;
        border-radius: 1.6rem;
        background: #FFFFFF;
        color: #3A241C;
        font-size: 1.7rem;
        font-weight: 700;
        text-align: center;
    }
    .product-detail-cart-btn {
        width: 100%;
        min-height: 5.4rem;
        border: 0;
        border-radius: 999px;
        background: #D05F32;
        color: #FFFFFF;
        font-size: 1.7rem;
        font-weight: 800;
        box-shadow: 0 18px 28px rgba(208, 95, 50, 0.18);
    }
    .product-detail-cart-btn:hover {
        background: #AB4D29;
    }
    .product-detail-shipping {
        margin-top: 1.4rem;
        color: #D05F32;
        font-size: 1.35rem;
        font-weight: 700;
    }
    .product-detail-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1.8rem;
    }
    .product-detail-wishlist-btn {
        min-height: 4.8rem;
        padding: 0 1.6rem;
        border-radius: 999px;
    }
    .product-detail-benefits {
        display: grid;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #E7D4C3;
    }
    .product-detail-benefit {
        display: flex;
        gap: 1rem;
        align-items: start;
        color: #3A241C;
        font-size: 1.4rem;
        line-height: 1.6;
    }
    .product-detail-benefit i {
        color: #D05F32;
        margin-top: .2rem;
    }
    .product-detail-meta {
        display: grid;
        gap: .8rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #E7D4C3;
        color: #3A241C;
        font-size: 1.35rem;
    }
    .product-detail-description {
        margin-top: 2.2rem;
        padding: 2.2rem;
        border: 1px solid #E7D4C3;
        border-radius: 2.4rem;
        background: rgba(255, 255, 255, 0.76);
    }
    .product-detail-description h2 {
        margin: 0 0 1rem;
        color: #572B1A;
        font-family: "Manrope", sans-serif;
        font-size: 2.2rem;
    }
    .product-detail-description p {
        margin: 0;
        color: #3A241C;
        font-size: 1.45rem;
        line-height: 1.8;
    }
    .vendor-product-section {
        margin-top: 4rem;
    }
    @media (max-width: 1279px) {
        .product-detail-shell {
            grid-template-columns: 7.2rem minmax(0, 1fr);
        }
        .product-detail-summary {
            grid-column: 1 / -1;
        }
    }
    @media (max-width: 767px) {
        .product-detail-page {
            padding: 2rem 0 4rem;
        }
        .product-detail-shell {
            grid-template-columns: 1fr;
        }
        .product-detail-gallery-rail {
            order: 2;
            grid-template-columns: repeat(auto-fit, minmax(6.4rem, 1fr));
        }
        .product-detail-summary {
            order: 3;
        }
        .product-detail-form-row,
        .product-detail-offer {
            grid-template-columns: 1fr;
        }
        .product-detail-price-current {
            font-size: 3.4rem;
        }
        .product-detail-option {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 479px) {
        .product-detail-media-card,
        .product-detail-summary,
        .product-detail-description {
            border-radius: 2rem;
        }
        .product-detail-media-card,
        .product-detail-summary,
        .product-detail-description {
            padding-left: 1.4rem;
            padding-right: 1.4rem;
        }
        .product-detail-title {
            font-size: 2.4rem;
        }
        .product-detail-short {
            font-size: 1.7rem;
        }
        .product-detail-actions > * {
            width: 100%;
        }
        .product-detail-wishlist-btn {
            width: 100%;
        }
        .product-detail-main-badge,
        .product-detail-highlight,
        .product-detail-discount {
            font-size: 1.1rem;
        }
    }
</style>
@endpush

<main class="main product-detail-page">
    <div class="container">
        <nav class="product-detail-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('store.home') }}">Inicio</a>
            <span>›</span>
            <a href="{{ route('store.shop') }}">Catálogo</a>
            <span>›</span>
            <a href="{{ route('store.shop', ['category' => $product->category->slug ?? null]) }}">{{ $product->category->name ?? 'General' }}</a>
            <span>›</span>
            <span>{{ $product->name }}</span>
        </nav>

        <section class="product-detail-shell">
            <aside class="product-detail-gallery-rail">
                @foreach($galleryImages as $image)
                    <button
                        type="button"
                        class="product-detail-thumb {{ $loop->first ? 'is-active' : '' }}"
                        data-product-thumb
                        data-image="{{ $image }}"
                        aria-label="Ver imagen {{ $loop->iteration }} de {{ $product->name }}"
                    >
                        <img src="{{ $image }}" alt="{{ $product->name }}">
                    </button>
                @endforeach
            </aside>

            <div class="product-detail-media-card">
                <figure class="product-detail-main-image">
                    @if($product->is_featured)
                        <span class="product-detail-main-badge">Top recomendado</span>
                    @endif
                    <img src="{{ $primaryImage }}" alt="{{ $product->name }}" id="product-detail-main-image">
                </figure>
            </div>

            <div class="product-detail-summary">
                <div class="product-detail-category">
                    <span>{{ $product->category->name ?? 'General' }}</span>
                </div>

                <h1 class="product-detail-title">{{ $product->name }}</h1>

                @if(filled($product->short_description))
                    <p class="product-detail-short">{{ $product->short_description }}</p>
                @endif

                <div class="product-detail-rating">
                    <span class="product-detail-stars">★★★★★</span>
                    <span>4.7</span>
                    <span>Producto destacado del catálogo</span>
                    <span class="product-detail-highlight">Compra segura</span>
                </div>

                <div class="product-detail-price-row">
                    @if($hasComparePrice)
                        <span class="product-detail-price-old">${{ number_format($product->compare_price, 0, ',', '.') }}</span>
                    @endif
                    <span class="product-detail-price-current">${{ number_format($product->price, 0, ',', '.') }}</span>
                    @if($discountPercentage)
                        <span class="product-detail-discount">{{ $discountPercentage }}% de descuento</span>
                    @endif
                </div>

                <div class="product-detail-offer">
                    <strong>Oferta activa</strong>
                    <span>Envío nacional y checkout simple para cerrar la compra en pocos pasos.</span>
                    <span>{{ $product->stock > 0 ? 'Stock disponible' : 'Últimas unidades' }}</span>
                </div>

                <div class="product-detail-purchase">
                    <div class="product-detail-option">
                        <img src="{{ $primaryImage }}" alt="{{ $product->name }}">
                        <div>
                            <p class="product-detail-option-label">Selección actual</p>
                            <p class="product-detail-option-value">{{ $product->name }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('store.cart.add', $product) }}">
                        @csrf
                        <div class="product-detail-form-row">
                            <div class="product-detail-qty">
                                <input class="form-control" type="number" min="1" max="99" name="quantity" value="1" aria-label="Cantidad">
                            </div>
                            <button class="product-detail-cart-btn">Añadir al carrito</button>
                        </div>
                    </form>

                    <p class="product-detail-shipping">Entrega estimada y seguimiento disponible después de la compra.</p>
                </div>

                <div class="product-detail-actions">
                    @if($isWishlisted)
                        <form method="POST" action="{{ route('store.wishlist.remove', $product) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline product-detail-wishlist-btn">Quitar de favoritos</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('store.wishlist.add', $product) }}">
                            @csrf
                            <button class="btn btn-outline product-detail-wishlist-btn">Guardar en favoritos</button>
                        </form>
                    @endif
                </div>

                <div class="product-detail-benefits">
                    <div class="product-detail-benefit">
                        <i class="w-icon-truck"></i>
                        <span>Envío gestionado con información clara de despacho y estado del pedido.</span>
                    </div>
                    <div class="product-detail-benefit">
                        <i class="w-icon-secure"></i>
                        <span>Pagos protegidos y experiencia de compra diseñada para transmitir confianza.</span>
                    </div>
                    <div class="product-detail-benefit">
                        <i class="w-icon-service"></i>
                        <span>Soporte cercano si necesitas ayuda antes o después de tu compra.</span>
                    </div>
                </div>

                <div class="product-detail-meta">
                    <div><strong>SKU:</strong> {{ $product->sku ?: 'No disponible' }}</div>
                    <div><strong>Categoría:</strong> {{ $product->category->name ?? 'General' }}</div>
                    <div><strong>Disponibilidad:</strong> {{ $product->stock > 0 ? $product->stock.' unidades disponibles' : 'Agotado' }}</div>
                </div>
            </div>
        </section>

        <section class="product-detail-description">
            <h2>Descripción del producto</h2>
            <p>{{ $product->description ?: 'Este producto forma parte del catálogo activo de la tienda y está listo para compra en línea.' }}</p>
        </section>

        <section class="vendor-product-section">
            <h2 class="title title-center mb-5">Productos relacionados</h2>
            <div class="product-market-grid">
                @foreach($relatedProducts as $product)
                    @include('store.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    </div>
</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var mainImage = document.getElementById('product-detail-main-image');
        var thumbnails = document.querySelectorAll('[data-product-thumb]');

        if (!mainImage || !thumbnails.length) {
            return;
        }

        thumbnails.forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                var nextImage = thumb.getAttribute('data-image');

                if (!nextImage) {
                    return;
                }

                mainImage.src = nextImage;

                thumbnails.forEach(function (item) {
                    item.classList.remove('is-active');
                });

                thumb.classList.add('is-active');
            });
        });
    });
</script>
@endpush
