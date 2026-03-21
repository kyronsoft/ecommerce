@extends('layouts.store', ['title' => 'Tiendas registradas | La Tienda de Mi Abue'])

@section('content')
<style>
    .stores-page {
        padding: 3rem 0 7rem;
        background:
            radial-gradient(circle at top left, rgba(217, 121, 87, 0.14), transparent 28%),
            radial-gradient(circle at top right, rgba(123, 74, 55, 0.12), transparent 26%),
            linear-gradient(180deg, #fffaf6 0%, #f4ece5 100%);
    }

    .stores-hero {
        position: relative;
        overflow: hidden;
        padding: 4.4rem;
        border-radius: 3.4rem;
        background:
            linear-gradient(135deg, rgba(95, 54, 40, 0.96), rgba(217, 121, 87, 0.88)),
            url('{{ asset('wolmart/assets/images/vendor/dokan/1.jpg') }}') center/cover no-repeat;
        color: #fff;
        box-shadow: 0 3rem 7rem rgba(95, 54, 40, 0.16);
    }

    .stores-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(38, 22, 16, 0.58), rgba(38, 22, 16, 0.18));
    }

    .stores-hero > * {
        position: relative;
        z-index: 1;
    }

    .stores-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .8rem;
        padding: .8rem 1.4rem;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .stores-hero__layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 34rem;
        gap: 2rem;
        align-items: end;
    }

    .stores-hero h1 {
        margin: 1.8rem 0 1rem;
        color: #fff;
        font-family: 'DM Serif Display', serif;
        font-size: clamp(3.6rem, 5vw, 6.4rem);
        line-height: .98;
        max-width: 70rem;
    }

    .stores-hero p {
        max-width: 62rem;
        margin: 0;
        color: rgba(255, 255, 255, 0.86);
        font-size: 1.65rem;
        line-height: 1.85;
    }

    .stores-hero-callout {
        padding: 2.2rem;
        border-radius: 2.6rem;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(10px);
    }

    .stores-hero-callout strong {
        display: block;
        margin-bottom: .7rem;
        color: #fff;
        font-size: 2.8rem;
        font-weight: 700;
        line-height: 1;
    }

    .stores-hero-callout span {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.4rem;
        line-height: 1.7;
    }

    .stores-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 1.6rem;
        margin: 2.2rem 0 0;
    }

    .stores-stat {
        padding: 1.8rem;
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 2rem;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(10px);
    }

    .stores-stat strong {
        display: block;
        margin-bottom: .4rem;
        color: #fff;
        font-size: 2.9rem;
        font-weight: 700;
        line-height: 1;
    }

    .stores-stat span {
        color: rgba(255, 255, 255, 0.82);
        font-size: 1.35rem;
    }

    .stores-toolbar {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1.8rem;
        flex-wrap: wrap;
        margin: 2.6rem 0 1.6rem;
        padding: 2rem 2.2rem;
        border: 1px solid rgba(123, 74, 55, 0.12);
        border-radius: 2.8rem;
        background: rgba(255, 255, 255, 0.84);
        box-shadow: 0 2rem 4rem rgba(95, 54, 40, 0.08);
    }

    .stores-toolbar h2 {
        margin: 0;
        color: #5f3628;
        font-family: 'DM Serif Display', serif;
        font-size: 3rem;
    }

    .stores-toolbar p {
        margin: .6rem 0 0;
        color: #8a7064;
        font-size: 1.45rem;
    }

    .stores-toolbar-meta {
        display: inline-flex;
        align-items: center;
        gap: .8rem;
        margin-top: 1rem;
        padding: .8rem 1.2rem;
        border-radius: 999px;
        background: #fff3ea;
        color: #7b4a37;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .stores-layout {
        display: grid;
        grid-template-columns: 31rem minmax(0, 1fr);
        gap: 2rem;
        align-items: start;
    }

    .stores-filter-panel,
    .stores-results-panel {
        border: 1px solid rgba(123, 74, 55, 0.12);
        border-radius: 3rem;
        background: rgba(255, 255, 255, 0.88);
        box-shadow: 0 2.4rem 5rem rgba(95, 54, 40, 0.08);
    }

    .stores-filter-panel {
        position: sticky;
        top: 2rem;
        padding: 2rem;
    }

    .stores-results-panel {
        padding: 1.8rem;
    }

    .stores-filter-panel h3 {
        margin: 0 0 1rem;
        color: #5f3628;
        font-family: 'DM Serif Display', serif;
        font-size: 2.5rem;
    }

    .stores-filter-panel p {
        margin: 0 0 1.6rem;
        color: #8a7064;
        font-size: 1.4rem;
    }

    .stores-filter-group + .stores-filter-group {
        margin-top: 1.3rem;
    }

    .stores-filter-group label {
        display: block;
        margin-bottom: .7rem;
        color: #5f3628;
        font-size: 1.35rem;
        font-weight: 700;
    }

    .stores-filter-group .form-control,
    .stores-filter-group select {
        min-height: 4.8rem;
        border-radius: 1.7rem;
        border: 1px solid rgba(123, 74, 55, 0.14);
        background: #fff;
    }

    .stores-filter-checks {
        display: grid;
        gap: .8rem;
        margin-top: .6rem;
    }

    .stores-filter-check {
        display: flex;
        align-items: center;
        gap: .8rem;
        padding: .95rem 1rem;
        border-radius: 1.5rem;
        background: #fff7f1;
        color: #6f564b;
        font-size: 1.35rem;
        font-weight: 600;
    }

    .stores-filter-actions {
        display: grid;
        gap: .9rem;
        margin-top: 1.6rem;
    }

    .stores-filter-actions .btn {
        width: 100%;
    }

    .stores-results-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.4rem;
        flex-wrap: wrap;
        margin-bottom: 1.4rem;
        padding: 0 .4rem;
    }

    .stores-results-head strong {
        color: #5f3628;
        font-size: 1.5rem;
    }

    .stores-results-head span {
        color: #8a7064;
        font-size: 1.35rem;
    }

    .stores-list {
        display: grid;
        gap: 1.8rem;
    }

    .store-card {
        position: relative;
        overflow: hidden;
        border-radius: 2.8rem;
        border: 1px solid rgba(123, 74, 55, 0.10);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(255, 248, 244, 0.96));
        box-shadow: 0 2rem 4.8rem rgba(95, 54, 40, 0.08);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .store-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 2.8rem 5.6rem rgba(95, 54, 40, 0.12);
    }

    .store-card__header {
        position: relative;
        min-height: 22rem;
        overflow: hidden;
    }

    .store-card__header-link,
    .store-card__title-link {
        color: inherit;
        text-decoration: none;
    }

    .store-card__banner {
        width: 100%;
        height: 22rem;
        object-fit: cover;
        display: block;
        transition: transform .28s ease;
    }

    .store-card:hover .store-card__banner {
        transform: scale(1.03);
    }

    .store-card__header::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(31, 18, 14, 0.08), rgba(31, 18, 14, 0.26));
        pointer-events: none;
    }

    .store-card__label {
        position: absolute;
        top: 1.8rem;
        left: 1.8rem;
        z-index: 1;
        padding: .75rem 1.2rem;
        border-radius: 999px;
        background: rgba(95, 54, 40, 0.86);
        color: #fff;
        font-size: 1.2rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .store-card__body {
        display: grid;
        grid-template-columns: auto minmax(0, 1fr) auto;
        gap: 2rem;
        padding: 2.2rem;
        align-items: start;
    }

    .store-card__brand {
        width: 9rem;
        height: 9rem;
        margin-top: -5.6rem;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 1.6rem 3rem rgba(95, 54, 40, 0.14);
        background: #fff;
        position: relative;
        z-index: 1;
    }

    .store-card__brand img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .store-card__title {
        margin: 0;
        color: #5f3628;
        font-family: 'DM Serif Display', serif;
        font-size: 3rem;
        line-height: 1.04;
    }

    .store-card__title-link:hover {
        color: #c46645;
    }

    .store-card__meta {
        display: flex;
        flex-wrap: wrap;
        gap: .8rem;
        margin: 1rem 0 1.2rem;
    }

    .store-card__chip {
        display: inline-flex;
        align-items: center;
        gap: .6rem;
        padding: .65rem 1rem;
        border-radius: 999px;
        background: #f8ece4;
        color: #7b4a37;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .store-card__copy {
        margin: 0 0 1.4rem;
        color: #755f55;
        font-size: 1.5rem;
        line-height: 1.85;
    }

    .store-card__highlights,
    .store-card__stats {
        display: flex;
        gap: .9rem;
        flex-wrap: wrap;
    }

    .store-card__tag,
    .store-card__stat {
        padding: .72rem 1.08rem;
        border-radius: 999px;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .store-card__tag {
        background: rgba(217, 121, 87, 0.12);
        color: #c46645;
    }

    .store-card__stat {
        background: #fff6f0;
        color: #5f3628;
        border: 1px solid rgba(123, 74, 55, 0.10);
    }

    .store-card__aside {
        min-width: 21rem;
        display: grid;
        gap: 1rem;
    }

    .store-card__cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .7rem;
        min-height: 4.9rem;
        padding: 1rem 1.8rem;
        border-radius: 999px;
        background: linear-gradient(135deg, #7b4a37 0%, #d97957 100%);
        color: #fff;
        font-size: 1.4rem;
        font-weight: 700;
        text-decoration: none;
        box-shadow: 0 1.4rem 2.6rem rgba(123, 74, 55, 0.14);
    }

    .store-card__cta:hover {
        color: #fff;
    }

    .store-card__owner {
        padding: 1.4rem 1.6rem;
        border-radius: 2rem;
        background: #fff7f1;
        color: #8a7064;
        font-size: 1.3rem;
        line-height: 1.7;
    }

    .store-card__owner strong {
        display: block;
        color: #5f3628;
        font-size: 1.45rem;
    }

    .stores-empty {
        padding: 4rem 2.4rem;
        border: 1px dashed rgba(123, 74, 55, 0.18);
        border-radius: 3rem;
        background: rgba(255, 255, 255, 0.72);
        color: #8a7064;
        text-align: center;
    }

    .stores-pagination {
        margin-top: 1.8rem;
    }

    @media (max-width: 1199px) {
        .stores-hero__layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 991px) {
        .stores-layout {
            grid-template-columns: 1fr;
        }

        .stores-filter-panel {
            position: static;
        }

        .stores-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .store-card__body {
            grid-template-columns: 1fr;
        }

        .store-card__brand {
            margin-top: -6rem;
        }

        .store-card__aside {
            min-width: 0;
        }
    }

    @media (max-width: 767px) {
        .stores-page {
            padding-top: 2rem;
        }

        .stores-hero {
            padding: 2.6rem;
            border-radius: 2.4rem;
        }

        .stores-stats {
            grid-template-columns: 1fr;
        }

        .stores-toolbar,
        .stores-results-panel,
        .stores-filter-panel {
            border-radius: 2rem;
        }
    }
</style>

<main class="main stores-page">
    <div class="page-content">
        <div class="container">
            <section class="stores-hero">
                <span class="stores-hero__eyebrow">Marketplace / Tiendas registradas</span>
                <div class="stores-hero__layout">
                    <div>
                        <h1>Encuentra la tienda perfecta para comprar hoy</h1>
                        <p>
                            Explora negocios y emprendimientos activos del marketplace, compara su propuesta y entra directo al catálogo propio de cada tienda con una navegación clara, rápida y visualmente impactante.
                        </p>
                    </div>

                    <aside class="stores-hero-callout">
                        <strong>{{ $storeStats['stores'] }}</strong>
                        <span>tiendas activas listas para recibir visitas, búsquedas y compras dentro de la plataforma.</span>
                    </aside>
                </div>

                <div class="stores-stats">
                    <article class="stores-stat">
                        <strong>{{ $storeStats['stores'] }}</strong>
                        <span>Tiendas visibles</span>
                    </article>
                    <article class="stores-stat">
                        <strong>{{ $storeStats['products'] }}</strong>
                        <span>Productos activos</span>
                    </article>
                    <article class="stores-stat">
                        <strong>{{ $storeStats['featured_products'] }}</strong>
                        <span>Destacados</span>
                    </article>
                    <article class="stores-stat">
                        <strong>{{ $storeStats['categories'] }}</strong>
                        <span>Categorías</span>
                    </article>
                </div>
            </section>

            <section class="stores-toolbar">
                <div>
                    <h2>Directorio de tiendas</h2>
                    <p>Se muestran las primeras 10 tiendas por página con búsqueda, filtros y ordenamiento.</p>
                    <span class="stores-toolbar-meta">
                        {{ $stores->total() }} resultado{{ $stores->total() === 1 ? '' : 's' }} disponibles
                    </span>
                </div>
            </section>

            <div class="stores-layout">
                <aside class="stores-filter-panel">
                    <h3>Busca y filtra</h3>
                    <p>Encuentra la tienda por nombre, ubicación o visibilidad destacada.</p>

                    <form method="GET" action="{{ route('store.stores.index') }}">
                        <div class="stores-filter-group">
                            <label for="q">Buscar tienda</label>
                            <input id="q" type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Nombre, responsable o ciudad">
                        </div>

                        <div class="stores-filter-group">
                            <label for="location">Ubicación</label>
                            <select id="location" name="location" class="form-control">
                                <option value="">Todas las ubicaciones</option>
                                @foreach($locations as $availableLocation)
                                    <option value="{{ $availableLocation }}" @selected($selectedLocation === $availableLocation)>{{ $availableLocation }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="stores-filter-group">
                            <label for="sort">Ordenar por</label>
                            <select id="sort" name="sort" class="form-control">
                                <option value="featured" @selected($sort === 'featured')>Más relevantes</option>
                                <option value="products" @selected($sort === 'products')>Más productos</option>
                                <option value="newest" @selected($sort === 'newest')>Más recientes</option>
                                <option value="oldest" @selected($sort === 'oldest')>Más antiguas</option>
                                <option value="name_asc" @selected($sort === 'name_asc')>Nombre A-Z</option>
                                <option value="name_desc" @selected($sort === 'name_desc')>Nombre Z-A</option>
                            </select>
                        </div>

                        <div class="stores-filter-group">
                            <label>Filtros rápidos</label>
                            <div class="stores-filter-checks">
                                <label class="stores-filter-check">
                                    <input type="checkbox" name="featured" value="1" @checked($featuredOnly)>
                                    Solo tiendas destacadas
                                </label>
                                <label class="stores-filter-check">
                                    <input type="checkbox" name="with_catalog" value="1" @checked($withCatalogOnly)>
                                    Solo con catálogo visible
                                </label>
                            </div>
                        </div>

                        <div class="stores-filter-actions">
                            <button type="submit" class="btn btn-dark btn-rounded">Aplicar filtros</button>
                            <a href="{{ route('store.stores.index') }}" class="btn btn-outline btn-rounded">Limpiar</a>
                        </div>
                    </form>
                </aside>

                <section class="stores-results-panel">
                    <div class="stores-results-head">
                        <div>
                            <strong>Tiendas encontradas</strong>
                            <span>Página {{ $stores->currentPage() }} de {{ max($stores->lastPage(), 1) }}</span>
                        </div>
                    </div>

                    @if($stores->isEmpty())
                        <div class="stores-empty">
                            No encontramos tiendas con ese criterio. Prueba con otro nombre, ubicación o cambia el ordenamiento.
                        </div>
                    @else
                        <div class="stores-list">
                            @foreach($stores as $store)
                                <article class="store-card">
                                    <div class="store-card__header">
                                        <a href="{{ $store['catalog_url'] }}" class="store-card__header-link" aria-label="Ir al catálogo de {{ $store['name'] }}">
                                            <img src="{{ $store['banner'] }}" alt="{{ $store['name'] }}" class="store-card__banner">
                                        </a>
                                        <span class="store-card__label">{{ $store['label'] }}</span>
                                    </div>

                                    <div class="store-card__body">
                                        <figure class="store-card__brand">
                                            <a href="{{ $store['catalog_url'] }}" aria-label="Ir al catálogo de {{ $store['name'] }}">
                                                <img src="{{ $store['logo'] }}" alt="{{ $store['name'] }}">
                                            </a>
                                        </figure>

                                        <div>
                                            <h3 class="store-card__title">
                                                <a href="{{ $store['catalog_url'] }}" class="store-card__title-link">{{ $store['name'] }}</a>
                                            </h3>

                                            <div class="store-card__meta">
                                                <span class="store-card__chip"><i class="w-icon-user"></i> {{ $store['owner_name'] }}</span>
                                                <span class="store-card__chip"><i class="w-icon-map-marker"></i> {{ $store['location'] }}</span>
                                                <span class="store-card__chip"><i class="w-icon-clock"></i> Desde {{ $store['joined_at'] }}</span>
                                            </div>

                                            <p class="store-card__copy">
                                                {{ $store['short_description'] ?: 'Tienda registrada dentro de la aplicación con catálogo disponible, navegación por categorías y operación lista para compras en línea.' }}
                                            </p>

                                            <div class="store-card__highlights">
                                                @foreach($store['highlights'] as $highlight)
                                                    <span class="store-card__tag">{{ $highlight }}</span>
                                                @endforeach
                                            </div>

                                            <div class="store-card__stats" style="margin-top: 1.4rem;">
                                                <span class="store-card__stat">{{ $store['products_count'] }} productos</span>
                                                <span class="store-card__stat">{{ $store['featured_products_count'] }} destacados</span>
                                                <span class="store-card__stat">{{ $store['categories_count'] }} categorías</span>
                                            </div>
                                        </div>

                                        <aside class="store-card__aside">
                                            <a href="{{ $store['catalog_url'] }}" class="store-card__cta">
                                                Ver catálogo
                                                <i class="w-icon-long-arrow-right"></i>
                                            </a>
                                            <div class="store-card__owner">
                                                <strong>{{ $store['owner_name'] }}</strong>
                                                {{ $store['email'] ?: 'Contacto privado' }}
                                            </div>
                                        </aside>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="stores-pagination">
                            {{ $stores->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
</main>
@endsection
