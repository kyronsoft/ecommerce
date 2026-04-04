@extends('layouts.store')

@section('content')
@push('styles')
<style>
    .entrepreneur-page {
        padding: 3rem 0 6rem;
        background:
            radial-gradient(circle at top left, rgba(235, 164, 104, 0.18), transparent 24%),
            linear-gradient(180deg, #FFFFFF 0%, #FBF1E1 100%);
    }
    .entrepreneur-shell {
        padding: 2.6rem;
        border: 1px solid #E7D4C3;
        border-radius: 3.2rem;
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 24px 42px rgba(87, 43, 26, 0.09);
    }
    .entrepreneur-hero {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(28rem, .85fr);
        gap: 2.2rem;
        align-items: start;
        margin-bottom: 2.4rem;
    }
    .entrepreneur-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        padding: .75rem 1.2rem;
        border-radius: 999px;
        background: #FBF1E1;
        color: #572B1A;
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }
    .entrepreneur-hero h1 {
        margin: 1.2rem 0 .9rem;
        color: #572B1A;
        font-family: "Manrope", sans-serif;
        font-size: clamp(3.4rem, 4.8vw, 5.4rem);
        line-height: .98;
    }
    .entrepreneur-hero p {
        max-width: 72rem;
        margin: 0;
        color: #3A241C;
        font-size: 1.65rem;
        line-height: 1.7;
    }
    .entrepreneur-hero__aside {
        display: grid;
        gap: 1rem;
        padding: 1.8rem;
        border: 1px solid #E7D4C3;
        border-radius: 2.2rem;
        background: linear-gradient(180deg, #FFF8F0 0%, #FBF1E1 100%);
    }
    .entrepreneur-hero__aside strong {
        color: #572B1A;
        font-family: "Manrope", sans-serif;
        font-size: 1.9rem;
    }
    .entrepreneur-hero__aside p {
        font-size: 1.45rem;
        line-height: 1.65;
    }
    .entrepreneur-hero__aside ul {
        display: grid;
        gap: .8rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .entrepreneur-hero__aside li {
        display: flex;
        align-items: center;
        gap: .9rem;
        color: #572B1A;
        font-size: 1.45rem;
        font-weight: 600;
    }
    .entrepreneur-hero__aside i {
        color: #D05F32;
    }
    .entrepreneur-matrix {
        overflow-x: auto;
        padding-bottom: .2rem;
    }
    .entrepreneur-grid {
        display: grid;
        grid-template-columns: 18rem 27rem repeat(3, minmax(15rem, 1fr));
        gap: 1rem;
        min-width: 88rem;
    }
    .entrepreneur-empty {
        min-height: 1px;
    }
    .entrepreneur-plan-name {
        padding: 0 .6rem;
        text-align: center;
        color: #D79A64;
        font-family: "Manrope", sans-serif;
        font-size: 2rem;
        font-weight: 800;
    }
    .entrepreneur-plan-price {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 6.4rem;
        padding: 1rem;
        border-radius: 1.3rem;
        background: #E3AB74;
        color: #FFFFFF;
        font-family: "Manrope", sans-serif;
        font-size: 2rem;
        font-weight: 800;
    }
    .entrepreneur-group,
    .entrepreneur-feature,
    .entrepreneur-cell {
        border-radius: 1.5rem;
        background: #F4F4F2;
    }
    .entrepreneur-group {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.8rem;
        color: #444444;
        font-family: "Manrope", sans-serif;
        font-size: 2rem;
        font-weight: 800;
        text-align: center;
    }
    .entrepreneur-feature {
        display: flex;
        align-items: center;
        padding: 1.6rem 1.8rem;
        color: #121212;
        font-family: "Manrope", sans-serif;
        font-size: 1.55rem;
        font-weight: 700;
        line-height: 1.2;
    }
    .entrepreneur-cell {
        display: grid;
        place-items: center;
        min-height: 5.8rem;
    }
    .entrepreneur-cell i {
        color: #E3AB74;
        font-size: 3rem;
    }
    .entrepreneur-cell--empty::after {
        content: "";
        width: 1.4rem;
        height: 1.4rem;
        border-radius: 50%;
        background: rgba(87, 43, 26, 0.08);
    }
    .entrepreneur-actions {
        display: grid;
        grid-template-columns: 18rem 27rem repeat(3, minmax(15rem, 1fr));
        gap: 1rem;
        margin-top: 1.2rem;
        min-width: 88rem;
    }
    .entrepreneur-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 5rem;
        padding: 1.2rem 1.6rem;
        border-radius: 1.4rem;
        border: 1px solid transparent;
        background: #572B1A;
        color: #FFFFFF;
        font-size: 1.45rem;
        font-weight: 800;
        text-align: center;
        transition: transform .18s ease, background .18s ease, border-color .18s ease;
    }
    .entrepreneur-btn:hover {
        transform: translateY(-1px);
        background: #AB4D29;
        color: #FFFFFF;
    }
    .entrepreneur-btn--soft {
        background: #FBF1E1;
        border-color: #E7D4C3;
        color: #572B1A;
    }
    .entrepreneur-btn--soft:hover {
        background: #FFFFFF;
        color: #572B1A;
    }
    .entrepreneur-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.6rem;
        margin-top: 2rem;
        padding: 1.8rem 2rem;
        border-radius: 2rem;
        background: #FBF1E1;
        border: 1px solid #E7D4C3;
    }
    .entrepreneur-footer__text strong {
        display: block;
        color: #572B1A;
        font-family: "Manrope", sans-serif;
        font-size: 2rem;
        margin-bottom: .4rem;
    }
    .entrepreneur-footer__text p {
        margin: 0;
        color: #3A241C;
        font-size: 1.45rem;
        line-height: 1.65;
    }
    .entrepreneur-mobile-plans {
        display: none;
    }
    .entrepreneur-mobile-card {
        padding: 2rem 1.8rem;
        border: 1px solid #E7D4C3;
        border-radius: 2.2rem;
        background: #FFFFFF;
        box-shadow: 0 18px 30px rgba(87, 43, 26, 0.07);
    }
    .entrepreneur-mobile-card + .entrepreneur-mobile-card {
        margin-top: 1.4rem;
    }
    .entrepreneur-mobile-card__head {
        margin-bottom: 1.4rem;
        text-align: center;
    }
    .entrepreneur-mobile-card__head strong {
        display: block;
        color: #572B1A;
        font-family: "Manrope", sans-serif;
        font-size: 2.3rem;
        margin-bottom: .6rem;
    }
    .entrepreneur-mobile-card__price {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 16rem;
        min-height: 5.4rem;
        padding: 1rem 1.6rem;
        border-radius: 1.4rem;
        background: #E3AB74;
        color: #FFFFFF;
        font-family: "Manrope", sans-serif;
        font-size: 2rem;
        font-weight: 800;
    }
    .entrepreneur-mobile-card ul {
        display: grid;
        gap: .9rem;
        margin: 0 0 1.6rem;
        padding: 0;
        list-style: none;
    }
    .entrepreneur-mobile-card li {
        display: flex;
        align-items: flex-start;
        gap: .9rem;
        color: #3A241C;
        font-size: 1.45rem;
        line-height: 1.5;
    }
    .entrepreneur-mobile-card li i {
        margin-top: .2rem;
        color: #D05F32;
    }
    @media (max-width: 991px) {
        .entrepreneur-shell {
            padding: 1.8rem;
            border-radius: 2.6rem;
        }
        .entrepreneur-hero {
            grid-template-columns: 1fr;
        }
        .entrepreneur-grid,
        .entrepreneur-actions {
            min-width: 82rem;
        }
        .entrepreneur-footer {
            flex-direction: column;
            align-items: stretch;
        }
    }
    @media (max-width: 767px) {
        .entrepreneur-page {
            padding: 2rem 0 4rem;
        }
        .entrepreneur-shell {
            padding: 1.4rem;
        }
        .entrepreneur-header h1,
        .entrepreneur-hero h1 {
            font-size: 3.2rem;
        }
        .entrepreneur-matrix {
            display: none;
        }
        .entrepreneur-mobile-plans {
            display: block;
        }
    }
</style>
@endpush

<main class="main entrepreneur-page">
    <div class="container">
        <section class="entrepreneur-shell">
            <div class="entrepreneur-hero">
                <div>
                    <span class="entrepreneur-hero__eyebrow">Quiero ser emprendedor</span>
                    <h1>Elige el plan ideal para destacar tu tienda</h1>
                    <p>Activa tu presencia dentro del ecommerce con espacios pensados para atraer miradas, impulsar clics y darle visibilidad real a tus productos. Estos tres planes te ayudan a empezar, crecer o liderar la vitrina digital.</p>
                </div>

                <aside class="entrepreneur-hero__aside">
                    <strong>Que incluye esta exhibicion</strong>
                    <p>Selecciona el nivel de visibilidad que mejor acompane tu etapa comercial y empieza a mover tu catalogo con mas alcance.</p>
                    <ul>
                        <li><i class="fas fa-check-circle"></i> Presencia promocional dentro de la home</li>
                        <li><i class="fas fa-check-circle"></i> Activaciones en newsletter y fechas especiales</li>
                        <li><i class="fas fa-check-circle"></i> Mayor recordacion para tu marca</li>
                    </ul>
                </aside>
            </div>

            <div class="entrepreneur-matrix">
                <div class="entrepreneur-grid" aria-label="Tabla de planes para emprendedores">
                    <div class="entrepreneur-empty"></div>
                    <div class="entrepreneur-empty"></div>
                    @foreach ($plans as $plan)
                        <div class="entrepreneur-plan-name">{{ $plan['name'] }}</div>
                    @endforeach

                    <div class="entrepreneur-empty"></div>
                    <div class="entrepreneur-empty"></div>
                    @foreach ($plans as $plan)
                        <div class="entrepreneur-plan-price">{{ $plan['price_label'] }}</div>
                    @endforeach

                    @foreach ($featureGroups as $group)
                        <div class="entrepreneur-group" style="grid-row: span {{ count($group['items']) }};">
                            {{ $group['label'] }}
                        </div>

                        @foreach ($group['items'] as $item)
                            <div class="entrepreneur-feature">{{ $item['label'] }}</div>

                            @foreach ($plans as $plan)
                                <div class="entrepreneur-cell {{ $plan['features'][$item['key']] ? '' : 'entrepreneur-cell--empty' }}">
                                    @if ($plan['features'][$item['key']])
                                        <i class="fas fa-check"></i>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    @endforeach
                </div>

                <div class="entrepreneur-actions">
                    <div class="entrepreneur-empty"></div>
                    <div class="entrepreneur-empty"></div>
                    @foreach ($plans as $plan)
                        <a href="{{ route('store.entrepreneur.apply', $plan['slug']) }}" class="entrepreneur-btn">
                            Elegir {{ ucfirst($plan['slug']) }}
                        </a>
                    @endforeach
                </div>
            </div>

            <section class="entrepreneur-mobile-plans" aria-label="Planes para emprendedores en movil">
                @foreach ($plans as $plan)
                    <article class="entrepreneur-mobile-card">
                        <div class="entrepreneur-mobile-card__head">
                            <strong>{{ $plan['name'] }}</strong>
                            <span class="entrepreneur-mobile-card__price">{{ $plan['price_label'] }}</span>
                        </div>

                        <ul>
                            @foreach ($featureGroups as $group)
                                @foreach ($group['items'] as $item)
                                    @if ($plan['features'][$item['key']])
                                        <li><i class="fas fa-check-circle"></i> {{ $item['label'] }}</li>
                                    @endif
                                @endforeach
                            @endforeach
                        </ul>

                        <a href="{{ route('store.entrepreneur.apply', $plan['slug']) }}" class="entrepreneur-btn">
                            Elegir {{ ucfirst($plan['slug']) }}
                        </a>
                    </article>
                @endforeach
            </section>

            <div class="entrepreneur-footer">
                <div class="entrepreneur-footer__text">
                    <strong>Quieres empezar a exhibir tus productos</strong>
                    <p>Escribenos y te ayudamos a elegir el plan que mejor encaja con tu marca, tu presupuesto y tu momento comercial.</p>
                </div>
                <a href="mailto:soporte@latiendademiabue.com?subject=Quiero%20ser%20emprendedor" class="entrepreneur-btn entrepreneur-btn--soft">
                    Hablar con el equipo
                </a>
            </div>
        </section>
    </div>
</main>
@endsection
