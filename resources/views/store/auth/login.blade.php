<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Clientes | La Tienda de Mi Abue</title>
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
    <style>
        :root {
            --auth-bg: #FFFFFF;
            --auth-soft: #FBF1E1;
            --auth-border: #E7D4C3;
            --auth-text: #3A241C;
            --auth-heading: #572B1A;
            --auth-primary: #D05F32;
            --auth-primary-hover: #AB4D29;
            --auth-muted: #7B665C;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 2.4rem;
            font-family: 'Inter', sans-serif;
            color: var(--auth-text);
            background:
                radial-gradient(circle at top left, rgba(235, 164, 104, 0.12), transparent 28%),
                radial-gradient(circle at bottom right, rgba(208, 95, 50, 0.08), transparent 26%),
                linear-gradient(180deg, #fffdfa 0%, var(--auth-soft) 100%);
        }
        a {
            color: inherit;
            text-decoration: none;
        }
        .auth-shell {
            width: min(100%, 43rem);
            padding: .8rem;
            border: 1px solid rgba(231, 212, 195, 0.95);
            border-radius: 2.4rem;
            background: rgba(255, 255, 255, 0.72);
            box-shadow: 0 26px 60px rgba(87, 43, 26, 0.10);
        }
        .auth-card {
            background: #FFFFFF;
            border: 1px solid var(--auth-border);
            border-radius: 1.8rem;
            padding: 2.1rem 2rem 2.2rem;
        }
        .auth-tabs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            margin-bottom: 1.6rem;
            border-bottom: 1px solid var(--auth-border);
        }
        .auth-tab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .75rem .75rem 1rem;
            margin-bottom: -1px;
            border-bottom: 3px solid transparent;
            color: var(--auth-muted);
            font-family: 'Manrope', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }
        .auth-tab.is-active {
            color: var(--auth-primary);
            border-bottom-color: var(--auth-primary);
        }
        .auth-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 1.2rem;
        }
        .auth-logo .brand-logo-shell {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: .5rem .8rem;
            border-radius: 1.2rem;
            background: var(--auth-soft);
        }
        .auth-logo .brand-logo-image {
            width: min(100%, 7rem);
            max-height: 3rem;
            object-fit: contain;
        }
        .auth-copy {
            margin: 0 0 1.6rem;
            text-align: center;
            color: var(--auth-muted);
            font-size: 1rem;
            line-height: 1.55;
        }
        .feedback {
            margin-bottom: 1.2rem;
            padding: .85rem 1rem;
            border-radius: 1rem;
            font-size: .95rem;
            font-weight: 600;
        }
        .feedback--success {
            color: #2f7d4d;
            background: rgba(47, 125, 77, 0.10);
            border: 1px solid rgba(47, 125, 77, 0.16);
        }
        .feedback--error {
            color: #a94848;
            background: rgba(169, 72, 72, 0.10);
            border: 1px solid rgba(169, 72, 72, 0.16);
        }
        .field + .field {
            margin-top: 1.2rem;
        }
        .field label {
            display: block;
            margin-bottom: .55rem;
            color: var(--auth-heading);
            font-size: 1rem;
            font-weight: 700;
        }
        .field input {
            width: 100%;
            min-height: 3.9rem;
            padding: .85rem 1rem;
            border: 1px solid var(--auth-border);
            border-radius: 1.2rem;
            background: #FFFFFF;
            color: var(--auth-text);
            font-size: 1.05rem;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .field input:focus {
            border-color: rgba(208, 95, 50, 0.45);
            box-shadow: 0 0 0 4px rgba(235, 164, 104, 0.14);
        }
        .auth-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin: 1.2rem 0 1.6rem;
            color: var(--auth-muted);
            font-size: .95rem;
        }
        .auth-remember {
            display: inline-flex;
            align-items: center;
            gap: .8rem;
        }
        .auth-remember input {
            width: 1.2rem;
            height: 1.2rem;
            margin: 0;
        }
        .auth-meta__link {
            color: var(--auth-primary);
            font-weight: 700;
        }
        .auth-submit {
            width: 100%;
            min-height: 4rem;
            border: 0;
            border-radius: 999px;
            background: var(--auth-primary);
            color: #FFFFFF;
            font-family: 'Manrope', sans-serif;
            font-size: 1.1rem;
            font-weight: 800;
            cursor: pointer;
        }
        .auth-submit:hover {
            background: var(--auth-primary-hover);
        }
        .auth-footer {
            margin-top: 1.6rem;
            text-align: center;
            color: var(--auth-muted);
            font-size: .95rem;
        }
        .auth-social {
            display: flex;
            justify-content: center;
            gap: .8rem;
            margin-top: 1.1rem;
        }
        .auth-social a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.3rem;
            height: 3.3rem;
            border-radius: 50%;
            border: 1px solid currentColor;
            font-size: 1.25rem;
        }
        .auth-social .facebook { color: #3b5998; }
        .auth-social .twitter { color: #1da1f2; }
        .auth-social .google { color: #db4437; }
        .auth-links {
            margin-top: 1.3rem;
            text-align: center;
            color: var(--auth-muted);
            font-size: .95rem;
        }
        .auth-links a {
            color: var(--auth-primary);
            font-weight: 700;
        }
        @media (max-width: 640px) {
            body {
                padding: 1.2rem;
            }
            .auth-card {
                padding: 1.8rem 1.4rem 2rem;
            }
            .auth-meta {
                flex-direction: column;
                align-items: flex-start;
            }
        }
        @media (max-width: 430px) {
            .auth-tabs {
                grid-template-columns: 1fr;
            }
            .auth-tab {
                padding: .8rem;
            }
            .auth-links {
                display: grid;
                gap: .6rem;
            }
            .auth-links span {
                display: none;
            }
            .auth-social {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <div class="auth-shell">
        <section class="auth-card">
            <div class="auth-tabs">
                <a href="{{ route('store.login') }}" class="auth-tab is-active">Ingresar</a>
                <a href="{{ route('store.register') }}" class="auth-tab">Registro</a>
            </div>

            <div class="auth-logo">
                @include('partials.brand-logo', ['variant' => 'footer'])
            </div>

            <p class="auth-copy">Ingresa con tu correo y contraseña para continuar comprando con tu cuenta de cliente.</p>

            @if(session('status'))
                <div class="feedback feedback--success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="feedback feedback--error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('store.login.store') }}">
                @csrf
                <div class="field">
                    <label for="email">Correo electrónico *</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Contraseña *</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <div class="auth-meta">
                    <label class="auth-remember" for="remember">
                        <input id="remember" type="checkbox" disabled>
                        <span>Recordarme</span>
                    </label>
                    <span class="auth-meta__link">Acceso seguro</span>
                </div>

                <button type="submit" class="auth-submit">Ingresar</button>
            </form>

            <div class="auth-footer">
                Accede también con redes sociales
                <div class="auth-social">
                    <a href="#" class="facebook" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="twitter" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="google" aria-label="Google"><i class="fab fa-google"></i></a>
                </div>
            </div>

            <div class="auth-links">
                <a href="{{ route('store.home') }}">Volver a la tienda</a>
                <span> · </span>
                <a href="{{ route('admin.login') }}">Acceso emprendedores</a>
            </div>
        </section>
    </div>
</body>
</html>
