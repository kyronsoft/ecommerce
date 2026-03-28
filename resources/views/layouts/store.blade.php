<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>{{ $title ?? 'La Tienda de Mi Abue' }}</title>
    @include('partials.favicons')
    <script>
        WebFontConfig = { google: { families: ['Quicksand:400,500,600,700', 'DM Serif Display:400'] } };
        (function (d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = '{{ asset('wolmart/assets/js/webfont.js') }}';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>
    <link rel="stylesheet" type="text/css" href="{{ asset('wolmart/assets/vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('wolmart/assets/vendor/animate/animate.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('wolmart/assets/vendor/magnific-popup/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('wolmart/assets/vendor/swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('wolmart/assets/css/demo1.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('wolmart/assets/css/custom-brand.css') }}">
    <style>
        :root {
            --store-ink: #502818;
            --store-muted: #603018;
            --store-line: #D09050;
            --store-warm: #F8B878;
            --store-warm-deep: #D06840;
            --store-surface: #F8F0E0;
            --store-soft: #F8E8D0;
            --store-dark: #502818;
            --store-radius: 22px;
        }
        body.storefront-shell {
            background: var(--store-soft);
            color: var(--store-ink);
            font-family: "Quicksand", sans-serif;
        }
        .storefront-shell .page-wrapper {
            background: transparent;
        }
        .store-topbar {
            border-bottom: 1px solid rgba(208, 144, 80, 0.35);
            background: var(--store-surface);
            font-size: 1.3rem;
        }
        .store-topbar__inner,
        .store-header-main__inner,
        .store-header-nav__inner {
            display: flex;
            align-items: center;
            gap: 2rem;
            min-height: 100%;
        }
        .store-topbar__inner {
            min-height: 4.4rem;
        }
        .store-topbar__message {
            color: var(--store-muted);
            font-weight: 600;
        }
        .store-topbar__meta {
            margin-left: 1rem;
        }
        .store-topbar__meta form {
            margin: 0;
        }
        .store-topbar__meta-button {
            border: 0;
            padding: 0;
            background: transparent;
            color: var(--store-ink);
            font: inherit;
            font-weight: 700;
            cursor: pointer;
        }
        .store-topbar__meta-button:hover {
            color: var(--store-warm-deep);
        }
        .store-topbar__links,
        .store-topbar__meta,
        .store-header-actions,
        .store-primary-nav {
            display: flex;
            align-items: center;
            gap: 1.6rem;
            flex-wrap: wrap;
        }
        .store-topbar a,
        .store-header-nav a,
        .store-footer a {
            color: var(--store-ink);
        }
        .store-topbar a:hover,
        .store-header-nav a:hover,
        .store-footer a:hover {
            color: var(--store-warm-deep);
        }
        .store-header-main {
            background: var(--store-warm);
            box-shadow: 0 16px 34px rgba(208, 104, 64, 0.18);
        }
        .store-header-main__inner {
            min-height: 10.4rem;
            padding: 1.6rem 0;
        }
        .store-brand-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 16rem;
        }
        .store-search-shell {
            flex: 1 1 42rem;
            display: flex;
            align-items: stretch;
            overflow: hidden;
            border: 3px solid rgba(80, 40, 24, 0.92);
            border-radius: 999px;
            background: var(--store-soft);
        }
        .store-search-shell select,
        .store-search-shell input {
            border: 0;
            background: transparent;
            height: 5.2rem;
            color: var(--store-ink);
        }
        .store-search-shell select {
            width: 15rem;
            padding: 0 1.6rem;
            border-right: 1px solid rgba(208, 144, 80, 0.4);
        }
        .store-search-shell input {
            flex: 1;
            padding: 0 1.8rem;
        }
        .store-search-shell button {
            min-width: 14rem;
            border: 0;
            background: var(--store-dark);
            color: var(--store-surface);
            font-weight: 700;
        }
        .store-support-box {
            display: flex;
            align-items: center;
            gap: 1.2rem;
            color: var(--store-dark);
        }
        .store-support-box__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 4.8rem;
            height: 4.8rem;
            border-radius: 50%;
            background: rgba(248, 232, 208, 0.72);
            font-size: 2rem;
        }
        .store-support-box__label {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        .store-support-box__value {
            margin: 0.2rem 0 0;
            font-size: 1.8rem;
            font-weight: 700;
        }
        .store-header-actions {
            margin-left: auto;
            gap: 1rem;
        }
        .store-header-action {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 4.8rem;
            height: 4.8rem;
            border-radius: 50%;
            background: rgba(248, 232, 208, 0.72);
            color: var(--store-dark);
            font-size: 2rem;
        }
        .store-header-user {
            width: auto;
            min-width: 4.8rem;
            padding: 0 1.6rem;
            gap: 0.8rem;
            border-radius: 999px;
            font-size: 1.3rem;
            font-weight: 700;
        }
        .store-header-user__name {
            max-width: 11rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .store-header-action__badge {
            position: absolute;
            top: -0.4rem;
            right: -0.2rem;
            min-width: 2rem;
            height: 2rem;
            padding: 0 0.5rem;
            border-radius: 999px;
            background: var(--store-warm-deep);
            color: var(--store-surface);
            font-size: 1.1rem;
            font-weight: 700;
            line-height: 2rem;
            text-align: center;
        }
        .store-header-nav {
            background: var(--store-dark);
            color: var(--store-surface);
        }
        .store-header-nav__inner {
            min-height: 5.8rem;
            justify-content: space-between;
        }
        .store-primary-nav a,
        .store-primary-nav button {
            color: var(--store-surface);
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .store-primary-nav .is-active {
            color: var(--store-warm);
        }
        .store-header-nav__meta {
            display: flex;
            align-items: center;
            gap: 1.4rem;
            color: rgba(248, 232, 208, 0.9);
            font-size: 1.25rem;
        }
        .store-mobile-tools {
            padding: 1.6rem 0 0;
            background: var(--store-surface);
        }
        .store-mobile-search {
            display: flex;
            border: 1px solid var(--store-line);
            border-radius: 999px;
            overflow: hidden;
            background: var(--store-surface);
        }
        .store-mobile-search input {
            border: 0;
            height: 4.8rem;
            padding: 0 1.6rem;
        }
        .store-mobile-search .btn {
            min-width: 5.2rem;
            border: 0;
            border-left: 1px solid var(--store-line);
            background: var(--store-dark);
            color: var(--store-surface);
        }
        .store-mobile-nav {
            display: flex;
            gap: 0.8rem;
            overflow-x: auto;
            padding: 1.2rem 0 0.4rem;
        }
        .store-mobile-nav a {
            white-space: nowrap;
            padding: 0.9rem 1.4rem;
            border-radius: 999px;
            background: var(--store-soft);
            color: var(--store-dark);
            font-weight: 700;
        }
        .store-mobile-nav .is-active {
            background: var(--store-warm);
        }
        .store-content-flash {
            margin-top: 1.6rem;
        }
        .store-footer {
            padding: 6rem 0 0;
            background: var(--store-surface);
            border-top: 1px solid var(--store-line);
        }
        .store-footer__main {
            display: grid;
            grid-template-columns: 1.35fr 1fr 1fr 1fr;
            gap: 2.4rem;
            padding-bottom: 3.2rem;
        }
        .store-footer__card,
        .store-footer__links {
            padding: 2.6rem;
            border: 1px solid var(--store-line);
            border-radius: var(--store-radius);
            background: var(--store-surface);
        }
        .store-footer__title {
            margin-bottom: 1.6rem;
            font-size: 1.7rem;
            font-weight: 700;
        }
        .store-footer__links ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .store-footer__links li + li {
            margin-top: 1rem;
        }
        .store-footer__meta {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.2rem;
            padding: 2.4rem 0;
            border-top: 1px solid var(--store-line);
            border-bottom: 1px solid var(--store-line);
        }
        .store-footer__meta-item {
            color: var(--store-muted);
            font-size: 1.3rem;
        }
        .store-footer__meta-item strong {
            display: block;
            margin-bottom: 0.6rem;
            color: var(--store-ink);
            font-size: 1.35rem;
        }
        .store-footer__bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.6rem;
            padding: 2rem 0 3rem;
            color: var(--store-muted);
            font-size: 1.3rem;
        }
        .store-footer__bottom-links {
            display: flex;
            align-items: center;
            gap: 1.4rem;
            flex-wrap: wrap;
        }
        @media (max-width: 1199px) {
            .store-support-box {
                display: none;
            }
            .store-footer__main {
                grid-template-columns: 1fr 1fr;
            }
            .store-footer__meta {
                grid-template-columns: 1fr 1fr;
            }
        }
        @media (max-width: 767px) {
            .store-topbar__links,
            .store-topbar__meta,
            .store-header-nav {
                display: none;
            }
            .store-header-main__inner {
                gap: 1.2rem;
            }
            .store-brand-link {
                min-width: 12rem;
            }
            .store-search-shell {
                order: 4;
                flex: 1 1 100%;
            }
            .store-search-shell select {
                display: none;
            }
            .store-footer__main,
            .store-footer__meta,
            .store-footer__bottom {
                grid-template-columns: 1fr;
                display: grid;
            }
            .store-footer__bottom-links {
                gap: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="home storefront-shell">
<div class="page-wrapper">
    <header class="storefront-header">
        <div class="store-topbar">
            <div class="container">
                <div class="store-topbar__inner">
                    <div class="store-topbar__message">Bienvenido a La Tienda de Mi Abue. Compra con una experiencia clara, cálida y confiable.</div>
                    <nav class="store-topbar__links ml-auto" aria-label="Accesos rápidos">
                        <a href="{{ route('store.home') }}">Inicio</a>
                        <a href="{{ route('store.shop') }}">Catalogo</a>
                        <a href="{{ route('store.stores.index') }}">Emprendedores</a>
                        <a href="{{ route('store.wishlist.index') }}">Favoritos</a>
                        <a href="{{ route('store.cart.index') }}">Carrito</a>
                        <a href="{{ route('store.checkout.index') }}">Finalizar Compra</a>
                        <a href="{{ route('admin.login') }}">Backoffice</a>
                    </nav>
                    <div class="store-topbar__meta">
                        @if($currentStoreUser)
                            <span>Hola, {{ $currentStoreUser->first_name ?: $currentStoreUser->name }}</span>
                            <form method="POST" action="{{ route('store.logout') }}">
                                @csrf
                                <button type="submit" class="store-topbar__meta-button">Salir</button>
                            </form>
                        @else
                            <a href="{{ route('store.login') }}">Ingresar</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="store-header-main">
            <div class="container">
                <div class="store-header-main__inner">
                    <a href="{{ route('store.home') }}" class="store-brand-link">
                        @include('partials.brand-logo', ['variant' => 'header'])
                    </a>

                    <form method="GET" action="{{ route('store.shop') }}" class="store-search-shell">
                        <div>
                            <select name="category" aria-label="Filtrar por categoría">
                                <option value="">Catálogo</option>
                                @foreach($headerCategories as $category)
                                    <option value="{{ $category->slug }}" @selected(request('category') == $category->slug)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar productos, categorías o tiendas">
                        <button type="submit">Buscar</button>
                    </form>

                    <div class="store-support-box">
                        <span class="store-support-box__icon"><i class="w-icon-call"></i></span>
                        <div>
                            <p class="store-support-box__label">Soporte</p>
                            <p class="store-support-box__value">000 000 0000</p>
                        </div>
                    </div>

                    <div class="store-header-actions">
                        <a href="{{ $currentStoreUser ? route('store.home') : route('store.login') }}" class="store-header-action store-header-user" aria-label="{{ $currentStoreUser ? 'Cliente autenticado' : 'Ingresar' }}">
                            <i class="w-icon-user"></i>
                            <span class="store-header-user__name">{{ $currentStoreUser ? ($currentStoreUser->first_name ?: 'Mi cuenta') : 'Ingresar' }}</span>
                        </a>
                        <a href="{{ route('store.wishlist.index') }}" class="store-header-action" aria-label="Favoritos">
                            <i class="w-icon-heart"></i>
                            @if(($wishlistCount ?? 0) > 0)
                                <span class="store-header-action__badge">{{ $wishlistCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('store.cart.index') }}" class="store-header-action" aria-label="Carrito">
                            <i class="w-icon-cart"></i>
                            @if(($cartCount ?? 0) > 0)
                                <span class="store-header-action__badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="store-mobile-tools d-md-none">
            <div class="container">
                <form method="GET" action="{{ route('store.shop') }}" class="store-mobile-search">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Buscar productos o tiendas">
                    <button class="btn" type="submit" aria-label="Buscar"><i class="w-icon-search"></i></button>
                </form>
                <nav class="store-mobile-nav" aria-label="Navegación móvil">
                    <a href="{{ route('store.home') }}" class="{{ request()->routeIs('store.home') ? 'is-active' : '' }}">Inicio</a>
                    <a href="{{ route('store.shop') }}" class="{{ request()->routeIs('store.shop') ? 'is-active' : '' }}">Catalogo</a>
                    <a href="{{ route('store.stores.index') }}" class="{{ request()->routeIs('store.stores.*', 'store.store.show') ? 'is-active' : '' }}">Emprendedores</a>
                    <a href="{{ route('store.wishlist.index') }}" class="{{ request()->routeIs('store.wishlist.*') ? 'is-active' : '' }}">Favoritos</a>
                    <a href="{{ route('store.cart.index') }}" class="{{ request()->routeIs('store.cart.*') ? 'is-active' : '' }}">Carrito</a>
                    <a href="{{ route('store.checkout.index') }}" class="{{ request()->routeIs('store.checkout.*') ? 'is-active' : '' }}">Finalizar Compra</a>
                    <a href="{{ route('store.login') }}" class="{{ request()->routeIs('store.login', 'store.register') ? 'is-active' : '' }}">Ingresar</a>
                    <a href="{{ route('admin.login') }}">Admin</a>
                </nav>
            </div>
        </div>

        <div class="store-header-nav">
            <div class="container">
                <div class="store-header-nav__inner">
                    <nav class="store-primary-nav" aria-label="Navegación principal">
                        <a href="{{ route('store.home') }}" class="{{ request()->routeIs('store.home') ? 'is-active' : '' }}">Inicio</a>
                        <a href="{{ route('store.shop') }}" class="{{ request()->routeIs('store.shop', 'store.product.*') ? 'is-active' : '' }}">Catalogo</a>
                        <a href="{{ route('store.stores.index') }}" class="{{ request()->routeIs('store.stores.*', 'store.store.show') ? 'is-active' : '' }}">Emprendedores</a>
                        <a href="{{ route('store.wishlist.index') }}" class="{{ request()->routeIs('store.wishlist.*') ? 'is-active' : '' }}">Favoritos</a>
                        <a href="{{ route('store.cart.index') }}" class="{{ request()->routeIs('store.cart.*') ? 'is-active' : '' }}">Carrito</a>
                        <a href="{{ route('store.checkout.index') }}" class="{{ request()->routeIs('store.checkout.*') ? 'is-active' : '' }}">Finalizar Compra</a>
                    </nav>
                    <div class="store-header-nav__meta">
                        <span>Pago seguro con ePayco</span>
                        <span>Entrega nacional</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @if (session('status'))
        <div class="container store-content-flash"><div class="alert alert-success">{{ session('status') }}</div></div>
    @endif

    @yield('content')

    @php
        $footerColumns = [
            [
                'title' => 'Empresa',
                'links' => [
                    ['label' => 'Inicio', 'url' => route('store.home')],
                    ['label' => 'Tienda', 'url' => route('store.shop')],
                    ['label' => 'Tiendas', 'url' => route('store.stores.index')],
                    ['label' => 'Favoritos', 'url' => route('store.wishlist.index')],
                    ['label' => 'Carrito', 'url' => route('store.cart.index')],
                    ['label' => 'Checkout', 'url' => route('store.checkout.index')],
                    ['label' => 'Backoffice', 'url' => route('admin.login')],
                ],
            ],
            [
                'title' => 'Mi cuenta',
                'links' => [
                    ['label' => 'Mis favoritos', 'url' => route('store.wishlist.index')],
                    ['label' => 'Ver carrito', 'url' => route('store.cart.index')],
                    ['label' => 'Finalizar compra', 'url' => route('store.checkout.index')],
                    ['label' => $currentStoreUser ? 'Mi cuenta activa' : 'Ingresar', 'url' => route('store.login')],
                    ['label' => 'Crear cuenta', 'url' => route('store.register')],
                    ['label' => 'Seguir comprando', 'url' => route('store.shop')],
                    ['label' => 'Llamar soporte', 'url' => 'tel:0000000000'],
                    ['label' => 'Escribir por correo', 'url' => 'mailto:soporte@latiendademiabue.com'],
                ],
            ],
            [
                'title' => 'Servicio al cliente',
                'links' => [
                    ['label' => 'Métodos de pago', 'url' => route('store.checkout.index')],
                    ['label' => 'Envíos', 'url' => route('store.shop')],
                    ['label' => 'Cambios y devoluciones', 'url' => route('store.shop')],
                    ['label' => 'Centro de ayuda', 'url' => 'mailto:soporte@latiendademiabue.com'],
                    ['label' => 'Soporte 24/7', 'url' => 'tel:0000000000'],
                    ['label' => 'Términos de compra', 'url' => route('store.shop')],
                ],
            ],
        ];

        $footerGroups = [
            [
                'title' => 'Categorías populares',
                'links' => collect($headerCategories ?? [])->take(6)->map(fn ($category) => [
                    'label' => $category->name,
                    'url' => route('store.shop', ['category' => $category->slug]),
                ])->all(),
            ],
            [
                'title' => 'Compra rápida',
                'links' => [
                    ['label' => 'Inicio', 'url' => route('store.home')],
                    ['label' => 'Tienda', 'url' => route('store.shop')],
                    ['label' => 'Tiendas', 'url' => route('store.stores.index')],
                    ['label' => 'Favoritos', 'url' => route('store.wishlist.index')],
                    ['label' => 'Carrito', 'url' => route('store.cart.index')],
                    ['label' => 'Checkout', 'url' => route('store.checkout.index')],
                ],
            ],
            [
                'title' => 'Atención y ayuda',
                'links' => [
                    ['label' => 'Soporte telefónico', 'url' => 'tel:0000000000'],
                    ['label' => 'Correo de soporte', 'url' => 'mailto:soporte@latiendademiabue.com'],
                    ['label' => 'Métodos de pago', 'url' => route('store.checkout.index')],
                    ['label' => 'Envíos y entregas', 'url' => route('store.shop')],
                    ['label' => 'Cambios y devoluciones', 'url' => route('store.shop')],
                ],
            ],
            [
                'title' => 'Explora más',
                'links' => [
                    ['label' => 'Nuevos productos', 'url' => route('store.shop')],
                    ['label' => 'Destacados', 'url' => route('store.shop')],
                    ['label' => 'Compra segura', 'url' => route('store.checkout.index')],
                    ['label' => 'Regalos y favoritos', 'url' => route('store.wishlist.index')],
                    ['label' => 'Portal administrativo', 'url' => route('admin.login')],
                ],
            ],
        ];
    @endphp

    <footer class="store-footer mt-10">
        <div class="container">
            <div class="store-footer__main">
                <div class="store-footer__card">
                    <a href="{{ route('store.home') }}" class="logo-footer mb-4 d-inline-flex">
                        @include('partials.brand-logo', ['variant' => 'footer'])
                    </a>
                    <h4 class="store-footer__title">¿Tienes preguntas? Te atendemos 24/7</h4>
                    <a href="tel:0000000000" class="widget-about-call">000 000 0000</a>
                    <p class="widget-about-desc">Un ecommerce cercano con navegación clara, catálogo organizado y un checkout pensado para comprar con confianza.</p>
                    <div class="social-icons social-icons-colored">
                        <a href="#" class="social-icon social-facebook w-icon-facebook" aria-label="Facebook"></a>
                        <a href="#" class="social-icon social-twitter w-icon-twitter" aria-label="Twitter"></a>
                        <a href="#" class="social-icon social-instagram fab fa-instagram" aria-label="Instagram"></a>
                    </div>
                </div>

                @foreach($footerColumns as $column)
                    <div class="store-footer__links">
                        <h3 class="store-footer__title">{{ $column['title'] }}</h3>
                        <ul>
                            @foreach($column['links'] as $link)
                                <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <div class="store-footer__meta">
                @foreach($footerGroups as $group)
                    <div class="store-footer__meta-item">
                        <strong>{{ $group['title'] }}</strong>
                        <span>{{ collect($group['links'])->pluck('label')->take(4)->implode(' · ') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="store-footer__bottom">
                <p class="copyright mb-0">© {{ now()->year }} La Tienda de Mi Abue. Una experiencia de compra inspirada en cercanía, orden y confianza.</p>
                <div class="store-footer__bottom-links">
                    <a href="{{ route('store.shop') }}">Catálogo</a>
                    <a href="{{ route('store.wishlist.index') }}">Favoritos</a>
                    <a href="{{ route('store.checkout.index') }}">Pago seguro</a>
                    <a href="{{ route('admin.login') }}">Administrador</a>
                </div>
            </div>
        </div>
    </footer>
</div>
<script src="{{ asset('wolmart/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('wolmart/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('wolmart/assets/js/main.min.js') }}"></script>
@stack('scripts')
</body>
</html>
