@extends('layouts.store', ['title' => $store['name'].' | Tienda'])

@section('content')
<style>
    .vendor-store-page {
        padding: 2.6rem 0 7rem;
        background:
            radial-gradient(circle at top left, rgba(235, 164, 104, 0.10), transparent 26%),
            linear-gradient(180deg, #ffffff 0%, #fbf1e1 100%);
    }

    .vendor-store-layout {
        display: grid;
        grid-template-columns: 31rem minmax(0, 1fr);
        gap: 2.4rem;
        align-items: start;
    }

    .vendor-store-sidebar,
    .vendor-store-panel,
    .vendor-store-hero {
        border: 1px solid rgba(231, 212, 195, 0.95);
        border-radius: 3rem;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 2.4rem 5rem rgba(87, 43, 26, 0.08);
    }

    .vendor-store-sidebar {
        position: sticky;
        top: 2rem;
        padding: 2rem;
    }

    .vendor-store-widget + .vendor-store-widget {
        margin-top: 1.6rem;
    }

    .vendor-store-widget h3 {
        margin: 0 0 1.2rem;
        color: #572B1A;
        font-family: 'Manrope', sans-serif;
        font-size: 2.2rem;
    }

    .vendor-store-categories,
    .vendor-store-mini-products,
    .vendor-store-hours {
        display: grid;
        gap: .9rem;
    }

    .vendor-store-categories a,
    .vendor-store-hours li {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.2rem;
        border-radius: 1.6rem;
        background: #FBF1E1;
        color: #3A241C;
        font-size: 1.35rem;
        font-weight: 700;
    }

    .vendor-store-categories a:hover {
        color: #572B1A;
    }

    .vendor-store-contact input,
    .vendor-store-contact textarea {
        width: 100%;
        margin-bottom: 1rem;
        border-radius: 1.6rem;
        border: 1px solid rgba(231, 212, 195, 0.95);
        background: #fff;
    }

    .vendor-store-contact textarea {
        min-height: 12rem;
    }

    .vendor-store-mini-product {
        display: grid;
        grid-template-columns: 8rem minmax(0, 1fr);
        gap: 1rem;
        align-items: center;
        padding: 1rem;
        border-radius: 1.8rem;
        background: #FFFFFF;
    }

    .vendor-store-mini-product img {
        width: 8rem;
        height: 8rem;
        object-fit: cover;
        border-radius: 1.4rem;
    }

    .vendor-store-mini-product h4 {
        margin: 0 0 .35rem;
        font-size: 1.4rem;
        line-height: 1.4;
    }

    .vendor-store-mini-product a {
        color: #572B1A;
    }

    .vendor-store-mini-product strong {
        color: #D05F32;
        font-size: 1.35rem;
    }

    .vendor-store-main {
        min-width: 0;
    }

    .vendor-store-hero {
        overflow: hidden;
        padding-bottom: 2.2rem;
    }

    .vendor-store-hero__media {
        position: relative;
        height: 34rem;
        overflow: hidden;
    }

    .vendor-store-hero__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .vendor-store-hero__overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(29, 18, 13, 0.10), rgba(29, 18, 13, 0.48));
    }

    .vendor-store-hero__content {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr);
        gap: 2rem;
        align-items: end;
        padding: 0 2.4rem;
        margin-top: -5.2rem;
        position: relative;
        z-index: 1;
    }

    .vendor-store-hero__brand {
        width: 10rem;
        height: 10rem;
        border-radius: 50%;
        overflow: hidden;
        border: 5px solid #fff;
        box-shadow: 0 1.8rem 3rem rgba(87, 43, 26, 0.12);
        background: #fff;
    }

    .vendor-store-hero__brand img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .vendor-store-hero__title {
        margin: 0;
        color: #572B1A;
        font-family: 'Manrope', sans-serif;
        font-size: clamp(3rem, 4vw, 4.8rem);
        line-height: 1.02;
    }

    .vendor-store-hero__meta {
        display: grid;
        gap: .8rem;
        margin-top: 1.2rem;
        color: #3A241C;
        font-size: 1.45rem;
    }

    .vendor-store-hero__meta li {
        display: flex;
        align-items: flex-start;
        gap: .9rem;
    }

    .vendor-store-hero__meta i {
        margin-top: .25rem;
        color: #D05F32;
    }

    .vendor-store-social {
        display: flex;
        gap: .8rem;
        flex-wrap: wrap;
        margin: 1.8rem 2.4rem 0;
    }

    .vendor-store-social a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 4.2rem;
        height: 4.2rem;
        border-radius: 50%;
        background: #FBF1E1;
        color: #572B1A;
        font-size: 1.5rem;
    }

    .vendor-store-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.6rem;
        flex-wrap: wrap;
        margin: 2rem 0 1.4rem;
        padding: 2rem 2.2rem;
        border-radius: 2.6rem;
        border: 1px solid rgba(231, 212, 195, 0.95);
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 1.6rem 3rem rgba(87, 43, 26, 0.06);
    }

    .vendor-store-toolbar h2 {
        margin: 0;
        color: #572B1A;
        font-family: 'Manrope', sans-serif;
        font-size: 2.8rem;
    }

    .vendor-store-toolbar p {
        margin: .5rem 0 0;
        color: #3A241C;
        font-size: 1.4rem;
    }

    .vendor-store-metrics {
        display: flex;
        gap: .9rem;
        flex-wrap: wrap;
    }

    .vendor-store-metric {
        padding: .85rem 1.2rem;
        border-radius: 999px;
        background: #FBF1E1;
        color: #572B1A;
        border: 1px solid rgba(231, 212, 195, 0.95);
        font-size: 1.25rem;
        font-weight: 700;
    }

    .vendor-store-products {
        padding: 2rem;
    }

    .vendor-store-empty {
        padding: 3rem 2rem;
        border-radius: 2.2rem;
        border: 1px dashed rgba(123, 74, 55, 0.16);
        background: #fffaf7;
        text-align: center;
        color: #8a7064;
    }

    @media (max-width: 1279px) {
        .vendor-store-layout {
            grid-template-columns: 1fr;
        }

        .vendor-store-sidebar {
            position: static;
        }
    }

    @media (max-width: 767px) {
        .vendor-store-hero__media {
            height: 24rem;
        }

        .vendor-store-hero__content {
            grid-template-columns: 1fr;
            margin-top: -3.6rem;
        }
    }
    @media (max-width: 479px) {
        .vendor-store-page {
            padding-bottom: 4rem;
        }

        .vendor-store-sidebar,
        .vendor-store-panel,
        .vendor-store-hero,
        .vendor-store-toolbar {
            border-radius: 2rem;
        }

        .vendor-store-sidebar,
        .vendor-store-products {
            padding: 1.4rem;
        }

        .vendor-store-hero__media {
            height: 18rem;
        }

        .vendor-store-hero__content {
            padding: 0 1.4rem;
        }

        .vendor-store-hero__brand {
            width: 7.2rem;
            height: 7.2rem;
        }

        .vendor-store-hero__title {
            font-size: 2.4rem;
        }

        .vendor-store-mini-product {
            grid-template-columns: 1fr;
        }

        .vendor-store-mini-product img {
            width: 100%;
            height: 16rem;
        }
        .vendor-store-social a {
            width: 3.8rem;
            height: 3.8rem;
        }
    }
</style>

<main class="main vendor-store-page">
    <div class="page-content">
        <div class="container">
            <div class="vendor-store-layout">
                <aside class="vendor-store-sidebar">
                    <section class="vendor-store-widget">
                        <h3>Categorías</h3>
                        <div class="vendor-store-categories">
                            @foreach($categories as $category)
                                <a href="{{ route('store.shop', ['category' => $category->slug]) }}">
                                    <span>{{ $category->name }}</span>
                                    <span>{{ $category->products_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </section>

                    <section class="vendor-store-widget">
                        <h3>Contactar tienda</h3>
                        <form class="vendor-store-contact">
                            <input type="text" class="form-control" placeholder="Tu nombre">
                            <input type="email" class="form-control" placeholder="tu@email.com">
                            <textarea class="form-control" placeholder="Cuéntanos qué necesitas"></textarea>
                            <button type="button" class="btn btn-dark btn-rounded btn-block">Enviar mensaje</button>
                        </form>
                    </section>

                    <section class="vendor-store-widget">
                        <h3>Horario</h3>
                        <ul class="vendor-store-hours list-style-none mb-0">
                            @forelse($store['business_hours'] as $businessHour)
                                <li><span>{{ $businessHour }}</span><strong>Disponible</strong></li>
                            @empty
                                <li><span>Lunes a viernes</span><strong>8:00 - 18:00</strong></li>
                                <li><span>Sábado</span><strong>8:00 - 13:00</strong></li>
                                <li><span>Domingo</span><strong>Canal digital</strong></li>
                            @endforelse
                        </ul>
                    </section>

                    <section class="vendor-store-widget">
                        <h3>Destacados</h3>
                        <div class="vendor-store-mini-products">
                            @foreach($featuredProducts as $product)
                                <article class="vendor-store-mini-product">
                                    <a href="{{ route('store.product.show', $product) }}">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </a>
                                    <div>
                                        <h4><a href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a></h4>
                                        <strong>${{ number_format($product->price, 0, ',', '.') }}</strong>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>

                    <section class="vendor-store-widget">
                        <h3>Recientes</h3>
                        <div class="vendor-store-mini-products">
                            @foreach($latestProducts as $product)
                                <article class="vendor-store-mini-product">
                                    <a href="{{ route('store.product.show', $product) }}">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    </a>
                                    <div>
                                        <h4><a href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a></h4>
                                        <strong>${{ number_format($product->price, 0, ',', '.') }}</strong>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                </aside>

                <div class="vendor-store-main">
                    <section class="vendor-store-hero">
                        <div class="vendor-store-hero__media">
                            <img src="{{ $store['banner'] }}" alt="{{ $store['name'] }}">
                            <div class="vendor-store-hero__overlay"></div>
                        </div>

                        <div class="vendor-store-hero__content">
                            <figure class="vendor-store-hero__brand">
                                <img src="{{ $store['logo'] }}" alt="{{ $store['name'] }}">
                            </figure>

                            <div>
                                <h1 class="vendor-store-hero__title">{{ $store['name'] }}</h1>
                                <ul class="vendor-store-hero__meta list-style-none mb-0">
                                    <li><i class="w-icon-map-marker"></i> {{ $store['location'] }}</li>
                                    <li><i class="w-icon-phone"></i> {{ $store['phone'] ?: 'Atención digital y comercial desde la plataforma' }}</li>
                                    <li><i class="w-icon-star-full"></i> {{ $store['short_description'] ?: 'Tienda visible para clientes del ecommerce' }}</li>
                                    <li><i class="w-icon-cart"></i> Catálogo disponible para compra en línea</li>
                                </ul>
                            </div>
                        </div>

                        <div class="vendor-store-social">
                            <a href="mailto:{{ $store['email'] }}" aria-label="Email"><i class="far fa-envelope"></i></a>
                            <a href="{{ $store['shop_url'] }}" aria-label="Catálogo"><i class="fas fa-store"></i></a>
                            @if($store['website'])
                                <a href="{{ $store['website'] }}" aria-label="Sitio web"><i class="fas fa-globe"></i></a>
                            @endif
                            @if($store['facebook_url'])
                                <a href="{{ $store['facebook_url'] }}" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                            @endif
                            @if($store['instagram_url'])
                                <a href="{{ $store['instagram_url'] }}" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                            @endif
                            <a href="{{ route('store.home') }}" aria-label="Inicio"><i class="fas fa-home"></i></a>
                        </div>
                    </section>

                    <section class="vendor-store-toolbar">
                        <div>
                            <h2>Productos de la tienda</h2>
                            <p>{{ $store['description'] ?: 'Vista inspirada en vendor-dokan-store.html con hero de tienda, sidebar comercial y grilla de catálogo.' }}</p>
                        </div>

                        <div class="vendor-store-metrics">
                            <span class="vendor-store-metric">{{ $storeMetrics['products'] }} productos activos</span>
                            <span class="vendor-store-metric">{{ $storeMetrics['featured_products'] }} destacados</span>
                            <span class="vendor-store-metric">{{ $storeMetrics['categories'] }} categorías</span>
                        </div>
                    </section>

                    <section class="vendor-store-panel vendor-store-products">
                        @if($products->isEmpty())
                            <div class="vendor-store-empty">
                                Esta tienda aún no tiene productos visibles.
                            </div>
                        @else
                            <div class="product-market-grid">
                                @foreach($products as $product)
                                    @include('store.partials.product-card', ['product' => $product])
                                @endforeach
                            </div>

                            <div class="toolbox toolbox-pagination justify-content-between shop-pagination mt-6">
                                {{ $products->links() }}
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
