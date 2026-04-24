@extends('layouts.store')

@push('styles')
<style>
    .shop-categories-page {
        padding: 2.4rem 0 6rem;
        background: linear-gradient(180deg, #FFFFFF 0%, #FBF1E1 18%, #FFFFFF 100%);
    }
    .shop-categories-page a {
        transition: color 0.2s ease, border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
    }
    .shop-categories-page .container {
        position: relative;
    }
    .shop-crumbs,
    .shop-hero__banner,
    .shop-hero__stats,
    .shop-categories-grid__card,
    .shop-layout-panel,
    .shop-results-head,
    .shop-empty {
        border: 1px solid rgba(231, 212, 195, 0.95);
        border-radius: 24px;
        background: #FFFFFF;
        box-shadow: 0 18px 34px rgba(87, 43, 26, 0.08);
    }
    .shop-crumbs {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        margin-bottom: 2rem;
        padding: 1.6rem 2rem;
        background: rgba(255, 255, 255, 0.96);
    }
    .shop-crumbs__title {
        margin: 0;
        font-size: 2.4rem;
        font-weight: 700;
        color: #572B1A;
        font-family: "Manrope", sans-serif;
    }
    .shop-crumbs__trail {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        color: #3A241C;
        font-size: 1.3rem;
    }
    .shop-crumbs__trail a {
        color: #572B1A;
        font-weight: 700;
    }
    .shop-hero {
        display: grid;
        grid-template-columns: 2.2fr 1fr;
        gap: 2rem;
        margin-bottom: 2.6rem;
    }
    .shop-hero__banner {
        position: relative;
        overflow: hidden;
        min-height: 29rem;
        background:
            linear-gradient(110deg, rgba(251, 241, 225, 0.96) 0%, rgba(255, 255, 255, 0.92) 46%, rgba(208, 95, 50, 0.16) 100%),
            url('{{ asset('wolmart/assets/images/shop/banner1.jpg') }}') center right / cover no-repeat;
    }
    .shop-hero__banner::after {
        content: "";
        position: absolute;
        right: -5rem;
        bottom: -7rem;
        width: 24rem;
        height: 24rem;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(208, 95, 50, 0.18) 0%, rgba(208, 95, 50, 0) 72%);
    }
    .shop-hero__content {
        position: relative;
        z-index: 1;
        max-width: 54rem;
        padding: 3.6rem;
    }
    .shop-hero__eyebrow {
        margin-bottom: 0.8rem;
        color: #572B1A;
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }
    .shop-hero__title {
        margin: 0 0 1.2rem;
        font-family: "Manrope", sans-serif;
        font-size: clamp(3rem, 4vw, 4.8rem);
        line-height: 1;
        color: #572B1A;
    }
    .shop-hero__text {
        margin: 0 0 1.6rem;
        max-width: 42rem;
        color: #3A241C;
        font-family: "Cormorant Garamond", serif;
        font-size: 1.9rem;
        line-height: 1.7;
    }
    .shop-hero__actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .shop-chip-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 4.8rem;
        padding: 0 1.8rem;
        border-radius: 999px;
        font-size: 1.3rem;
        font-weight: 700;
    }
    .shop-chip-btn--solid {
        background: #D05F32;
        color: #FFFFFF;
    }
    .shop-chip-btn--solid:hover {
        color: #FFFFFF;
        background: #AB4D29;
    }
    .shop-chip-btn--ghost {
        border: 1px solid rgba(231, 212, 195, 0.95);
        background: #FFFFFF;
        color: #572B1A;
    }
    .shop-chip-btn--ghost:hover {
        color: #572B1A;
        border-color: #D05F32;
        background: #FBF1E1;
    }
    .shop-hero__stats {
        display: grid;
        gap: 1rem;
        align-content: start;
        padding: 2rem;
        background: linear-gradient(180deg, rgba(251, 241, 225, 0.98) 0%, rgba(255, 255, 255, 0.9) 100%);
    }
    .shop-hero__stats-item {
        padding: 1.5rem 1.6rem;
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(231, 212, 195, 0.95);
    }
    .shop-hero__stats-label {
        margin-bottom: 0.5rem;
        color: #572B1A;
        font-size: 1.15rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }
    .shop-hero__stats-value {
        margin: 0;
        color: #572B1A;
        font-size: 2rem;
        font-weight: 700;
    }
    .shop-categories-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 2rem;
        margin-bottom: 2.8rem;
    }
    .shop-categories-grid__card {
        overflow: hidden;
    }
    .shop-categories-grid__image {
        display: block;
        width: 100%;
        height: 18rem;
        object-fit: cover;
        background: linear-gradient(135deg, #FBF1E1 0%, #FFFFFF 100%);
    }
    .shop-categories-grid__body {
        padding: 1.8rem;
    }
    .shop-categories-grid__name {
        margin: 0 0 1rem;
        font-size: 1.7rem;
        font-weight: 700;
        color: #572B1A;
        font-family: "Manrope", sans-serif;
    }
    .shop-categories-grid__meta-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.7rem 0;
        color: #3A241C;
        font-size: 1.25rem;
        border-top: 1px solid rgba(208, 144, 80, 0.28);
    }
    .shop-categories-grid__meta-row:first-of-type {
        border-top: 0;
        padding-top: 0;
    }
    .shop-categories-grid__badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 3rem;
        height: 3rem;
        padding: 0 0.9rem;
        border-radius: 999px;
        background: #D05F32;
        color: #FFFFFF;
        font-size: 1.2rem;
        font-weight: 700;
    }
    .shop-page-layout {
        display: grid;
        grid-template-columns: 30rem minmax(0, 1fr);
        gap: 2rem;
        align-items: start;
    }
    .shop-layout-panel {
        padding: 2rem;
    }
    .shop-panel-title {
        margin: 0 0 1.4rem;
        color: #572B1A;
        font-size: 1.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .shop-panel-note {
        margin: 0 0 1.4rem;
        color: #3A241C;
        font-size: 1.3rem;
        line-height: 1.7;
    }
    .shop-category-filter {
        display: grid;
        gap: 0.9rem;
    }
    .shop-category-filter__link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.2rem 1.4rem;
        border: 1px solid rgba(231, 212, 195, 0.95);
        border-radius: 16px;
        background: #FFFFFF;
        color: #572B1A;
        font-weight: 700;
    }
    .shop-category-filter__link:hover,
    .shop-category-filter__link.is-active {
        color: #572B1A;
        border-color: rgba(208, 104, 64, 0.48);
        background: linear-gradient(135deg, rgba(251, 241, 225, 0.9) 0%, #FFFFFF 100%);
        box-shadow: 0 12px 22px rgba(208, 104, 64, 0.18);
    }
    .shop-results-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.6rem;
        padding: 1.8rem 2rem;
        margin-bottom: 2rem;
    }
    .shop-results-head__title {
        margin: 0 0 0.4rem;
        color: #572B1A;
        font-family: "Manrope", sans-serif;
        font-size: 2rem;
        font-weight: 700;
    }
    .shop-results-head__text {
        margin: 0;
        color: #3A241C;
        font-size: 1.35rem;
    }
    .shop-results-head__pills {
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    .shop-results-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 3.8rem;
        padding: 0 1.4rem;
        border-radius: 999px;
        background: #FBF1E1;
        color: #572B1A;
        font-size: 1.2rem;
        font-weight: 700;
    }
    .shop-grid-results {
        display: block;
    }
    .shop-grid-results__item {
        padding: 0;
        border: 0;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
    }
    .shop-empty {
        padding: 3rem;
        color: #3A241C;
        text-align: center;
    }
    .shop-pagination-wrap {
        margin-top: 2.4rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(231, 212, 195, 0.95);
    }
    .shop-pagination-wrap nav {
        display: flex;
        justify-content: center;
    }
    .shop-pagination-wrap .pagination {
        gap: 0.8rem;
    }
    .shop-pagination-wrap .page-link,
    .shop-pagination-wrap .page-item span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 4.2rem;
        height: 4.2rem;
        border-radius: 999px;
        border: 1px solid rgba(231, 212, 195, 0.95);
        color: #572B1A;
        background: #FFFFFF;
    }
    .shop-pagination-wrap .active .page-link,
    .shop-pagination-wrap .page-item.active span {
        border-color: #D05F32;
        background: #D05F32;
        color: #FFFFFF;
    }
    @media (max-width: 1279px) {
        .shop-hero {
            grid-template-columns: 1fr;
        }
        .shop-hero__stats {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
        .shop-page-layout {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 1199px) {
        .shop-categories-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }
    @media (max-width: 991px) {
        .shop-hero,
        .shop-categories-grid,
        .shop-grid-results {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
        .shop-hero__banner {
            grid-column: 1 / -1;
        }
    }
    @media (max-width: 767px) {
        .shop-categories-page {
            padding-top: 1.6rem;
        }
        .shop-crumbs,
        .shop-results-head {
            display: grid;
        }
        .shop-hero,
        .shop-categories-grid,
        .shop-grid-results {
            grid-template-columns: 1fr;
        }
        .shop-hero__content {
            padding: 2.4rem;
        }
    }
    @media (max-width: 479px) {
        .shop-categories-page {
            padding-bottom: 4rem;
        }
        .shop-crumbs,
        .shop-hero__banner,
        .shop-hero__stats,
        .shop-categories-grid__card,
        .shop-layout-panel,
        .shop-results-head,
        .shop-empty {
            border-radius: 20px;
        }
        .shop-crumbs {
            padding: 1.4rem;
        }
        .shop-crumbs__title,
        .shop-results-head__title {
            font-size: 1.8rem;
        }
        .shop-hero__content,
        .shop-layout-panel {
            padding: 1.6rem;
        }
        .shop-hero__actions,
        .shop-chip-btn {
            width: 100%;
        }
        .shop-categories-grid__body {
            padding: 1.4rem;
        }
        .shop-results-head__pills {
            justify-content: flex-start;
        }
        .shop-hero__stats {
            grid-template-columns: 1fr;
        }
        .shop-pagination-wrap .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
@php
    $selectedCategorySlug = (string) request('category', '');
    $selectedStoreSlug = (string) request('store', '');
    $searchTerm = trim((string) request('search', ''));
    $selectedCategory = $categories->firstWhere('slug', $selectedCategorySlug);
    $fallbackCategoryImages = [
        'electronica' => 'wolmart/assets/images/demos/demo1/categories/1-1.jpg',
        'moda' => 'wolmart/assets/images/demos/demo1/categories/1-2.jpg',
        'hogar' => 'wolmart/assets/images/demos/demo1/categories/2-1.jpg',
        'deportes' => 'wolmart/assets/images/demos/demo1/categories/2-2.jpg',
        'belleza' => 'wolmart/assets/images/demos/demo1/categories/2-3.jpg',
        'accesorios' => 'wolmart/assets/images/demos/demo1/categories/2-4.jpg',
        'default' => 'wolmart/assets/images/demos/demo1/categories/2-5.jpg',
    ];
    $activeCategoryName = $selectedCategory?->name ?? 'Todas las categorias';
    $heroDescription = $selectedCategory?->description
        ?: ($searchTerm !== ''
            ? 'Resultados relacionados con tu busqueda actual dentro del catalogo.'
            : 'Explora el catalogo con una distribucion visual inspirada en la maqueta de categorias y una paleta tomada solo del logo de la marca.');
@endphp

<main class="main shop-categories-page">
    <div class="container">
        <section class="shop-crumbs">
            <h1 class="shop-crumbs__title">{{ $activeCategoryName }}</h1>
            <nav class="shop-crumbs__trail" aria-label="Breadcrumb">
                <a href="{{ route('store.home') }}">Inicio</a>
                <span>/</span>
                <a href="{{ route('store.shop') }}">Catalogo</a>
                @if($selectedCategory)
                    <span>/</span>
                    <span>{{ $selectedCategory->name }}</span>
                @endif
            </nav>
        </section>

        <section class="shop-hero">
            <article class="shop-hero__banner">
                <div class="shop-hero__content">
                    <div class="shop-hero__eyebrow">Catalogo visual</div>
                    <h2 class="shop-hero__title">{{ $activeCategoryName }}</h2>
                    <p class="shop-hero__text">{{ $heroDescription }}</p>
                    <div class="shop-hero__actions">
                        <a href="{{ route('store.shop', array_filter(['category' => $selectedCategorySlug ?: null])) }}" class="shop-chip-btn shop-chip-btn--solid">Ver productos</a>
                        <a href="{{ route('store.home') }}" class="shop-chip-btn shop-chip-btn--ghost">Volver al inicio</a>
                    </div>
                </div>
            </article>

            <aside class="shop-hero__stats">
                <div class="shop-hero__stats-item">
                    <div class="shop-hero__stats-label">Productos encontrados</div>
                    <p class="shop-hero__stats-value">{{ $products->total() }}</p>
                </div>
                <div class="shop-hero__stats-item">
                    <div class="shop-hero__stats-label">Categorias visibles</div>
                    <p class="shop-hero__stats-value">{{ $categories->count() }}</p>
                </div>
                <div class="shop-hero__stats-item">
                    <div class="shop-hero__stats-label">Filtro activo</div>
                    <p class="shop-hero__stats-value">{{ $selectedCategory ? 'Si' : 'No' }}</p>
                </div>
            </aside>
        </section>

        <section class="shop-categories-grid">
            @foreach($categories->take(8) as $category)
                @php
                    $categorySlug = $category->slug ?: \Illuminate\Support\Str::slug($category->name);
                    $categoryImagePath = $category->image;

                    if (! $categoryImagePath || ! file_exists(public_path($categoryImagePath))) {
                        $categoryImagePath = $fallbackCategoryImages[$categorySlug] ?? $fallbackCategoryImages['default'];
                    }

                    $categoryProductCount = $category->products_count ?? 0;
                @endphp
                <article class="shop-categories-grid__card">
                    <a href="{{ route('store.shop', ['category' => $category->slug]) }}">
                        <img src="{{ asset($categoryImagePath) }}" alt="{{ $category->name }}" class="shop-categories-grid__image">
                    </a>
                    <div class="shop-categories-grid__body">
                        <h3 class="shop-categories-grid__name">{{ $category->name }}</h3>
                        <div class="shop-categories-grid__meta-row">
                            <span>Explorar categoria</span>
                            <span class="shop-categories-grid__badge">{{ $categoryProductCount }}</span>
                        </div>
                        <div class="shop-categories-grid__meta-row">
                            <span>Entrar al catalogo</span>
                            <a href="{{ route('store.shop', ['category' => $category->slug]) }}">Ver</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>

        <section class="shop-page-layout">
            <aside class="shop-layout-panel">
                <h2 class="shop-panel-title">Filtros del catalogo</h2>
                <p class="shop-panel-note">Mantuvimos la funcionalidad actual, pero la presentamos con una paleta tomada solo del logo para mejorar contraste y consistencia visual.</p>

                <div class="shop-category-filter">
                    <a href="{{ route('store.shop', array_filter(['search' => $searchTerm ?: null, 'store' => $selectedStoreSlug ?: null])) }}" class="shop-category-filter__link {{ $selectedCategory ? '' : 'is-active' }}">
                        <span>Todas las categorias</span>
                        <span>{{ $products->total() }}</span>
                    </a>
                    @foreach($categories as $category)
                        @php
                            $query = array_filter([
                                'category' => $category->slug,
                                'search' => $searchTerm ?: null,
                                'store' => $selectedStoreSlug ?: null,
                            ]);
                        @endphp
                        <a href="{{ route('store.shop', $query) }}" class="shop-category-filter__link {{ $selectedCategorySlug === $category->slug ? 'is-active' : '' }}">
                            <span>{{ $category->name }}</span>
                            <span>{{ $category->products_count ?? 0 }}</span>
                        </a>
                    @endforeach
                </div>
            </aside>

            <div>
                <div class="shop-results-head">
                    <div>
                        <h2 class="shop-results-head__title">Resultados del catalogo</h2>
                        <p class="shop-results-head__text">
                            @if($selectedCategory)
                                Mostrando productos de <strong>{{ $selectedCategory->name }}</strong>.
                            @elseif($searchTerm !== '')
                                Mostrando resultados para <strong>{{ $searchTerm }}</strong>.
                            @else
                                Explora todos los productos disponibles en la tienda.
                            @endif
                        </p>
                    </div>
                    <div class="shop-results-head__pills">
                        <span class="shop-results-pill">{{ $products->total() }} productos</span>
                        @if($selectedCategory)
                            <span class="shop-results-pill">Categoria activa</span>
                        @endif
                        @if($searchTerm !== '')
                            <span class="shop-results-pill">Busqueda aplicada</span>
                        @endif
                    </div>
                </div>

                @if($products->count() > 0)
                    <div class="shop-grid-results product-market-grid">
                        @foreach($products as $product)
                            <div class="shop-grid-results__item">
                                @include('store.partials.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>

                    <div class="shop-pagination-wrap">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="shop-empty">
                        <h3>No encontramos productos para esta combinacion de filtros.</h3>
                        <p>Prueba otra categoria o vuelve al catalogo completo para seguir explorando.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>
</main>
@endsection
