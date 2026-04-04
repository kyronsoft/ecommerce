<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>{{ $title ?? 'La Tienda de Mi Abue' }}</title>
    @include('partials.favicons')
    <script>
        WebFontConfig = { google: { families: ['Inter:400,500,600,700', 'Manrope:500,600,700,800', 'Cormorant Garamond:400,500,600,700'] } };
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
            --store-ink: #3A241C;
            --store-muted: #572B1A;
            --store-line: #E7D4C3;
            --store-warm: #EBA468;
            --store-warm-deep: #D05F32;
            --store-surface: #FFFFFF;
            --store-soft: #FBF1E1;
            --store-dark: #572B1A;
            --store-button-hover: #AB4D29;
            --store-radius: 22px;
        }
        body.storefront-shell {
            background: var(--store-surface);
            color: var(--store-ink);
            font-family: "Inter", sans-serif;
        }
        h1, h2, h3, h4, h5, h6,
        .store-footer__title,
        .store-support-box__value {
            font-family: "Manrope", sans-serif;
        }
        blockquote,
        .store-topbar__message {
            font-family: "Cormorant Garamond", serif;
        }
        .storefront-shell .page-wrapper {
            background: transparent;
        }
        .store-topbar {
            border-bottom: 1px solid rgba(87, 43, 26, 0.12);
            background: var(--store-soft);
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
        .store-topbar__inner {
            justify-content: space-between;
        }
        .store-topbar__message {
            flex: 1 1 44rem;
            min-width: 0;
            display: inline-flex;
            align-items: center;
            padding: .8rem 1.4rem;
            border: 1px solid rgba(87, 43, 26, 0.14);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            box-shadow: 0 12px 24px rgba(87, 43, 26, 0.08);
            color: var(--store-muted);
            font-size: 1.7rem;
            font-weight: 600;
            line-height: 1.45;
        }
        .store-topbar__nav {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 2rem;
            flex: 0 1 auto;
            min-width: 0;
            margin-left: auto;
        }
        .store-topbar__meta {
            margin-left: 0;
            flex-wrap: nowrap;
            gap: 1.2rem;
            white-space: nowrap;
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
            flex-wrap: nowrap;
        }
        .store-topbar__links {
            gap: 1.4rem;
            white-space: nowrap;
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
            background: var(--store-surface);
            box-shadow: 0 16px 34px rgba(87, 43, 26, 0.08);
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
            border: 1px solid var(--store-line);
            border-radius: 999px;
            background: var(--store-surface);
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
            border-right: 1px solid var(--store-line);
        }
        .store-search-shell input {
            flex: 1;
            padding: 0 1.8rem;
        }
        .store-search-shell button {
            min-width: 14rem;
            border: 0;
            background: var(--store-warm-deep);
            color: #FFFFFF;
            font-weight: 700;
        }
        .store-search-shell button:hover {
            background: var(--store-button-hover);
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
            background: var(--store-soft);
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
            background: var(--store-soft);
            color: var(--store-muted);
            font-size: 2rem;
            border: 1px solid var(--store-line);
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
            color: #FFFFFF;
            font-size: 1.1rem;
            font-weight: 700;
            line-height: 2rem;
            text-align: center;
        }
        .store-header-nav {
            background: var(--store-surface);
            color: var(--store-muted);
            border-top: 1px solid rgba(87, 43, 26, 0.08);
            border-bottom: 1px solid rgba(87, 43, 26, 0.08);
        }
        .store-header-nav__inner {
            min-height: 5.8rem;
            justify-content: space-between;
        }
        .store-primary-nav a,
        .store-primary-nav button {
            color: var(--store-muted);
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .store-primary-nav .is-active {
            color: var(--store-warm-deep);
        }
        .store-header-nav__meta {
            display: flex;
            align-items: center;
            gap: 1.4rem;
            color: rgba(87, 43, 26, 0.72);
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
            background: var(--store-warm-deep);
            color: #FFFFFF;
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
            background: var(--store-warm-deep);
            color: #FFFFFF;
        }
        .store-content-flash {
            margin-top: 1.6rem;
        }
        .btn,
        button,
        input,
        select,
        textarea {
            font-family: "Inter", sans-serif;
        }
        .btn-primary,
        .btn-dark,
        .btn-cart,
        .btn-rounded.btn-primary {
            background: #D05F32;
            border-color: #D05F32;
            color: #FFFFFF;
        }
        .btn-primary:hover,
        .btn-dark:hover,
        .btn-cart:hover,
        .btn-rounded.btn-primary:hover {
            background: #AB4D29;
            border-color: #AB4D29;
            color: #FFFFFF;
        }
        .btn-outline,
        .btn-default {
            border-color: var(--store-line);
            color: #572B1A;
            background: #FFFFFF;
        }
        .btn-outline:hover,
        .btn-default:hover {
            border-color: #D05F32;
            color: #D05F32;
            background: #FBF1E1;
        }
        .page-header,
        .breadcrumb-nav {
            background: #FBF1E1;
        }
        .page-title,
        .title {
            color: #572B1A;
            font-family: "Manrope", sans-serif;
        }
        .product-market-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 2rem;
        }
        .product-wrap--editorial .product {
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
            border: 1px solid var(--store-line);
            border-radius: 20px;
            background: #FFFFFF;
            box-shadow: none;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }
        .product-wrap--editorial .product:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 30px rgba(87, 43, 26, 0.08);
        }
        .product-wrap--editorial .product-media {
            margin: 0;
            background: #FFFFFF;
            border-radius: 20px 20px 0 0;
            overflow: hidden;
            aspect-ratio: 1 / 1;
            position: relative;
        }
        .product-wrap--editorial .product-media > a,
        .product-wrap--editorial .product-media img {
            display: block;
            width: 100%;
            height: 100%;
        }
        .product-wrap--editorial .product-media img {
            object-fit: cover;
        }
        .product-wrap--editorial .product-label-group {
            position: absolute;
            top: 1.2rem;
            left: 1.2rem;
            z-index: 2;
        }
        .product-wrap--editorial .product-card__top-actions {
            position: absolute;
            top: 1.2rem;
            right: 1.2rem;
            z-index: 2;
        }
        .product-wrap--editorial .product-details {
            display: flex;
            flex: 1;
            flex-direction: column;
            gap: 0.8rem;
            padding: 1.2rem 0 0;
            background: #FFFFFF;
        }
        .product-wrap--editorial .product-cat a {
            color: #572B1A;
            font-size: 1.15rem;
            letter-spacing: 0.04em;
        }
        .product-wrap--editorial .product-name {
            font-family: "Manrope", sans-serif;
            margin: 0;
            font-size: 1.6rem;
            line-height: 1.35;
        }
        .product-wrap--editorial .product-name a {
            color: #3A241C;
            display: -webkit-box;
            overflow: hidden;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            line-clamp: 2;
        }
        .product-wrap--editorial .product-pa-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: auto;
        }
        .product-wrap--editorial .product-price-wrap {
            display: flex;
            align-items: baseline;
            gap: 0.6rem;
            flex-wrap: wrap;
        }
        .product-wrap--editorial .product-price {
            color: #D05F32;
            font-family: "Manrope", sans-serif;
            font-weight: 800;
            font-size: 1.7rem;
            line-height: 1;
        }
        .product-wrap--editorial .old-price {
            color: rgba(58, 36, 28, 0.55);
            font-size: 1.25rem;
            text-decoration: line-through;
        }
        .product-wrap--editorial .product-card__meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            color: #3A241C;
            font-size: 1.2rem;
        }
        .product-wrap--editorial .product-card__meta span {
            position: relative;
        }
        .product-wrap--editorial .product-card__meta span + span::before {
            content: "";
            display: inline-block;
            width: 0.4rem;
            height: 0.4rem;
            margin-right: 0.8rem;
            border-radius: 50%;
            background: #D05F32;
            vertical-align: middle;
        }
        .product-wrap--editorial .btn-product-icon,
        .product-wrap--editorial .product-action-horizontal .btn-product-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 4.2rem;
            height: 4.2rem;
            border-radius: 50%;
            background: #D05F32;
            color: #FFFFFF;
            border-color: #D05F32;
        }
        .product-wrap--editorial .product-card__cart-form {
            flex: 0 0 auto;
        }
        .product-wrap--editorial .btn-product-icon:hover,
        .product-wrap--editorial .product-action-horizontal .btn-product-icon:hover {
            background: #AB4D29;
            border-color: #AB4D29;
            color: #FFFFFF;
        }
        .product-label-group .product-label,
        .product-label {
            border-radius: 999px;
            color: #FFFFFF;
            font-weight: 700;
        }
        .product-label.label-discount,
        .product-label--sale {
            background: #D05F32;
        }
        .product-label.label-new,
        .product-label--new {
            background: #EBA468;
        }
        .product-label.label-featured,
        .product-label--featured {
            background: #572B1A;
        }
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 2rem;
        }
        .wishlist-card {
            position: relative;
        }
        .wishlist-card__remove {
            position: absolute;
            top: 1.2rem;
            left: 1.2rem;
            z-index: 3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.8rem;
            height: 3.8rem;
            border: 1px solid var(--store-line);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.94);
            color: #572B1A;
        }
        .wishlist-title {
            margin-bottom: 2rem;
            font-family: "Manrope", sans-serif;
            color: #572B1A;
        }
        .wishlist-empty {
            padding: 3rem;
            border: 1px solid var(--store-line);
            border-radius: 24px;
            background: #FFFFFF;
            text-align: center;
        }
        @media (max-width: 1399px) {
            .product-market-grid,
            .wishlist-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
        @media (max-width: 991px) {
            .product-market-grid,
            .wishlist-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        @media (max-width: 767px) {
            .product-market-grid,
            .wishlist-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1.6rem;
            }
            .product-wrap--editorial .product-name {
                font-size: 1.45rem;
            }
        }
        @media (max-width: 479px) {
            .product-market-grid,
            .wishlist-grid {
                grid-template-columns: 1fr 1fr;
                gap: 1.2rem;
            }
            .product-wrap--editorial .product-details {
                padding-top: 1rem;
            }
        }
        .store-footer {
            padding: 6rem 0 0;
            background: var(--store-dark);
            border-top: 0;
            color: var(--store-soft);
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
            border: 1px solid rgba(251, 241, 225, 0.14);
            border-radius: var(--store-radius);
            background: rgba(251, 241, 225, 0.05);
        }
        .store-footer__title {
            margin-bottom: 1.6rem;
            font-size: 1.7rem;
            font-weight: 700;
            color: var(--store-soft);
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
            border-top: 1px solid rgba(251, 241, 225, 0.12);
            border-bottom: 1px solid rgba(251, 241, 225, 0.12);
        }
        .store-footer__meta-item {
            color: rgba(251, 241, 225, 0.76);
            font-size: 1.3rem;
        }
        .store-footer__meta-item strong {
            display: block;
            margin-bottom: 0.6rem;
            color: var(--store-soft);
            font-size: 1.35rem;
        }
        .store-footer__bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.6rem;
            padding: 2rem 0 3rem;
            color: rgba(251, 241, 225, 0.76);
            font-size: 1.3rem;
        }
        .store-footer a,
        .store-footer .widget-about-call,
        .store-footer .widget-about-desc,
        .store-footer .copyright {
            color: var(--store-soft);
        }
        .store-footer a:hover {
            color: var(--store-warm);
        }
        .store-footer .social-icon {
            border-color: rgba(251, 241, 225, 0.2);
            background: rgba(251, 241, 225, 0.08);
            color: var(--store-soft);
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
            .store-topbar__nav {
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
                    <div class="store-topbar__nav">
                        <nav class="store-topbar__links" aria-label="Accesos rápidos">
                            <a href="{{ route('store.home') }}">Inicio</a>
                            <a href="{{ route('store.shop') }}">Catalogo</a>
                            <a href="{{ route('store.stores.index') }}">Emprendedores</a>
                            <a href="{{ route('store.entrepreneur') }}">Quiero ser emprendedor</a>
                            <a href="{{ route('admin.login') }}">Backoffice emprendedores</a>
                        </nav>
                        <div class="store-topbar__meta">
                            @if($currentStoreUser)
                                <span>Hola, {{ $currentStoreUser->first_name ?: $currentStoreUser->name }}</span>
                                <form method="POST" action="{{ route('store.logout') }}">
                                    @csrf
                                    <button type="submit" class="store-topbar__meta-button">Salir</button>
                                </form>
                            @else
                                <a href="{{ route('store.login') }}">Acceso clientes</a>
                            @endif
                        </div>
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
                        <a href="{{ $currentStoreUser ? route('store.home') : route('store.login') }}" class="store-header-action store-header-user" aria-label="{{ $currentStoreUser ? 'Cliente autenticado' : 'Acceso clientes' }}">
                            <i class="w-icon-user"></i>
                            <span class="store-header-user__name">{{ $currentStoreUser ? ($currentStoreUser->first_name ?: 'Mi cuenta') : 'Clientes' }}</span>
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
                    <a href="{{ route('store.entrepreneur') }}" class="{{ request()->routeIs('store.entrepreneur') ? 'is-active' : '' }}">Quiero ser emprendedor</a>
                    <a href="{{ route('store.wishlist.index') }}" class="{{ request()->routeIs('store.wishlist.*') ? 'is-active' : '' }}">Favoritos</a>
                    <a href="{{ route('store.cart.index') }}" class="{{ request()->routeIs('store.cart.*') ? 'is-active' : '' }}">Carrito</a>
                    <a href="{{ route('store.checkout.index') }}" class="{{ request()->routeIs('store.checkout.*') ? 'is-active' : '' }}">Finalizar Compra</a>
                    <a href="{{ route('store.login') }}" class="{{ request()->routeIs('store.login', 'store.register') ? 'is-active' : '' }}">Clientes</a>
                    <a href="{{ route('admin.login') }}">Backoffice</a>
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
                        <a href="{{ route('store.entrepreneur') }}" class="{{ request()->routeIs('store.entrepreneur') ? 'is-active' : '' }}">Quiero ser emprendedor</a>
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
                    ['label' => 'Quiero ser emprendedor', 'url' => route('store.entrepreneur')],
                    ['label' => 'Favoritos', 'url' => route('store.wishlist.index')],
                    ['label' => 'Carrito', 'url' => route('store.cart.index')],
                    ['label' => 'Checkout', 'url' => route('store.checkout.index')],
                    ['label' => 'Backoffice emprendedores', 'url' => route('admin.login')],
                    ['label' => 'Quiero ser emprendedor', 'url' => route('store.entrepreneur')],
                ],
            ],
            [
                'title' => 'Mi cuenta',
                'links' => [
                    ['label' => 'Mis favoritos', 'url' => route('store.wishlist.index')],
                    ['label' => 'Ver carrito', 'url' => route('store.cart.index')],
                    ['label' => 'Finalizar compra', 'url' => route('store.checkout.index')],
                    ['label' => $currentStoreUser ? 'Mi cuenta activa' : 'Acceso clientes', 'url' => route('store.login')],
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
                    ['label' => 'Backoffice emprendedores', 'url' => route('admin.login')],
                    ['label' => 'Quiero ser emprendedor', 'url' => route('store.entrepreneur')],
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
                    <a href="{{ route('store.entrepreneur') }}">Quiero ser emprendedor</a>
                    <a href="{{ route('admin.login') }}">Backoffice emprendedores</a>
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
