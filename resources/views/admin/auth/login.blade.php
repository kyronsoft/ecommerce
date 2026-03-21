<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso Backoffice | La Tienda de Mi Abue</title>
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
            --brand-primary: #7b4a37;
            --brand-primary-strong: #5f3628;
            --brand-accent: #d97957;
            --brand-bg: #f6ede6;
            --brand-card: rgba(255, 255, 255, 0.92);
            --brand-border: rgba(123, 74, 55, 0.16);
            --brand-text: #50392f;
            --brand-muted: #8b7064;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 2rem;
            font-family: 'Quicksand', sans-serif;
            color: var(--brand-text);
            background:
                radial-gradient(circle at top left, rgba(217, 121, 87, 0.18), transparent 32%),
                radial-gradient(circle at bottom right, rgba(123, 74, 55, 0.16), transparent 35%),
                linear-gradient(180deg, #fffaf7 0%, var(--brand-bg) 100%);
        }
        .login-shell {
            width: min(100%, 1020px);
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            border-radius: 32px;
            overflow: hidden;
            background: var(--brand-card);
            border: 1px solid var(--brand-border);
            box-shadow: 0 35px 70px rgba(123, 74, 55, 0.14);
            backdrop-filter: blur(12px);
        }
        .login-hero {
            padding: 4.4rem;
            background: linear-gradient(160deg, rgba(123, 74, 55, 0.96), rgba(217, 121, 87, 0.90));
            color: #fff;
        }
        .login-hero .brand {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1.2rem 1.6rem;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.10);
        }
        .login-hero img {
            width: 14rem;
            max-width: 100%;
        }
        .login-hero h1 {
            margin: 2.4rem 0 1rem;
            font-family: 'DM Serif Display', serif;
            font-size: clamp(3rem, 4vw, 5rem);
            line-height: 1.02;
        }
        .login-hero p {
            margin: 0;
            max-width: 42rem;
            color: rgba(255, 255, 255, 0.86);
            font-size: 1.6rem;
            line-height: 1.7;
        }
        .login-panel {
            padding: 4.4rem 3.6rem;
            background: rgba(255, 255, 255, 0.94);
        }
        .login-panel small {
            display: inline-block;
            margin-bottom: 1rem;
            color: var(--brand-muted);
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .login-panel h2 {
            margin: 0 0 .8rem;
            color: var(--brand-primary-strong);
            font-family: 'DM Serif Display', serif;
            font-size: 3.4rem;
        }
        .login-panel p {
            margin: 0 0 2.2rem;
            color: var(--brand-muted);
            font-size: 1.45rem;
            line-height: 1.7;
        }
        .field {
            margin-bottom: 1.4rem;
        }
        .field label {
            display: block;
            margin-bottom: .7rem;
            color: var(--brand-primary-strong);
            font-size: 1.35rem;
            font-weight: 700;
        }
        .field input {
            width: 100%;
            min-height: 5rem;
            padding: 1rem 1.4rem;
            border: 1px solid var(--brand-border);
            border-radius: 16px;
            outline: none;
            color: var(--brand-text);
            font-size: 1.45rem;
        }
        .field input:focus {
            border-color: rgba(123, 74, 55, 0.4);
            box-shadow: 0 0 0 4px rgba(123, 74, 55, 0.08);
        }
        .feedback {
            margin-bottom: 1.6rem;
            padding: 1.2rem 1.4rem;
            border-radius: 16px;
            font-size: 1.35rem;
            font-weight: 600;
        }
        .feedback--success {
            color: #2f7d4d;
            background: rgba(47, 125, 77, 0.10);
            border: 1px solid rgba(47, 125, 77, 0.16);
        }
        .feedback--error {
            color: #a94848;
            background: rgba(169, 72, 72, 0.09);
            border: 1px solid rgba(169, 72, 72, 0.16);
        }
        .submit {
            width: 100%;
            min-height: 5rem;
            border: 0;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--brand-primary) 0%, var(--brand-accent) 100%);
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            cursor: pointer;
        }
        .login-hint {
            margin-top: 1.6rem;
            color: var(--brand-muted);
            font-size: 1.3rem;
        }
        .login-links {
            margin-top: 2rem;
        }
        .login-links a {
            color: var(--brand-accent);
            font-weight: 700;
            text-decoration: none;
        }
        @media (max-width: 900px) {
            .login-shell {
                grid-template-columns: 1fr;
            }
            .login-hero,
            .login-panel {
                padding: 3rem 2.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <section class="login-hero">
            <div class="brand">
                @include('partials.brand-logo', ['variant' => 'footer'])
            </div>
            <h1>Acceso al backoffice</h1>
            <p>Ingresa con tu usuario administrador para gestionar catalogo, pedidos, clientes y la operacion diaria del ecommerce.</p>
        </section>

        <section class="login-panel">
            <small>Ingreso seguro</small>
            <h2>Administrador</h2>
            <p>Usa el correo y la contraseña del usuario administrador para entrar al panel.</p>

            @if(session('status'))
                <div class="feedback feedback--success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="feedback feedback--error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}">
                @csrf
                <div class="field">
                    <label for="email">Correo</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Contraseña</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <button type="submit" class="submit">Ingresar al backoffice</button>
            </form>

            <div class="login-hint">
                Correo administrador inicial: <strong>{{ env('ADMIN_DEFAULT_EMAIL', 'jaruizr74@gmail.com') }}</strong>
            </div>

            <div class="login-links">
                <a href="{{ route('store.home') }}">Volver a la tienda</a>
            </div>
        </section>
    </div>
</body>
</html>
