<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar | La Tienda de Mi Abue</title>
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
    <style>
        :root {
            --brand-ink: #502818;
            --brand-deep: #603018;
            --brand-accent: #D06840;
            --brand-accent-strong: #C86040;
            --brand-surface: #F8F0E0;
            --brand-soft: #F8E8D0;
            --brand-line: #D09050;
            --brand-glow: #F8B878;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Quicksand', sans-serif;
            color: var(--brand-ink);
            background:
                radial-gradient(circle at top left, rgba(248, 184, 120, 0.24), transparent 26%),
                radial-gradient(circle at bottom right, rgba(208, 104, 64, 0.16), transparent 30%),
                linear-gradient(180deg, #fbf4e7 0%, var(--brand-soft) 100%);
        }
        a {
            color: var(--brand-accent-strong);
            text-decoration: none;
        }
        a:hover {
            color: var(--brand-ink);
        }
        .auth-breadcrumb {
            border-top: 1px solid rgba(208, 144, 80, 0.24);
            border-bottom: 1px solid rgba(208, 144, 80, 0.24);
            background: rgba(248, 240, 224, 0.86);
        }
        .auth-container {
            width: min(100%, 1120px);
            margin: 0 auto;
            padding: 0 2rem;
        }
        .auth-breadcrumb__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.6rem;
            min-height: 7.4rem;
        }
        .auth-breadcrumb__title {
            margin: 0;
            font-family: 'DM Serif Display', serif;
            font-size: 3rem;
        }
        .auth-breadcrumb__trail {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
            color: var(--brand-deep);
            font-size: 1.35rem;
        }
        .auth-breadcrumb__trail span:last-child {
            font-weight: 700;
            color: var(--brand-accent-strong);
        }
        .auth-main {
            padding: 5.2rem 0 6.4rem;
        }
        .auth-card-wrap {
            display: flex;
            justify-content: center;
        }
        .auth-card {
            width: min(100%, 54rem);
            background: rgba(248, 240, 224, 0.96);
            border: 1px solid rgba(208, 144, 80, 0.28);
            border-radius: 28px;
            box-shadow: 0 24px 60px rgba(80, 40, 24, 0.12);
        }
        .auth-card__body {
            padding: 3.2rem;
        }
        .auth-card__frame {
            padding: 3rem;
            border: 1px solid rgba(208, 144, 80, 0.22);
            border-radius: 22px;
            background: rgba(251, 244, 231, 0.96);
        }
        .auth-card__brand {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .auth-card__brand .brand-logo-shell {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 1.4rem;
            border-radius: 20px;
            background: rgba(248, 232, 208, 0.72);
        }
        .auth-card__brand .brand-logo-image {
            width: min(100%, 20rem);
            max-height: 8rem;
            object-fit: contain;
        }
        .auth-header {
            text-align: center;
        }
        .auth-header h1 {
            margin: 0 0 0.8rem;
            font-family: 'DM Serif Display', serif;
            font-size: 3.4rem;
            color: var(--brand-ink);
        }
        .auth-header p {
            margin: 0;
            color: var(--brand-deep);
            font-size: 1.45rem;
            line-height: 1.7;
        }
        .auth-separator {
            position: relative;
            margin: 2.2rem 0;
            text-align: center;
        }
        .auth-separator::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            border-top: 1px solid rgba(208, 144, 80, 0.26);
        }
        .auth-separator span {
            position: relative;
            z-index: 1;
            display: inline-block;
            padding: 0 1.2rem;
            background: rgba(251, 244, 231, 0.96);
            color: var(--brand-accent-strong);
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }
        .feedback {
            margin-bottom: 1.4rem;
            padding: 1.2rem 1.4rem;
            border-radius: 16px;
            font-size: 1.3rem;
            font-weight: 600;
        }
        .feedback--success {
            background: rgba(248, 184, 120, 0.24);
            border: 1px solid rgba(208, 144, 80, 0.34);
            color: var(--brand-ink);
        }
        .feedback--error {
            background: rgba(208, 104, 64, 0.14);
            border: 1px solid rgba(200, 96, 64, 0.26);
            color: var(--brand-ink);
        }
        .field {
            margin-bottom: 1.5rem;
        }
        .field label {
            display: block;
            margin-bottom: 0.8rem;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--brand-ink);
        }
        .field input {
            width: 100%;
            min-height: 5.2rem;
            padding: 1rem 1.5rem;
            border: 1px solid rgba(208, 144, 80, 0.34);
            border-radius: 16px;
            background: #fffaf1;
            color: var(--brand-ink);
            font-size: 1.45rem;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .field input:focus {
            border-color: rgba(208, 104, 64, 0.68);
            box-shadow: 0 0 0 4px rgba(248, 184, 120, 0.18);
        }
        .auth-meta {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
            margin: 0.2rem 0 1.8rem;
            color: var(--brand-deep);
            font-size: 1.25rem;
        }
        .auth-submit {
            width: 100%;
            min-height: 5.2rem;
            border: 0;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--brand-ink) 0%, var(--brand-accent) 100%);
            color: var(--brand-surface);
            font-size: 1.5rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 18px 34px rgba(208, 104, 64, 0.2);
        }
        .auth-footer {
            margin-top: 2rem;
            text-align: center;
            color: var(--brand-deep);
            font-size: 1.35rem;
        }
        .auth-footer strong {
            color: var(--brand-accent-strong);
        }
        @media (max-width: 767px) {
            .auth-container {
                padding: 0 1.4rem;
            }
            .auth-breadcrumb__inner {
                min-height: auto;
                padding: 1.6rem 0;
                align-items: flex-start;
                flex-direction: column;
            }
            .auth-card__body {
                padding: 2rem;
            }
            .auth-card__frame {
                padding: 2rem 1.6rem;
            }
            .auth-header h1 {
                font-size: 2.9rem;
            }
            .auth-main {
                padding: 3.2rem 0 4.4rem;
            }
        }
    </style>
</head>
<body>
    <section class="auth-breadcrumb">
        <div class="auth-container">
            <div class="auth-breadcrumb__inner">
                <h2 class="auth-breadcrumb__title">Ingresar</h2>
                <div class="auth-breadcrumb__trail">
                    <a href="{{ route('store.home') }}">Inicio</a>
                    <span>/</span>
                    <span>Acceso clientes</span>
                </div>
            </div>
        </div>
    </section>

    <main class="auth-main">
        <div class="auth-container">
            <div class="auth-card-wrap">
                <section class="auth-card">
                    <div class="auth-card__body">
                        <div class="auth-card__frame">
                            <div class="auth-card__brand">
                                @include('partials.brand-logo', ['variant' => 'footer'])
                            </div>

                            <header class="auth-header">
                                <h1>Ingresar</h1>
                                <p>¿Aún no tienes cuenta? <a href="{{ route('store.register') }}"><strong>Regístrate aquí</strong></a></p>
                            </header>

                            <div class="auth-separator">
                                <span>Ingresa con tu correo</span>
                            </div>

                            @if(session('status'))
                                <div class="feedback feedback--success">{{ session('status') }}</div>
                            @endif

                            @if($errors->any())
                                <div class="feedback feedback--error">{{ $errors->first() }}</div>
                            @endif

                            <form method="POST" action="{{ route('store.login.store') }}">
                                @csrf
                                <div class="field">
                                    <label for="email">Correo electrónico</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com" required autofocus>
                                </div>

                                <div class="field">
                                    <label for="password">Contraseña</label>
                                    <input id="password" type="password" name="password" placeholder="Ingresa tu contraseña" required>
                                </div>

                                <div class="auth-meta">
                                    <span>Acceso exclusivo para clientes registrados.</span>
                                    <a href="{{ route('store.home') }}">Volver a la tienda</a>
                                </div>

                                <button type="submit" class="auth-submit">Ingresar a mi cuenta</button>
                            </form>

                            <div class="auth-footer">
                                El backoffice continúa con acceso independiente para administradores.
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
