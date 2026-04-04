<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente | La Tienda de Mi Abue</title>
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
    <style>
        :root {
            --brand-ink: #3A241C;
            --brand-deep: #572B1A;
            --brand-accent: #D05F32;
            --brand-accent-strong: #AB4D29;
            --brand-surface: #FFFFFF;
            --brand-soft: #FBF1E1;
            --brand-line: #E7D4C3;
            --brand-glow: #EBA468;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            color: var(--brand-ink);
            background:
                radial-gradient(circle at top left, rgba(235, 164, 104, 0.14), transparent 26%),
                radial-gradient(circle at bottom right, rgba(208, 95, 50, 0.10), transparent 30%),
                linear-gradient(180deg, #ffffff 0%, var(--brand-soft) 100%);
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
            font-family: 'Manrope', sans-serif;
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
            width: min(100%, 72rem);
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid rgba(231, 212, 195, 0.9);
            border-radius: 28px;
            box-shadow: 0 24px 60px rgba(87, 43, 26, 0.08);
        }
        .auth-card__body {
            padding: 3.2rem;
        }
        .auth-card__frame {
            padding: 3rem;
            border: 1px solid rgba(231, 212, 195, 0.9);
            border-radius: 22px;
            background: rgba(251, 241, 225, 0.56);
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
            background: rgba(251, 241, 225, 0.9);
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
            font-family: 'Manrope', sans-serif;
            font-size: 3.4rem;
            color: var(--brand-deep);
        }
        .auth-header p {
            margin: 0;
            color: var(--brand-ink);
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.9rem;
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
            background: rgba(255, 255, 255, 0.96);
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
        .feedback--error {
            background: rgba(208, 95, 50, 0.10);
            border: 1px solid rgba(208, 95, 50, 0.18);
            color: var(--brand-ink);
        }
        .feedback__title {
            display: block;
            margin-bottom: 0.8rem;
            font-size: 1.38rem;
        }
        .feedback__list {
            margin: 0;
            padding-left: 1.8rem;
        }
        .feedback__list li + li {
            margin-top: 0.4rem;
        }
        .auth-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.5rem;
        }
        .field {
            margin-bottom: 0.1rem;
        }
        .field--full {
            grid-column: 1 / -1;
        }
        .field label {
            display: block;
            margin-bottom: 0.8rem;
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--brand-ink);
        }
        .field input,
        .field select {
            width: 100%;
            min-height: 5.2rem;
            padding: 1rem 1.5rem;
            border: 1px solid rgba(231, 212, 195, 0.9);
            border-radius: 16px;
            background: #FFFFFF;
            color: var(--brand-ink);
            font-size: 1.45rem;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .field input:focus,
        .field select:focus {
            border-color: rgba(208, 95, 50, 0.6);
            box-shadow: 0 0 0 4px rgba(235, 164, 104, 0.18);
        }
        .auth-note {
            margin: 1.6rem 0 1.8rem;
            color: var(--brand-deep);
            font-size: 1.25rem;
            line-height: 1.7;
        }
        .auth-submit {
            width: 100%;
            min-height: 5.2rem;
            border: 0;
            border-radius: 999px;
            background: var(--brand-accent);
            color: #FFFFFF;
            font-size: 1.5rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 18px 34px rgba(208, 95, 50, 0.18);
        }
        .auth-submit:hover {
            background: var(--brand-accent-strong);
        }
        .auth-footer {
            margin-top: 2rem;
            text-align: center;
            color: var(--brand-deep);
            font-size: 1.35rem;
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
            .auth-grid {
                grid-template-columns: 1fr;
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
                <h2 class="auth-breadcrumb__title">Registro</h2>
                <div class="auth-breadcrumb__trail">
                    <a href="{{ route('store.home') }}">Inicio</a>
                    <span>/</span>
                    <span>Nuevo cliente</span>
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
                                <h1>Crear cuenta</h1>
                                <p>¿Ya tienes cuenta? <a href="{{ route('store.login') }}"><strong>Ingresa aquí</strong></a></p>
                            </header>

                            <div class="auth-separator">
                                <span>Regístrate con tus datos</span>
                            </div>

                            @if($errors->any())
                                <div class="feedback feedback--error">
                                    <span class="feedback__title">No pudimos completar tu registro. Revisa lo siguiente:</span>
                                    <ul class="feedback__list">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('store.register.store') }}">
                                @csrf
                                <div class="auth-grid">
                                    <div class="field">
                                        <label for="first_name">Nombres</label>
                                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Tus nombres" required>
                                    </div>

                                    <div class="field">
                                        <label for="last_name">Apellidos</label>
                                        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Tus apellidos" required>
                                    </div>

                                    <div class="field field--full">
                                        <label for="email">Correo electrónico</label>
                                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="correo@ejemplo.com" required>
                                    </div>

                                    <div class="field">
                                        <label for="phone">Telefono</label>
                                        <input id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="Tu numero de contacto" required>
                                    </div>

                                    <div class="field">
                                        <label for="department_code">Departamento</label>
                                        <select id="department_code" name="department_code" required>
                                            <option value="">Selecciona un departamento</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department['code'] }}" @selected(old('department_code') === $department['code'])>{{ $department['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="field">
                                        <label for="city_code">Ciudad</label>
                                        <select id="city_code" name="city_code" required data-old-value="{{ old('city_code') }}">
                                            <option value="">Selecciona una ciudad</option>
                                        </select>
                                    </div>

                                    <div class="field">
                                        <label for="password">Contraseña</label>
                                        <input id="password" type="password" name="password" placeholder="Crea una contraseña" required>
                                    </div>

                                    <div class="field">
                                        <label for="password_confirmation">Confirmar contraseña</label>
                                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Repite la contraseña" required>
                                    </div>
                                </div>

                                <div class="auth-note">
                                    Tus datos quedarán asociados a un perfil de cliente dentro del marketplace y seguirán separados del backoffice para emprendedores.
                                </div>

                                <button type="submit" class="auth-submit">Crear mi cuenta</button>
                            </form>

                            <div class="auth-footer">
                                <a href="{{ route('store.home') }}">Volver a la tienda</a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script>
        const citiesByDepartment = @json($citiesByDepartment);
        const departmentSelect = document.getElementById('department_code');
        const citySelect = document.getElementById('city_code');

        function renderCities() {
            const departmentCode = departmentSelect.value;
            const cities = citiesByDepartment[departmentCode] || [];
            const oldCity = citySelect.dataset.oldValue;

            citySelect.innerHTML = '<option value="">Selecciona una ciudad</option>';

            cities.forEach(function (city) {
                const option = document.createElement('option');
                option.value = city.code;
                option.textContent = city.name;

                if (oldCity && oldCity === city.code) {
                    option.selected = true;
                }

                citySelect.appendChild(option);
            });

            citySelect.dataset.oldValue = '';
        }

        departmentSelect.addEventListener('change', renderCities);
        renderCities();
    </script>
</body>
</html>
