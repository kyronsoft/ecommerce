@php
    $pageTitle = $pageTitle ?? $breadcrumb ?? 'Dashboard';
    $pageDescription = $pageDescription ?? 'Gestiona catalogo, clientes, pedidos y operacion diaria del ecommerce desde un solo lugar.';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>{{ $title ?? $pageTitle.' | Backoffice | La Tienda de Mi Abue' }}</title>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('wolmart/assets/css/style.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('wolmart/assets/css/custom-brand.css') }}">
    <style>
        :root {
            --admin-bg: #f7efe8;
            --admin-surface: #ffffff;
            --admin-surface-alt: #fff9f5;
            --admin-border: rgba(123, 74, 55, 0.14);
            --admin-border-strong: rgba(123, 74, 55, 0.24);
            --admin-primary: #7b4a37;
            --admin-primary-strong: #5f3628;
            --admin-accent: #d97957;
            --admin-text: #4f372e;
            --admin-muted: #8a7064;
            --admin-success: #2f7d4d;
            --admin-warning: #b27b21;
            --admin-danger: #a94848;
            --admin-shadow: 0 24px 60px rgba(123, 74, 55, 0.10);
            --admin-radius-xl: 32px;
            --admin-radius-lg: 24px;
            --admin-radius-md: 18px;
            --admin-radius-sm: 14px;
        }

        body.admin-body {
            min-height: 100vh;
            margin: 0;
            color: var(--admin-text);
            font-family: 'Quicksand', sans-serif;
            background:
                radial-gradient(circle at top left, rgba(217, 121, 87, 0.16), transparent 34%),
                radial-gradient(circle at top right, rgba(123, 74, 55, 0.12), transparent 30%),
                linear-gradient(180deg, #fffaf7 0%, var(--admin-bg) 100%);
        }

        .admin-shell {
            min-height: 100vh;
            padding: 2.4rem;
        }

        .admin-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            padding: 1.8rem 2.4rem;
            margin-bottom: 2.2rem;
            background: rgba(255, 255, 255, 0.88);
            border: 1px solid var(--admin-border);
            border-radius: var(--admin-radius-xl);
            box-shadow: var(--admin-shadow);
            backdrop-filter: blur(14px);
        }

        .admin-brand {
            display: flex;
            align-items: center;
            gap: 1.4rem;
            color: inherit;
            text-decoration: none;
        }

        .admin-brand-copy strong {
            display: block;
            margin-bottom: .2rem;
            color: var(--admin-primary-strong);
            font-size: 2rem;
            font-weight: 700;
        }

        .admin-brand-copy span,
        .admin-brand-copy small {
            display: block;
            color: var(--admin-muted);
        }

        .admin-topbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .admin-pill,
        .admin-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .7rem;
            min-height: 4.6rem;
            padding: 1rem 1.8rem;
            border: 1px solid var(--admin-border-strong);
            border-radius: 999px;
            background: #fff;
            color: var(--admin-primary-strong);
            font-size: 1.4rem;
            font-weight: 700;
            line-height: 1;
            text-decoration: none;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .admin-pill:hover,
        .admin-btn:hover {
            transform: translateY(-1px);
            color: var(--admin-primary-strong);
            box-shadow: 0 16px 30px rgba(123, 74, 55, 0.10);
        }

        .admin-pill--primary,
        .admin-btn--primary {
            border-color: transparent;
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-accent) 100%);
            color: #fff;
        }

        .admin-pill-form {
            margin: 0;
        }

        .admin-btn--secondary {
            background: var(--admin-surface-alt);
        }

        .admin-btn--danger {
            color: var(--admin-danger);
            border-color: rgba(169, 72, 72, 0.24);
            background: #fff7f7;
        }

        .admin-workspace {
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 2.2rem;
            align-items: start;
        }

        .admin-sidebar-card,
        .admin-panel {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid var(--admin-border);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--admin-shadow);
            backdrop-filter: blur(12px);
        }

        .admin-sidebar-card {
            position: sticky;
            top: 2.4rem;
            padding: 2.4rem 1.8rem;
        }

        .admin-sidebar-card small {
            display: block;
            margin-bottom: .8rem;
            color: var(--admin-muted);
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .admin-sidebar-nav {
            display: grid;
            gap: .7rem;
        }

        .admin-sidebar-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.2rem 1.4rem;
            border-radius: 16px;
            color: var(--admin-text);
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 700;
            transition: background .18s ease, color .18s ease, transform .18s ease;
        }

        .admin-sidebar-link:hover {
            background: var(--admin-surface-alt);
            transform: translateX(2px);
            color: var(--admin-primary-strong);
        }

        .admin-sidebar-link.is-active {
            background: linear-gradient(135deg, rgba(123, 74, 55, 0.12), rgba(217, 121, 87, 0.18));
            color: var(--admin-primary-strong);
        }

        .admin-sidebar-link i {
            width: 2rem;
            text-align: center;
            color: var(--admin-accent);
        }

        .admin-main {
            min-width: 0;
        }

        .admin-page-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.8rem;
            margin-bottom: 1.8rem;
            padding: 2.6rem 2.8rem;
            background: linear-gradient(135deg, rgba(123, 74, 55, 0.94), rgba(217, 121, 87, 0.88));
            border-radius: var(--admin-radius-xl);
            color: #fff;
            box-shadow: 0 24px 50px rgba(123, 74, 55, 0.16);
        }

        .admin-page-head h1 {
            margin: .2rem 0 .8rem;
            color: #fff;
            font-family: 'DM Serif Display', serif;
            font-size: clamp(2.8rem, 3vw, 4.4rem);
            line-height: 1.05;
        }

        .admin-page-head p {
            margin: 0;
            max-width: 68rem;
            color: rgba(255, 255, 255, 0.88);
            font-size: 1.5rem;
        }

        .admin-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .8rem;
            padding: .7rem 1.2rem;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.10);
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .admin-page-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .admin-page-actions .admin-btn {
            border-color: rgba(255, 255, 255, 0.18);
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        .admin-page-actions .admin-btn--primary {
            background: #fff;
            color: var(--admin-primary-strong);
        }

        .admin-flash,
        .admin-errors {
            margin-bottom: 1.6rem;
            padding: 1.4rem 1.8rem;
            border-radius: 18px;
            font-size: 1.4rem;
            font-weight: 600;
        }

        .admin-flash {
            border: 1px solid rgba(47, 125, 77, 0.18);
            background: rgba(47, 125, 77, 0.09);
            color: var(--admin-success);
        }

        .admin-errors {
            border: 1px solid rgba(169, 72, 72, 0.18);
            background: rgba(169, 72, 72, 0.08);
            color: var(--admin-danger);
        }

        .admin-errors ul {
            margin: .8rem 0 0 1.8rem;
        }

        .admin-errors li + li {
            margin-top: .4rem;
        }

        .admin-stats-grid,
        .admin-grid-2,
        .admin-grid-3,
        .admin-grid-4 {
            display: grid;
            gap: 1.6rem;
        }

        .admin-stats-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 1.8rem;
        }

        .admin-grid-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .admin-grid-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .admin-grid-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .admin-stat-card {
            position: relative;
            overflow: hidden;
            padding: 2rem;
            border-radius: var(--admin-radius-lg);
            background: rgba(255, 255, 255, 0.94);
            border: 1px solid var(--admin-border);
            box-shadow: var(--admin-shadow);
        }

        .admin-stat-card::after {
            content: '';
            position: absolute;
            inset: auto -2rem -3rem auto;
            width: 9rem;
            height: 9rem;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(217, 121, 87, 0.16), transparent 72%);
        }

        .admin-stat-label {
            display: block;
            margin-bottom: .8rem;
            color: var(--admin-muted);
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .admin-stat-value {
            display: block;
            margin-bottom: .5rem;
            color: var(--admin-primary-strong);
            font-family: 'DM Serif Display', serif;
            font-size: 3.4rem;
            line-height: 1;
        }

        .admin-stat-help {
            color: var(--admin-muted);
            font-size: 1.35rem;
        }

        .admin-panel {
            padding: 2rem;
        }

        .admin-panel + .admin-panel {
            margin-top: 1.6rem;
        }

        .admin-section-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1.2rem;
            margin-bottom: 1.6rem;
        }

        .admin-section-head h2,
        .admin-section-head h3,
        .admin-panel h2,
        .admin-panel h3 {
            margin: 0;
            color: var(--admin-primary-strong);
            font-family: 'DM Serif Display', serif;
            font-size: 2.6rem;
            line-height: 1.1;
        }

        .admin-section-head p,
        .admin-panel > p {
            margin: .6rem 0 0;
            color: var(--admin-muted);
            font-size: 1.4rem;
        }

        .admin-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.4rem;
            margin-bottom: 1.4rem;
            flex-wrap: wrap;
        }

        .admin-toolbar-copy h2 {
            margin: 0;
        }

        .admin-toolbar-copy p {
            margin: .5rem 0 0;
            color: var(--admin-muted);
        }

        .admin-table-wrap {
            overflow: auto;
            border: 1px solid var(--admin-border);
            border-radius: 20px;
        }

        .admin-table {
            width: 100%;
            min-width: 72rem;
            margin: 0;
            border-collapse: collapse;
            background: #fff;
        }

        .admin-table th,
        .admin-table td {
            padding: 1.4rem 1.6rem;
            border-bottom: 1px solid rgba(123, 74, 55, 0.08);
            vertical-align: top;
            font-size: 1.4rem;
        }

        .admin-table th {
            color: var(--admin-primary-strong);
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            background: #fff8f3;
        }

        .admin-table tbody tr:hover {
            background: #fffdfb;
        }

        .admin-muted {
            color: var(--admin-muted);
        }

        .admin-status {
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            padding: .55rem 1rem;
            border-radius: 999px;
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: .03em;
            text-transform: uppercase;
        }

        .admin-status--pending,
        .admin-status--processing {
            color: var(--admin-warning);
            background: rgba(178, 123, 33, 0.12);
        }

        .admin-status--paid,
        .admin-status--completed,
        .admin-status--approved,
        .admin-status--active {
            color: var(--admin-success);
            background: rgba(47, 125, 77, 0.12);
        }

        .admin-status--shipped,
        .admin-status--featured {
            color: #2d6a99;
            background: rgba(45, 106, 153, 0.12);
        }

        .admin-status--cancelled,
        .admin-status--rejected,
        .admin-status--inactive {
            color: var(--admin-danger);
            background: rgba(169, 72, 72, 0.10);
        }

        .admin-actions {
            display: flex;
            align-items: center;
            gap: .8rem;
            flex-wrap: wrap;
        }

        .admin-link {
            color: var(--admin-accent);
            font-weight: 700;
            text-decoration: none;
        }

        .admin-link:hover {
            color: var(--admin-primary-strong);
        }

        .admin-pagination {
            margin-top: 1.6rem;
        }

        .admin-kv {
            display: grid;
            gap: 1.2rem;
        }

        .admin-kv-item {
            padding: 1.4rem 1.6rem;
            border: 1px solid var(--admin-border);
            border-radius: 18px;
            background: #fff;
        }

        .admin-kv-label {
            display: block;
            margin-bottom: .45rem;
            color: var(--admin-muted);
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .admin-kv-value {
            display: block;
            color: var(--admin-primary-strong);
            font-size: 1.5rem;
            font-weight: 700;
        }

        .admin-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.4rem;
        }

        .admin-field {
            margin-bottom: 1.4rem;
        }

        .admin-field label {
            display: block;
            margin-bottom: .7rem;
            color: var(--admin-primary-strong);
            font-size: 1.35rem;
            font-weight: 700;
        }

        .admin-field .form-control,
        .admin-field textarea,
        .admin-field select {
            min-height: 4.8rem;
            border: 1px solid var(--admin-border-strong);
            border-radius: 16px;
            background: #fff;
            box-shadow: none;
            color: var(--admin-text);
        }

        .admin-field textarea.form-control {
            min-height: 12rem;
            padding-top: 1.2rem;
        }

        .admin-field small {
            display: block;
            margin-top: .6rem;
            color: var(--admin-muted);
        }

        .admin-field .form-control.is-invalid,
        .admin-field textarea.is-invalid,
        .admin-field select.is-invalid {
            border-color: rgba(169, 72, 72, 0.42);
            background: rgba(169, 72, 72, 0.05);
            box-shadow: 0 0 0 4px rgba(169, 72, 72, 0.08);
        }

        .admin-field-error {
            margin-top: .7rem;
            color: var(--admin-danger) !important;
            font-size: 1.25rem;
            font-weight: 700;
            line-height: 1.45;
        }

        .admin-checkbox-row {
            display: flex;
            align-items: center;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .admin-checkbox {
            display: inline-flex;
            align-items: center;
            gap: .7rem;
            color: var(--admin-text);
            font-size: 1.4rem;
            font-weight: 600;
        }

        .admin-list {
            display: grid;
            gap: 1rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .admin-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.2rem;
            padding: 1.3rem 1.4rem;
            border: 1px solid var(--admin-border);
            border-radius: 16px;
            background: #fff;
        }

        .admin-empty {
            padding: 2.4rem;
            border: 1px dashed var(--admin-border-strong);
            border-radius: 22px;
            background: #fffaf7;
            color: var(--admin-muted);
            text-align: center;
        }

        .admin-image-preview {
            width: 100%;
            max-width: 18rem;
            border-radius: 20px;
            border: 1px solid var(--admin-border);
            background: #fff;
        }

        .admin-hero-media {
            display: flex;
            align-items: flex-start;
            gap: 1.6rem;
            flex-wrap: wrap;
        }

        .admin-hero-media img {
            width: 14rem;
            height: 14rem;
            object-fit: cover;
            border-radius: 24px;
            border: 1px solid var(--admin-border);
            background: #fff;
        }

        .admin-meta-row {
            display: flex;
            align-items: center;
            gap: .8rem;
            flex-wrap: wrap;
            margin-top: .9rem;
        }

        .admin-separator {
            margin: 1.8rem 0;
            border-top: 1px solid var(--admin-border);
        }

        .admin-summary-box {
            padding: 1.8rem;
            border-radius: 20px;
            background: linear-gradient(180deg, #fff8f4 0%, #fff 100%);
            border: 1px solid var(--admin-border);
        }

        .admin-summary-box strong {
            color: var(--admin-primary-strong);
        }

        .admin-order-items td .form-control,
        .admin-order-items td select {
            min-width: 11rem;
        }

        .admin-order-items .admin-row-total {
            min-width: 10rem;
            font-weight: 700;
            color: var(--admin-primary-strong);
        }

        .admin-helper {
            color: var(--admin-muted);
            font-size: 1.3rem;
        }

        .admin-stack {
            display: grid;
            gap: 1.6rem;
        }

        .admin-price {
            color: var(--admin-primary-strong);
            font-size: 2.1rem;
            font-weight: 700;
        }

        .admin-subtitle {
            margin: 0;
            color: var(--admin-muted);
            font-size: 1.4rem;
        }

        @media (max-width: 1199px) {
            .admin-stats-grid,
            .admin-grid-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 991px) {
            .admin-shell {
                padding: 1.6rem;
            }

            .admin-workspace {
                grid-template-columns: 1fr;
            }

            .admin-sidebar-card {
                position: static;
            }

            .admin-page-head,
            .admin-topbar {
                padding: 1.8rem;
            }

            .admin-form-grid,
            .admin-grid-3,
            .admin-grid-2 {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767px) {
            .admin-stats-grid,
            .admin-grid-4 {
                grid-template-columns: 1fr;
            }

            .admin-brand {
                align-items: flex-start;
            }

            .admin-page-actions,
            .admin-topbar-actions {
                width: 100%;
                justify-content: stretch;
            }

            .admin-page-actions .admin-btn,
            .admin-topbar-actions .admin-pill {
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="admin-body">
@php
    $navigation = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'fas fa-chart-pie'],
        ['label' => 'Categorias', 'route' => 'admin.categories.index', 'icon' => 'fas fa-tags'],
        ['label' => 'Productos', 'route' => 'admin.products.index', 'icon' => 'fas fa-box-open'],
        ['label' => 'Tiendas', 'route' => 'admin.stores.index', 'icon' => 'fas fa-store'],
        ['label' => 'Pedidos', 'route' => 'admin.orders.index', 'icon' => 'fas fa-shopping-bag'],
        ['label' => 'Clientes', 'route' => 'admin.customers.index', 'icon' => 'fas fa-users'],
    ];
@endphp
<div class="admin-shell">
    <header class="admin-topbar">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand">
            @include('partials.brand-logo', ['variant' => 'header'])
            <span class="admin-brand-copy">
                <strong>Backoffice</strong>
                <span>La Tienda de Mi Abue</span>
                <small>Operacion, catalogo y seguimiento diario</small>
            </span>
        </a>
        <div class="admin-topbar-actions">
            <span class="admin-pill">{{ $currentAdminUser->email ?? session('admin_user_email') }}</span>
            <a href="{{ route('store.home') }}" class="admin-pill">Ver tienda</a>
            <a href="{{ route('admin.products.create') }}" class="admin-pill admin-pill--primary">Nuevo producto</a>
            <form method="POST" action="{{ route('admin.logout') }}" class="admin-pill-form">
                @csrf
                <button type="submit" class="admin-pill">Cerrar sesión</button>
            </form>
        </div>
    </header>

    <div class="admin-workspace">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-card">
                <small>Gestion</small>
                <nav class="admin-sidebar-nav">
                    @foreach($navigation as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            class="admin-sidebar-link {{ request()->routeIs(str_replace('.index', '.*', $item['route'])) || request()->routeIs($item['route']) ? 'is-active' : '' }}"
                        >
                            <i class="{{ $item['icon'] }}"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </aside>

        <main class="admin-main">
            <section class="admin-page-head">
                <div>
                    <span class="admin-eyebrow">Backoffice / {{ $breadcrumb ?? 'Dashboard' }}</span>
                    <h1>{{ $pageTitle }}</h1>
                    <p>{{ $pageDescription }}</p>
                </div>
                @hasSection('page_actions')
                    <div class="admin-page-actions">
                        @yield('page_actions')
                    </div>
                @endif
            </section>

            @if (session('status'))
                <div class="admin-flash">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="admin-errors">
                    <strong>Hay datos por corregir.</strong>
                    <div>Revisa los mensajes marcados debajo de cada campo para completar la tienda correctamente.</div>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<script src="{{ asset('wolmart/assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('wolmart/assets/js/main.min.js') }}"></script>
@stack('scripts')
</body>
</html>
