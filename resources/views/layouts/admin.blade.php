<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>{{ $title ?? 'Backoffice | La Tienda de Mi Abue' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('wolmart/assets/images/icons/favicon.png') }}">
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
    <link rel="stylesheet" type="text/css" href="{{ asset('wolmart/assets/css/style.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('wolmart/assets/css/custom-brand.css') }}">
</head>
<body class="my-account">
<div class="page-wrapper">
    <header class="header header-border">
        <div class="header-top"><div class="container"><div class="header-left"><p class="welcome-msg">Backoffice administrativo de La Tienda de Mi Abue.</p></div><div class="header-right"><a href="{{ route('store.home') }}">Ver tienda</a></div></div></div>
        <div class="header-middle"><div class="container"><div class="header-left"><a href="{{ route('admin.dashboard') }}" class="logo">@include('partials.brand-logo', ['variant' => 'header'])</a></div></div></div>
    </header>
    <main class="main account">
        <div class="page-content pt-2">
            <div class="container">
                <nav class="breadcrumb-nav mb-4"><ul class="breadcrumb"><li><a href="{{ route('admin.dashboard') }}">Backoffice</a></li><li>{{ $breadcrumb ?? 'Dashboard' }}</li></ul></nav>
                <div class="tab tab-vertical row gutter-lg">
                    <ul class="nav nav-tabs mb-6" role="tablist">
                        <li class="nav-item"><a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a></li>
                        <li class="nav-item"><a href="{{ route('admin.categories.index') }}" class="nav-link">Categorías</a></li>
                        <li class="nav-item"><a href="{{ route('admin.products.index') }}" class="nav-link">Productos</a></li>
                        <li class="nav-item"><a href="{{ route('admin.orders.index') }}" class="nav-link">Pedidos</a></li>
                        <li class="nav-item"><a href="{{ route('admin.customers.index') }}" class="nav-link">Clientes</a></li>
                    </ul>
                    <div class="tab-content mb-6">
                        @if (session('status'))<div class="alert alert-success mb-4">{{ session('status') }}</div>@endif
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script src="{{ asset('wolmart/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('wolmart/assets/js/main.min.js') }}"></script>
</body>
</html>
