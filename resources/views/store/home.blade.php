@extends('layouts.store')
@push('styles')
<style>
    .shopingo-home {
        padding: 2.4rem 0 6rem;
    }
    .shopingo-home .container {
        position: relative;
    }
    .shopingo-hero {
        display: grid;
        grid-template-columns: 1.25fr 3fr 1.1fr;
        gap: 2rem;
        align-items: stretch;
        margin-bottom: 2.8rem;
    }
    .shopingo-panel,
    .shopingo-banner,
    .shopingo-mini-banner,
    .shopingo-info-card,
    .shopingo-promo-card,
    .shopingo-category-card,
    .shopingo-product-shell {
        border: 1px solid #D09050;
        border-radius: 22px;
        background: #F8F0E0;
        box-shadow: 0 14px 30px rgba(208, 104, 64, 0.14);
    }
    .shopingo-sidebar {
        padding: 2.4rem 2rem;
    }
    .shopingo-sidebar__title,
    .shopingo-section__title {
        margin: 0 0 1.4rem;
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }
    .shopingo-sidebar__list {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .shopingo-sidebar__list li + li {
        border-top: 1px solid #D09050;
    }
    .shopingo-sidebar__list a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.2rem 0;
        color: #502818;
        font-weight: 600;
    }
    .shopingo-sidebar__list a:hover {
        color: #D06840;
    }
    .shopingo-banner {
        position: relative;
        overflow: hidden;
        min-height: 44rem;
        background:
            linear-gradient(120deg, rgba(248, 184, 120, 0.92) 0%, rgba(248, 240, 224, 0.82) 48%, rgba(208, 104, 64, 0.24) 100%),
            url('{{ asset('wolmart/assets/images/demos/demo1/sliders/slide-1.jpg') }}') center right / cover no-repeat;
    }
    .shopingo-banner__content {
        position: absolute;
        inset: 0 auto 0 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        max-width: 52%;
        padding: 4rem;
    }
    .shopingo-banner__eyebrow {
        margin-bottom: 1rem;
        color: #603018;
        font-size: 1.35rem;
        font-weight: 700;
        letter-spacing: 0.16em;
        text-transform: uppercase;
    }
    .shopingo-banner__title {
        margin: 0 0 1.4rem;
        font-family: "DM Serif Display", serif;
        font-size: clamp(3.4rem, 4vw, 5.6rem);
        line-height: 0.98;
        color: #502818;
    }
    .shopingo-banner__text {
        margin: 0 0 2.2rem;
        max-width: 38rem;
        color: #603018;
        font-size: 1.6rem;
        line-height: 1.7;
    }
    .shopingo-banner__actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
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
        background: #502818;
        color: #F8F0E0;
    }
    .shopingo-btn--light {
        border: 1px solid #D09050;
        background: rgba(248, 240, 224, 0.82);
        color: #502818;
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
        background: linear-gradient(135deg, #F8B878 0%, #F8F0E0 100%);
    }
    .shopingo-mini-banner--rose::before {
        background: #D06840;
    }
    .shopingo-mini-banner--blue {
        background: linear-gradient(135deg, #F8E8D0 0%, #F8F0E0 100%);
    }
    .shopingo-mini-banner--blue::before {
        background: #D09050;
    }
    .shopingo-mini-banner__kicker {
        margin-bottom: 0.8rem;
        color: #603018;
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
        color: #502818;
    }
    .shopingo-mini-banner__text {
        margin: 0 0 1.4rem;
        max-width: 22rem;
        color: #603018;
    }
    .shopingo-info-grid,
    .shopingo-promo-grid,
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
        background: #F8B878;
        color: #502818;
        font-size: 2.2rem;
    }
    .shopingo-info-card__title {
        margin: 0 0 0.4rem;
        font-size: 1.5rem;
        font-weight: 700;
    }
    .shopingo-info-card__text {
        margin: 0;
        color: #603018;
        font-size: 1.3rem;
    }
    .shopingo-promo-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        margin-bottom: 3.2rem;
    }
    .shopingo-promo-card {
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        align-items: center;
        overflow: hidden;
        min-height: 21rem;
    }
    .shopingo-promo-card--blue {
        background: linear-gradient(135deg, #F8E8D0 0%, #F8F0E0 100%);
    }
    .shopingo-promo-card--rose {
        background: linear-gradient(135deg, #F8B878 0%, #F8F0E0 100%);
    }
    .shopingo-promo-card--amber {
        background: linear-gradient(135deg, #D09050 0%, #F8F0E0 100%);
    }
    .shopingo-promo-card__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .shopingo-promo-card__body {
        padding: 2rem 2rem 2rem 0;
    }
    .shopingo-promo-card__label {
        margin-bottom: 0.6rem;
        color: #603018;
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
    }
    .shopingo-promo-card__title {
        margin: 0 0 0.8rem;
        font-size: 2.2rem;
        font-weight: 700;
        line-height: 1.05;
    }
    .shopingo-promo-card__text {
        margin: 0 0 1.2rem;
        color: #603018;
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
        background: #D09050;
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
        color: #603018;
        font-size: 1.2rem;
    }
    .shopingo-product-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
    .shopingo-product-shell {
        padding: 1.4rem;
    }
    .shopingo-product-shell .product-wrap {
        margin-bottom: 0;
    }
    .shopingo-product-shell .product {
        border: 0;
        box-shadow: none;
    }
    @media (max-width: 1199px) {
        .shopingo-hero {
            grid-template-columns: 1fr;
        }
        .shopingo-sidebar {
            order: 2;
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
        .shopingo-promo-grid,
        .shopingo-product-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .shopingo-banner__content {
            max-width: 100%;
            padding-right: 20rem;
        }
    }
    @media (max-width: 767px) {
        .shopingo-home {
            padding-top: 1.6rem;
        }
        .shopingo-banner {
            min-height: 34rem;
            background-position: center;
        }
        .shopingo-banner__content {
            position: static;
            max-width: 100%;
            padding: 2.4rem;
            background: linear-gradient(180deg, rgba(248,240,224,0.92), rgba(248,232,208,0.82));
        }
        .shopingo-hero__aside,
        .shopingo-info-grid,
        .shopingo-promo-grid,
        .shopingo-category-grid,
        .shopingo-product-grid {
            grid-template-columns: 1fr;
        }
        .shopingo-promo-card {
            grid-template-columns: 1fr;
        }
        .shopingo-promo-card__body {
            padding: 2rem;
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
    $promoPalettes = ['blue', 'rose', 'amber'];
    $infoItems = [
        ['icon' => 'w-icon-truck', 'title' => 'Envíos y devoluciones', 'text' => 'Procesos más claros para comprar con tranquilidad.'],
        ['icon' => 'w-icon-money', 'title' => 'Pago seguro', 'text' => 'Checkout respaldado por ePayco y seguimiento del pedido.'],
        ['icon' => 'w-icon-service', 'title' => 'Soporte 24/7', 'text' => 'Acompañamiento cercano para clientes y operación interna.'],
    ];
@endphp
<main class="main shopingo-home">
    <div class="container">
        <section class="shopingo-hero">
            <aside class="shopingo-panel shopingo-sidebar">
                <h2 class="shopingo-sidebar__title">Categorías</h2>
                <ul class="shopingo-sidebar__list">
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('store.shop', ['category' => $category->slug]) }}">
                                <span>{{ $category->name }}</span>
                                <span>{{ $category->products_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </aside>

            <div class="shopingo-banner">
                <div class="shopingo-banner__content">
                    <div class="shopingo-banner__eyebrow">Nueva temporada</div>
                    <h1 class="shopingo-banner__title">Compra con el orden visual del archivo de referencia</h1>
                    <p class="shopingo-banner__text">Reorganizamos la home con una composición de marketplace más comercial: navegación visible, categorías rápidas, hero dominante y bloques promocionales, sin tocar la lógica del proyecto.</p>
                    <div class="shopingo-banner__actions">
                        <a href="{{ route('store.shop') }}" class="shopingo-btn shopingo-btn--dark">Comprar ahora</a>
                        <a href="{{ route('store.stores.index') }}" class="shopingo-btn shopingo-btn--light">Ver tiendas</a>
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

        <section class="shopingo-promo-grid">
            @foreach($categories as $category)
                @php
                    $categorySlug = $category->slug ?: \Illuminate\Support\Str::slug($category->name);
                    $categoryImagePath = $category->image;

                    if (! $categoryImagePath || ! file_exists(public_path($categoryImagePath))) {
                        $categoryImagePath = $fallbackCategoryImages[$categorySlug] ?? $fallbackCategoryImages['default'];
                    }

                    $palette = $promoPalettes[$loop->index % count($promoPalettes)];
                @endphp
                @break($loop->index >= 2)
                <article class="shopingo-promo-card shopingo-promo-card--{{ $palette }}">
                    <div class="shopingo-promo-card__media">
                        <img src="{{ asset($categoryImagePath) }}" alt="{{ $category->name }}">
                    </div>
                    <div class="shopingo-promo-card__body">
                        <div class="shopingo-promo-card__label">Categoría</div>
                        <h3 class="shopingo-promo-card__title">{{ $category->name }}</h3>
                        <p class="shopingo-promo-card__text">Desde {{ $category->products_count }} productos publicados en el catálogo.</p>
                        <a href="{{ route('store.shop', ['category' => $category->slug]) }}" class="shopingo-btn shopingo-btn--light">Ver categoría</a>
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
            <div class="shopingo-product-grid">
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
            <div class="shopingo-product-grid">
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
