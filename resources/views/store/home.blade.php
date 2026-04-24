@extends('layouts.store')
@push('styles')
<style>
    .shopingo-home {
        padding: 2.4rem 0 6rem;
        background: linear-gradient(180deg, #FFFFFF 0%, #FFFFFF 100%);
    }
    .shopingo-home .container {
        position: relative;
    }
    .shopingo-hero {
        display: grid;
        grid-template-columns: minmax(0, 2.4fr) minmax(28rem, 1fr);
        gap: 2rem;
        align-items: stretch;
        margin-bottom: 2.8rem;
    }
    .shopingo-panel,
    .shopingo-banner,
    .shopingo-mini-banner,
    .shopingo-info-card,
    .shopingo-category-card,
    .shopingo-product-shell {
        border: 1px solid #E7D4C3;
        border-radius: 22px;
        background: #FFFFFF;
        box-shadow: 0 14px 30px rgba(87, 43, 26, 0.08);
    }
    .shopingo-info-grid,
    .shopingo-category-section,
    .shopingo-featured-section {
        padding: 2.6rem;
        border-radius: 3rem;
        background: #FBF1E1;
    }
    .shopingo-section__title {
        margin: 0 0 1.4rem;
        font-size: 1.8rem;
        font-weight: 700;
        font-family: "Manrope", sans-serif;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        color: #572B1A;
    }
    .shopingo-banner {
        position: relative;
        overflow: hidden;
        min-height: 52rem;
        background: #FFFFFF;
    }
    .shopingo-banner .swiper {
        height: 100%;
        min-height: 52rem;
    }
    .shopingo-banner__slide {
        position: relative;
        min-height: 52rem;
        overflow: hidden;
    }
    .shopingo-banner__slide::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(90deg, rgba(255, 255, 255, 0.94) 0%, rgba(255, 255, 255, 0.88) 34%, rgba(251, 241, 225, 0.38) 58%, rgba(87, 43, 26, 0.18) 100%);
        z-index: 1;
    }
    .shopingo-banner__image {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
    .shopingo-banner__content {
        position: absolute;
        z-index: 2;
        inset: 0 auto 0 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 1.2rem;
        max-width: min(65%, 52rem);
        padding: 2.6rem 4rem;
    }
    .shopingo-banner__eyebrow {
        margin-bottom: 1rem;
        color: #572B1A;
        font-size: 1.35rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }
    .shopingo-banner__title {
        margin: 0;
        font-family: "Manrope", sans-serif;
        font-size: clamp(3.2rem, 3.6vw, 4.8rem);
        line-height: 1.05;
        color: #572B1A;
        text-wrap: balance;
    }
    .shopingo-banner__text {
        margin: 0;
        max-width: 38rem;
        color: #3A241C;
        font-family: "Cormorant Garamond", serif;
        font-size: 1.8rem;
        line-height: 1.55;
    }
    .shopingo-banner__actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .shopingo-banner__nav {
        position: absolute;
        right: 2.4rem;
        bottom: 2.4rem;
        z-index: 3;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .shopingo-banner__arrow {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 4.8rem;
        height: 4.8rem;
        border: 1px solid rgba(231, 212, 195, 0.95);
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.92);
        color: #572B1A;
        cursor: pointer;
        transition: background-color .2s ease, color .2s ease, border-color .2s ease;
    }
    .shopingo-banner__arrow:hover {
        background: #D05F32;
        border-color: #D05F32;
        color: #FFFFFF;
    }
    .shopingo-banner__pagination {
        position: absolute;
        left: 4rem !important;
        bottom: 2.8rem !important;
        z-index: 3;
        display: flex;
        align-items: center;
        gap: .8rem;
    }
    .shopingo-banner__pagination .swiper-pagination-bullet {
        width: 1rem;
        height: 1rem;
        margin: 0 !important;
        background: rgba(87, 43, 26, 0.22);
        opacity: 1;
    }
    .shopingo-banner__pagination .swiper-pagination-bullet-active {
        width: 3.2rem;
        border-radius: 999px;
        background: #D05F32;
    }
    .shopingo-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 5rem;
        padding: 0 2rem;
        border-radius: 999px;
        font-size: 1.35rem;
        font-weight: 700;
    }
    .shopingo-btn--dark {
        background: #D05F32;
        color: #FFFFFF;
    }
    .shopingo-btn--dark:hover {
        background: #AB4D29;
        color: #FFFFFF;
    }
    .shopingo-btn--light {
        border: 1px solid #E7D4C3;
        background: #FFFFFF;
        color: #572B1A;
    }
    .shopingo-hero__aside {
        display: grid;
        gap: 2rem;
    }
    .shopingo-mini-banner {
        position: relative;
        overflow: hidden;
        min-height: 21rem;
        padding: 2.4rem;
        isolation: isolate;
    }
    .shopingo-mini-banner::before {
        content: "";
        position: absolute;
        inset: auto -3rem -4rem auto;
        width: 16rem;
        height: 16rem;
        border-radius: 50%;
        opacity: 0.35;
        z-index: -1;
    }
    .shopingo-mini-banner--rose {
        background: linear-gradient(135deg, #FBF1E1 0%, #FFFFFF 100%);
    }
    .shopingo-mini-banner--rose::before {
        background: #D05F32;
    }
    .shopingo-mini-banner--blue {
        background: linear-gradient(135deg, #FFFFFF 0%, #FBF1E1 100%);
    }
    .shopingo-mini-banner--blue::before {
        background: #EBA468;
    }
    .shopingo-mini-banner__kicker {
        margin-bottom: 0.8rem;
        color: #572B1A;
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }
    .shopingo-mini-banner__title {
        margin: 0 0 1rem;
        font-size: 2.5rem;
        line-height: 1.05;
        font-weight: 700;
        color: #572B1A;
        font-family: "Manrope", sans-serif;
    }
    .shopingo-mini-banner__text {
        margin: 0 0 1.4rem;
        max-width: 22rem;
        color: #3A241C;
    }
    .shopingo-info-grid,
    .shopingo-category-grid,
    .shopingo-product-grid {
        display: grid;
        gap: 2rem;
    }
    .shopingo-info-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        margin-bottom: 2.8rem;
    }
    .shopingo-info-card {
        display: flex;
        align-items: center;
        gap: 1.6rem;
        padding: 2rem;
    }
    .shopingo-info-card__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 5.4rem;
        height: 5.4rem;
        border-radius: 50%;
        background: #FBF1E1;
        color: #D05F32;
        font-size: 2.2rem;
    }
    .shopingo-info-card__title {
        margin: 0 0 0.4rem;
        font-size: 1.5rem;
        font-weight: 700;
    }
    .shopingo-info-card__text {
        margin: 0;
        color: #3A241C;
        font-size: 1.3rem;
    }
    .shopingo-section {
        margin-bottom: 3.4rem;
    }
    .shopingo-section__head {
        display: flex;
        align-items: center;
        gap: 1.6rem;
        margin-bottom: 1.8rem;
    }
    .shopingo-section__line {
        flex: 1;
        height: 1px;
        background: #D05F32;
    }
    .shopingo-category-grid {
        grid-template-columns: repeat(6, minmax(0, 1fr));
    }
    .shopingo-category-card {
        overflow: hidden;
        text-align: center;
    }
    .shopingo-category-card__image {
        height: 16rem;
        width: 100%;
        object-fit: cover;
    }
    .shopingo-category-card__body {
        padding: 1.4rem 1.2rem 1.6rem;
    }
    .shopingo-category-card__name {
        margin: 0 0 0.6rem;
        font-size: 1.4rem;
        font-weight: 700;
    }
    .shopingo-category-card__meta {
        margin: 0;
        color: #3A241C;
        font-size: 1.2rem;
    }
    .shopingo-product-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
    .shopingo-product-shell {
        padding: 0;
    }
    .shopingo-product-shell .product-wrap {
        margin-bottom: 0;
    }
    .shopingo-product-shell .product {
        border: 1px solid #E7D4C3;
        box-shadow: none;
    }
    @media (max-width: 1279px) {
        .shopingo-hero {
            grid-template-columns: 1fr;
        }
        .shopingo-hero__aside {
            grid-template-columns: 1fr 1fr;
        }
        .shopingo-category-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    @media (max-width: 991px) {
        .shopingo-info-grid,
        .shopingo-product-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .shopingo-banner__content {
            max-width: 100%;
            padding-right: 18rem;
        }
    }
    @media (max-width: 767px) {
        .shopingo-home {
            padding-top: 1.6rem;
        }
        .shopingo-info-grid,
        .shopingo-category-section,
        .shopingo-featured-section {
            padding: 1.6rem;
            border-radius: 2.2rem;
        }
        .shopingo-banner {
            min-height: 34rem;
        }
        .shopingo-banner .swiper,
        .shopingo-banner__slide {
            min-height: 34rem;
        }
        .shopingo-banner__slide::before {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92) 0%, rgba(255, 255, 255, 0.82) 44%, rgba(251, 241, 225, 0.66) 100%);
        }
        .shopingo-banner__content {
            position: relative;
            max-width: 100%;
            gap: 1rem;
            padding: 2.4rem 2.4rem 7rem;
        }
        .shopingo-banner__title {
            max-width: 100%;
            font-size: clamp(2.8rem, 9vw, 4rem);
        }
        .shopingo-banner__nav {
            right: 1.6rem;
            bottom: 1.4rem;
        }
        .shopingo-banner__pagination {
            left: 2.4rem !important;
            bottom: 2rem !important;
        }
        .shopingo-hero__aside,
        .shopingo-info-grid,
        .shopingo-category-grid,
        .shopingo-product-grid {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 479px) {
        .shopingo-home {
            padding-bottom: 4rem;
        }
        .shopingo-section__head {
            align-items: flex-start;
            flex-direction: column;
            gap: .8rem;
        }
        .shopingo-section__line {
            width: 100%;
        }
        .shopingo-banner__content {
            padding: 1.8rem 1.6rem 7rem;
        }
        .shopingo-banner__actions,
        .shopingo-btn {
            width: 100%;
        }
        .shopingo-hero__aside {
            grid-template-columns: 1fr;
        }
        .shopingo-info-card {
            align-items: flex-start;
            flex-direction: column;
        }
        .shopingo-category-card__image {
            height: 13rem;
        }
        .shopingo-banner__nav {
            right: 1.2rem;
            gap: .6rem;
        }
        .shopingo-banner__arrow {
            width: 4rem;
            height: 4rem;
        }
    }
</style>
@endpush
@section('content')
@php
    $fallbackCategoryImages = [
        'electronica' => 'wolmart/assets/images/demos/demo1/categories/1-1.jpg',
        'moda' => 'wolmart/assets/images/demos/demo1/categories/1-2.jpg',
        'hogar' => 'wolmart/assets/images/demos/demo1/categories/2-1.jpg',
        'deportes' => 'wolmart/assets/images/demos/demo1/categories/2-2.jpg',
        'belleza' => 'wolmart/assets/images/demos/demo1/categories/2-3.jpg',
        'accesorios' => 'wolmart/assets/images/demos/demo1/categories/2-4.jpg',
        'default' => 'wolmart/assets/images/demos/demo1/categories/2-5.jpg',
    ];
    $heroSlides = [
        [
            'image' => 'wolmart/assets/images/demos/demo9/banner/1-1.jpg',
            'eyebrow' => 'Campana de temporada',
            'title' => 'Renueva tu carrito con selecciones que convierten mejor',
            'text' => 'Descubre campañas visuales con productos listos para destacar, comprar y regalar en una sola visita.',
            'url' => route('store.shop'),
        ],
        [
            'image' => 'wolmart/assets/images/demos/demo9/banner/2-1.jpg',
            'eyebrow' => 'Promociones activas',
            'title' => 'Ofertas cuidadas para impulsar compras mas rapidas',
            'text' => 'Aprovecha lanzamientos, descuentos y colecciones curadas con una presentación más aspiracional.',
            'url' => route('store.shop'),
        ],
        [
            'image' => 'wolmart/assets/images/vendor/element/banner/3.jpg',
            'eyebrow' => 'Compra inspirada',
            'title' => 'Campanas visuales para mover favoritos hacia la venta',
            'text' => 'Conecta inspiración y acción con banners que llevan directo al catálogo y al checkout.',
            'url' => route('store.shop'),
        ],
    ];
    $infoItems = [
        ['icon' => 'w-icon-truck', 'title' => 'Envíos y devoluciones', 'text' => 'Procesos más claros para comprar con tranquilidad.'],
        ['icon' => 'w-icon-money', 'title' => 'Pago seguro', 'text' => 'Checkout respaldado por ePayco y seguimiento del pedido.'],
        ['icon' => 'w-icon-service', 'title' => 'Soporte 24/7', 'text' => 'Acompañamiento cercano para clientes y operación interna.'],
    ];
@endphp
<main class="main shopingo-home">
    <div class="container">
        <section class="shopingo-hero">
            <div class="shopingo-banner">
                <div class="swiper shopingo-banner-slider">
                    <div class="swiper-wrapper">
                        @foreach($heroSlides as $slide)
                            <article class="swiper-slide shopingo-banner__slide">
                                <img src="{{ asset($slide['image']) }}" alt="{{ $slide['title'] }}" class="shopingo-banner__image">
                                <div class="shopingo-banner__content">
                                    <div class="shopingo-banner__eyebrow">{{ $slide['eyebrow'] }}</div>
                                    <h1 class="shopingo-banner__title">{{ $slide['title'] }}</h1>
                                    <p class="shopingo-banner__text">{{ $slide['text'] }}</p>
                                    <div class="shopingo-banner__actions">
                                        <a href="{{ $slide['url'] }}" class="shopingo-btn shopingo-btn--dark">Comprar</a>
                                        <a href="{{ route('store.stores.index') }}" class="shopingo-btn shopingo-btn--light">Ver tiendas</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="shopingo-banner__pagination"></div>
                    <div class="shopingo-banner__nav">
                        <button type="button" class="shopingo-banner__arrow shopingo-banner__arrow--prev" aria-label="Campaña anterior">
                            <i class="w-icon-angle-left"></i>
                        </button>
                        <button type="button" class="shopingo-banner__arrow shopingo-banner__arrow--next" aria-label="Siguiente campaña">
                            <i class="w-icon-angle-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="shopingo-hero__aside">
                <article class="shopingo-mini-banner shopingo-mini-banner--rose">
                    <div class="shopingo-mini-banner__kicker">Colección</div>
                    <h2 class="shopingo-mini-banner__title">Productos destacados</h2>
                    <p class="shopingo-mini-banner__text">Acceso rápido a los artículos que ya marcaste como favoritos del catálogo.</p>
                    <a href="{{ route('store.shop') }}">Explorar</a>
                </article>
                <article class="shopingo-mini-banner shopingo-mini-banner--blue">
                    <div class="shopingo-mini-banner__kicker">Compra segura</div>
                    <h2 class="shopingo-mini-banner__title">Checkout listo para cerrar la venta</h2>
                    <p class="shopingo-mini-banner__text">Carrito, confirmación y pago conectados en un flujo continuo.</p>
                    <a href="{{ route('store.checkout.index') }}">Ir al checkout</a>
                </article>
            </div>
        </section>

        <section class="shopingo-info-grid">
            @foreach($infoItems as $item)
                <article class="shopingo-info-card">
                    <span class="shopingo-info-card__icon"><i class="{{ $item['icon'] }}"></i></span>
                    <div>
                        <h3 class="shopingo-info-card__title">{{ $item['title'] }}</h3>
                        <p class="shopingo-info-card__text">{{ $item['text'] }}</p>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="shopingo-section">
            <div class="shopingo-section__head">
                <div class="shopingo-section__line"></div>
                <h2 class="shopingo-section__title">Categorías destacadas</h2>
                <div class="shopingo-section__line"></div>
            </div>
            <div class="shopingo-category-grid">
                @foreach($categories as $category)
                    @php
                        $categorySlug = $category->slug ?: \Illuminate\Support\Str::slug($category->name);
                        $categoryImagePath = $category->image;

                        if (! $categoryImagePath || ! file_exists(public_path($categoryImagePath))) {
                            $categoryImagePath = $fallbackCategoryImages[$categorySlug] ?? $fallbackCategoryImages['default'];
                        }
                    @endphp
                    <a href="{{ route('store.shop', ['category' => $category->slug]) }}" class="shopingo-category-card">
                        <img src="{{ asset($categoryImagePath) }}" alt="{{ $category->name }}" class="shopingo-category-card__image">
                        <div class="shopingo-category-card__body">
                            <h3 class="shopingo-category-card__name">{{ $category->name }}</h3>
                            <p class="shopingo-category-card__meta">{{ $category->products_count }} productos</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="shopingo-section">
            <div class="shopingo-section__head">
                <div class="shopingo-section__line"></div>
                <h2 class="shopingo-section__title">Productos destacados</h2>
                <div class="shopingo-section__line"></div>
            </div>
            <div class="shopingo-product-grid product-market-grid">
                @foreach($featuredProducts as $product)
                    <div class="shopingo-product-shell">
                        @include('store.partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </section>

        <section class="shopingo-section">
            <div class="shopingo-section__head">
                <div class="shopingo-section__line"></div>
                <h2 class="shopingo-section__title">Últimos productos</h2>
                <div class="shopingo-section__line"></div>
            </div>
            <div class="shopingo-product-grid product-market-grid">
                @foreach($latestProducts as $product)
                    <div class="shopingo-product-shell">
                        @include('store.partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</main>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var bannerSlider = document.querySelector('.shopingo-banner-slider');

        if (!bannerSlider || typeof Swiper === 'undefined') {
            return;
        }

        new Swiper(bannerSlider, {
            loop: true,
            speed: 700,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false
            },
            pagination: {
                el: '.shopingo-banner__pagination',
                clickable: true
            },
            navigation: {
                nextEl: '.shopingo-banner__arrow--next',
                prevEl: '.shopingo-banner__arrow--prev'
            }
        });
    });
</script>
@endpush
