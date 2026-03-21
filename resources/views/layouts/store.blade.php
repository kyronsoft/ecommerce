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
</head>
<body class="home">
<div class="page-wrapper">
    <header class="header">
        <div class="header-top">
            <div class="container">
                <div class="header-left"><p class="welcome-msg">Ecommerce con identidad visual inspirada en la calidez de La Tienda de Mi Abue.</p></div>
                <div class="header-right"><a href="{{ route('admin.login') }}">Backoffice</a></div>
            </div>
        </div>
        <div class="header-middle">
            <div class="container">
                <div class="header-left mr-md-4">
                    <a href="{{ route('store.home') }}" class="logo ml-lg-0">
                        @include('partials.brand-logo', ['variant' => 'header'])
                    </a>
                    <form method="GET" action="{{ route('store.shop') }}" class="header-search hs-expanded hs-round d-none d-md-flex input-wrapper">
                        <div class="select-box product-catalog-select">
                            <select name="category" aria-label="Filtrar por categoría">
                                <option value="">Catálogo</option>
                                @foreach($headerCategories as $category)
                                    <option value="{{ $category->slug }}" @selected(request('category') == $category->slug)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Buscar productos">
                        <button class="btn btn-search" type="submit"><i class="w-icon-search"></i></button>
                    </form>
                </div>
                <div class="header-right ml-4">
                    <div class="header-call d-xs-show d-lg-flex align-items-center">
                        <a href="tel:0000000000" class="w-icon-call"></a>
                        <div class="call-info d-lg-show">
                            <h4 class="chat font-weight-normal font-size-md text-normal ls-normal text-light mb-0">Soporte</h4>
                            <a href="tel:0000000000" class="phone-number font-weight-bolder ls-50">000 000 0000</a>
                        </div>
                    </div>
                    <a class="wishlist label-down link d-xs-show" href="{{ route('store.wishlist.index') }}">
                        <i class="w-icon-heart">
                            @if(($wishlistCount ?? 0) > 0)
                                <span class="header-icon-badge wishlist-count">{{ $wishlistCount }}</span>
                            @endif
                        </i>
                        <span class="wishlist-label d-lg-show">Favoritos</span>
                    </a>
                    <div class="dropdown cart-dropdown cart-offcanvas mr-0 mr-lg-2">
                        <a href="{{ route('store.cart.index') }}" class="cart-toggle label-down link">
                            <i class="w-icon-cart">
                                @if(($cartCount ?? 0) > 0)
                                    <span class="header-icon-badge cart-count">{{ $cartCount }}</span>
                                @endif
                            </i>
                            <span class="cart-label">Carrito</span>
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
                    <a href="{{ route('store.shop') }}" class="{{ request()->routeIs('store.shop') ? 'is-active' : '' }}">Tienda</a>
                    <a href="{{ route('store.stores.index') }}" class="{{ request()->routeIs('store.stores.*', 'store.store.show') ? 'is-active' : '' }}">Tiendas</a>
                    <a href="{{ route('store.checkout.index') }}" class="{{ request()->routeIs('store.checkout.*') ? 'is-active' : '' }}">Checkout</a>
                    <a href="{{ route('admin.login') }}">Admin</a>
                </nav>
            </div>
        </div>
        <div class="header-bottom sticky-content fix-top sticky-header has-dropdown">
            <div class="container">
                <div class="inner-wrap">
                    <div class="header-left">
                        <nav class="main-nav"><ul class="menu active-underline"><li><a href="{{ route('store.home') }}">Inicio</a></li><li><a href="{{ route('store.shop') }}">Tienda</a></li><li><a href="{{ route('store.stores.index') }}">Tiendas</a></li><li><a href="{{ route('store.cart.index') }}">Carrito</a></li><li><a href="{{ route('store.checkout.index') }}">Checkout</a></li></ul></nav>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @if (session('status'))
        <div class="container mt-4"><div class="alert alert-success">{{ session('status') }}</div></div>
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

    <footer class="footer store-footer-shell mt-10">
        <div class="container">
            <div class="footer-top">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="widget widget-about footer-about-card">
                            <a href="{{ route('store.home') }}" class="logo-footer mb-4">
                                @include('partials.brand-logo', ['variant' => 'footer'])
                            </a>
                            <h4 class="widget-about-title">¿Tienes preguntas? Te atendemos 24/7</h4>
                            <a href="tel:0000000000" class="widget-about-call">000 000 0000</a>
                            <p class="widget-about-desc">Un ecommerce cálido, claro y cercano para que tus clientes encuentren productos, guarden favoritos y compren con confianza.</p>
                            <div class="social-icons social-icons-colored">
                                <a href="#" class="social-icon social-facebook w-icon-facebook" aria-label="Facebook"></a>
                                <a href="#" class="social-icon social-twitter w-icon-twitter" aria-label="Twitter"></a>
                                <a href="#" class="social-icon social-instagram fab fa-instagram" aria-label="Instagram"></a>
                                <a href="#" class="social-icon social-youtube fab fa-youtube" aria-label="YouTube"></a>
                                <a href="#" class="social-icon social-pinterest w-icon-pinterest" aria-label="Pinterest"></a>
                            </div>
                        </div>
                    </div>

                    @foreach($footerColumns as $column)
                        <div class="col-lg-2 col-md-6 col-sm-4">
                            <div class="widget">
                                <h3 class="widget-title">{{ $column['title'] }}</h3>
                                <ul class="widget-body">
                                    @foreach($column['links'] as $link)
                                        <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="footer-middle">
                @foreach($footerGroups as $group)
                    <div class="category-box">
                        <h6 class="category-name">{{ $group['title'] }}:</h6>
                        @foreach($group['links'] as $link)
                            <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="footer-bottom">
                <div class="footer-left">
                    <p class="copyright mb-0">© {{ now()->year }} La Tienda de Mi Abue. Una experiencia de compra inspirada en cercanía, orden y confianza.</p>
                </div>
                <div class="footer-right">
                    <a href="{{ route('store.shop') }}">Catálogo</a>
                    <span class="footer-divider"></span>
                    <a href="{{ route('store.wishlist.index') }}">Favoritos</a>
                    <span class="footer-divider"></span>
                    <a href="{{ route('store.checkout.index') }}">Pago seguro</a>
                </div>
            </div>
        </div>
    </footer>
</div>
<script src="{{ asset('wolmart/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('wolmart/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('wolmart/assets/js/main.min.js') }}"></script>
</body>
</html>
