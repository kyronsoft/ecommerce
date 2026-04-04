@extends('layouts.store')

@section('content')
@push('styles')
<style>
    .entrepreneur-apply-page {
        padding: 3rem 0 6rem;
        background:
            radial-gradient(circle at top right, rgba(208, 95, 50, 0.12), transparent 24%),
            linear-gradient(180deg, #FFFFFF 0%, #FBF1E1 100%);
    }
    .entrepreneur-apply-shell {
        display: grid;
        grid-template-columns: minmax(0, .9fr) minmax(0, 1.1fr);
        gap: 2rem;
    }
    .entrepreneur-apply-card {
        padding: 2.2rem;
        border: 1px solid #E7D4C3;
        border-radius: 2.8rem;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 22px 36px rgba(87, 43, 26, 0.08);
    }
    .entrepreneur-apply-badge {
        display: inline-flex;
        align-items: center;
        padding: .7rem 1.1rem;
        border-radius: 999px;
        background: #FBF1E1;
        color: #572B1A;
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
    .entrepreneur-apply-card h1,
    .entrepreneur-apply-card h2 {
        margin: 1.2rem 0 .8rem;
        color: #572B1A;
        font-family: "Manrope", sans-serif;
        line-height: 1.05;
    }
    .entrepreneur-apply-card h1 {
        font-size: clamp(3.2rem, 4vw, 4.8rem);
    }
    .entrepreneur-apply-card h2 {
        font-size: 2.4rem;
    }
    .entrepreneur-apply-card p {
        margin: 0;
        color: #3A241C;
        font-size: 1.5rem;
        line-height: 1.7;
    }
    .entrepreneur-plan-summary {
        display: grid;
        gap: 1.2rem;
        margin-top: 1.6rem;
        padding: 1.8rem;
        border-radius: 2rem;
        background: linear-gradient(180deg, #FFF7EC 0%, #FBF1E1 100%);
        border: 1px solid #E7D4C3;
    }
    .entrepreneur-plan-summary__price {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 5.6rem;
        padding: 1rem 1.8rem;
        border-radius: 1.4rem;
        background: #D05F32;
        color: #FFFFFF;
        font-family: "Manrope", sans-serif;
        font-size: 2rem;
        font-weight: 800;
    }
    .entrepreneur-plan-summary ul,
    .entrepreneur-terms-list {
        margin: 0;
        padding: 0;
        list-style: none;
        display: grid;
        gap: .9rem;
    }
    .entrepreneur-plan-summary li,
    .entrepreneur-terms-list li {
        display: flex;
        gap: .9rem;
        color: #572B1A;
        font-size: 1.45rem;
        line-height: 1.55;
    }
    .entrepreneur-plan-summary li i,
    .entrepreneur-terms-list li i {
        margin-top: .25rem;
        color: #D05F32;
    }
    .entrepreneur-terms-box {
        margin-top: 1.6rem;
        padding: 1.8rem;
        border-radius: 2rem;
        background: #FFFFFF;
        border: 1px solid #E7D4C3;
    }
    .entrepreneur-acceptance {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-top: 1.6rem;
        padding: 1.4rem 1.6rem;
        border: 1px solid #E7D4C3;
        border-radius: 1.8rem;
        background: #FBF1E1;
    }
    .entrepreneur-acceptance input {
        margin-top: .3rem;
    }
    .entrepreneur-form-panel {
        display: grid;
        gap: 1.6rem;
    }
    .entrepreneur-form {
        display: grid;
        gap: 1.4rem;
    }
    .entrepreneur-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.4rem;
    }
    .entrepreneur-field label {
        display: block;
        margin-bottom: .7rem;
        color: #572B1A;
        font-size: 1.4rem;
        font-weight: 700;
    }
    .entrepreneur-field input,
    .entrepreneur-field select,
    .entrepreneur-field textarea {
        width: 100%;
        min-height: 5.4rem;
        padding: 1.2rem 1.5rem;
        border: 1px solid #E7D4C3;
        border-radius: 1.6rem;
        background: #FFFFFF;
        color: #3A241C;
        font-size: 1.45rem;
    }
    .entrepreneur-field textarea {
        min-height: 13rem;
        resize: vertical;
    }
    .entrepreneur-helper {
        color: #8A6A5D;
        font-size: 1.3rem;
    }
    .entrepreneur-locked {
        padding: 1.6rem;
        border-radius: 2rem;
        border: 1px dashed #D8B8A7;
        background: #FFF9F3;
        color: #8A6A5D;
        font-size: 1.45rem;
        line-height: 1.65;
    }
    .entrepreneur-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 5.6rem;
        padding: 1.2rem 2rem;
        border: 0;
        border-radius: 999px;
        background: #D05F32;
        color: #FFFFFF;
        font-size: 1.55rem;
        font-weight: 800;
        cursor: pointer;
        transition: background .18s ease, transform .18s ease;
    }
    .entrepreneur-submit:hover {
        background: #AB4D29;
        transform: translateY(-1px);
    }
    .entrepreneur-hidden {
        display: none;
    }
    .entrepreneur-errors {
        margin-bottom: .4rem;
        padding: 1.4rem 1.6rem;
        border-radius: 1.6rem;
        background: #FFF4F0;
        border: 1px solid rgba(208, 95, 50, 0.28);
        color: #8A2E18;
        font-size: 1.4rem;
    }
    .entrepreneur-errors ul {
        margin: 0;
        padding-left: 1.8rem;
    }
    @media (max-width: 991px) {
        .entrepreneur-apply-shell {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 767px) {
        .entrepreneur-apply-page {
            padding: 2rem 0 4rem;
        }
        .entrepreneur-apply-card {
            padding: 1.6rem;
            border-radius: 2.2rem;
        }
        .entrepreneur-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

<main class="main entrepreneur-apply-page">
    <div class="container">
        <div class="entrepreneur-apply-shell">
            <section class="entrepreneur-apply-card">
                <span class="entrepreneur-apply-badge">Plan emprendedor</span>
                <h1>{{ $plan['name'] }}</h1>
                <p>{{ $plan['headline'] }}</p>

                <div class="entrepreneur-plan-summary">
                    <span class="entrepreneur-plan-summary__price">{{ $plan['price_label'] }}</span>
                    <ul>
                        @foreach ($plan['features'] as $key => $enabled)
                            @continue(! $enabled)
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>
                                    @switch($key)
                                        @case('banner_superior') Banner superior Home @break
                                        @case('banner_inferior') Banner inferior Home @break
                                        @case('popup_salida') Pop Up de salida x 24 horas @break
                                        @case('newsletter') Recomendacion de la semana en newsletter @break
                                        @case('destacados') Zona de destacados en fechas especiales @break
                                        @case('historias') Historias en redes sociales @break
                                    @endswitch
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="entrepreneur-terms-box">
                    <h2>Terminos y condiciones</h2>
                    <ul class="entrepreneur-terms-list">
                        <li><i class="fas fa-check-circle"></i><span>La activacion del plan inicia una vez se confirme el pago en ePayco.</span></li>
                        <li><i class="fas fa-check-circle"></i><span>El material grafico y la informacion comercial deben ser entregados por el emprendedor en tiempos acordados con el equipo.</span></li>
                        <li><i class="fas fa-check-circle"></i><span>La ubicacion de banners, historias o espacios destacados depende de disponibilidad y calendario comercial.</span></li>
                        <li><i class="fas fa-check-circle"></i><span>El pago corresponde a una activacion comercial del plan seleccionado y no garantiza ventas minimas.</span></li>
                        <li><i class="fas fa-check-circle"></i><span>Al continuar aceptas que el equipo de La Tienda de Mi Abue te contacte para coordinar materiales y fechas de publicacion.</span></li>
                    </ul>

                    <label class="entrepreneur-acceptance">
                        <input type="checkbox" id="accept-terms-toggle" name="accept_terms_preview" value="1" @checked(old('accept_terms'))>
                        <span>Acepto los terminos y condiciones del plan {{ $plan['name'] }} y deseo continuar con el registro y el pago.</span>
                    </label>
                </div>
            </section>

            <section class="entrepreneur-apply-card entrepreneur-form-panel">
                <div>
                    <span class="entrepreneur-apply-badge">Registro emprendedor</span>
                    <h2>Completa tus datos para continuar con el pago</h2>
                    <p>Cuando aceptas los terminos se habilita este formulario para registrar la informacion basica de tu emprendimiento y enviarte al checkout de ePayco.</p>
                </div>

                @if ($errors->any())
                    <div class="entrepreneur-errors">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div id="entrepreneur-locked" class="entrepreneur-locked">
                    Acepta los terminos y condiciones para desplegar el formulario de registro y avanzar al pago del plan.
                </div>

                <form method="POST" action="{{ route('store.entrepreneur.apply.store', $plan['slug']) }}" id="entrepreneur-form" class="entrepreneur-form entrepreneur-hidden">
                    @csrf
                    <input type="hidden" name="accept_terms" id="accept_terms_input" value="{{ old('accept_terms') ? '1' : '0' }}">

                    <div class="entrepreneur-grid">
                        <div class="entrepreneur-field">
                            <label for="first_name">Nombres *</label>
                            <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required>
                        </div>
                        <div class="entrepreneur-field">
                            <label for="last_name">Apellidos *</label>
                            <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required>
                        </div>
                    </div>

                    <div class="entrepreneur-grid">
                        <div class="entrepreneur-field">
                            <label for="email">Correo electronico *</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="entrepreneur-field">
                            <label for="phone">Telefono o WhatsApp *</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" required>
                        </div>
                    </div>

                    <div class="entrepreneur-grid">
                        <div class="entrepreneur-field">
                            <label for="store_name">Nombre del emprendimiento *</label>
                            <input id="store_name" name="store_name" type="text" value="{{ old('store_name') }}" required>
                        </div>
                        <div class="entrepreneur-field">
                            <label for="business_category">Categoria principal *</label>
                            <select id="business_category" name="business_category" required>
                                <option value="">Selecciona una categoria</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}" @selected(old('business_category') === $category->name)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="entrepreneur-helper">Usa una categoria ya existente en el marketplace para mantener la exhibicion alineada con el catalogo.</div>
                        </div>
                    </div>

                    <div class="entrepreneur-grid">
                        <div class="entrepreneur-field">
                            <label for="department">Departamento *</label>
                            <select id="department" name="department" required>
                                <option value="">Selecciona un departamento</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department['code'] }}" @selected(old('department') === $department['code'])>{{ $department['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="entrepreneur-field">
                            <label for="city">Ciudad *</label>
                            <select id="city" name="city" required disabled>
                                <option value="">Selecciona una ciudad</option>
                            </select>
                        </div>
                    </div>

                    <div class="entrepreneur-field">
                        <label for="address">Direccion comercial o de contacto *</label>
                        <input id="address" name="address" type="text" value="{{ old('address') }}" required>
                    </div>

                    <div class="entrepreneur-grid">
                        <div class="entrepreneur-field">
                            <label for="instagram">Instagram</label>
                            <input id="instagram" name="instagram" type="text" value="{{ old('instagram') }}" placeholder="@tuemprendimiento">
                        </div>
                        <div class="entrepreneur-field">
                            <label for="website">Sitio web</label>
                            <input id="website" name="website" type="url" value="{{ old('website') }}" placeholder="https://">
                        </div>
                    </div>

                    <div class="entrepreneur-field">
                        <label for="description">Cuentanos brevemente sobre tu marca *</label>
                        <textarea id="description" name="description" required>{{ old('description') }}</textarea>
                        <div class="entrepreneur-helper">Usaremos esta informacion para entender tu propuesta y coordinar mejor la activacion del plan.</div>
                    </div>

                    <button type="submit" class="entrepreneur-submit">
                        Continuar al pago con ePayco
                    </button>
                </form>
            </section>
        </div>
    </div>
</main>

@push('scripts')
<script>
    const termsToggle = document.getElementById('accept-terms-toggle');
    const formSection = document.getElementById('entrepreneur-form');
    const lockedSection = document.getElementById('entrepreneur-locked');
    const acceptTermsInput = document.getElementById('accept_terms_input');
    const departmentSelect = document.getElementById('department');
    const citySelect = document.getElementById('city');
    const citiesByDepartment = @json($citiesByDepartment);
    const oldCity = @json(old('city'));

    function syncAcceptanceState() {
        const accepted = termsToggle.checked;
        formSection.classList.toggle('entrepreneur-hidden', ! accepted);
        lockedSection.classList.toggle('entrepreneur-hidden', accepted);
        acceptTermsInput.value = accepted ? '1' : '0';
    }

    function populateCities() {
        const departmentCode = departmentSelect.value;
        const cities = citiesByDepartment[departmentCode] || [];

        citySelect.innerHTML = '<option value="">Selecciona una ciudad</option>';
        citySelect.disabled = cities.length === 0;

        cities.forEach((city) => {
            const option = document.createElement('option');
            option.value = city.code;
            option.textContent = city.name;

            if (oldCity && oldCity === city.code) {
                option.selected = true;
            }

            citySelect.appendChild(option);
        });
    }

    termsToggle.addEventListener('change', syncAcceptanceState);
    departmentSelect.addEventListener('change', () => {
        populateCities();
        if (! oldCity) {
            citySelect.value = '';
        }
    });

    syncAcceptanceState();
    populateCities();
</script>
@endpush
@endsection
