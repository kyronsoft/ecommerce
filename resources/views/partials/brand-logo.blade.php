@php
    $variant = $variant ?? 'header';
    $brandLogo = null;
    $brandCandidates = [
        'wolmart/assets/images/la-tienda-de-mi-abue-logo.png',
        'wolmart/assets/images/la-tienda-de-mi-abue-logo.webp',
        'wolmart/assets/images/la-tienda-de-mi-abue-logo.jpg',
        'wolmart/assets/images/la-tienda-de-mi-abue-logo.jpeg',
    ];

    foreach ($brandCandidates as $candidate) {
        if (file_exists(public_path($candidate))) {
            $brandLogo = $candidate;
            break;
        }
    }
@endphp

<span class="brand-logo-shell brand-logo-shell--{{ $variant }}">
    @if ($brandLogo)
        <img
            src="{{ asset($brandLogo) }}"
            alt="La Tienda de Mi Abue"
            class="brand-logo-image brand-logo-image--{{ $variant }}"
        >
    @else
        <span class="brand-logo-fallback brand-logo-fallback--{{ $variant }}" aria-label="La Tienda de Mi Abue">
            <span class="brand-logo-copy">
                <span class="brand-logo-script">La Tienda</span>
                <span class="brand-logo-subtitle">DE MI ABUE</span>
            </span>
        </span>
    @endif
</span>
