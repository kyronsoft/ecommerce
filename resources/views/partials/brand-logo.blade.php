@php
    $variant = $variant ?? 'header';
    $brandLogo = 'https://latiendademiabue.atl1.cdn.digitaloceanspaces.com/images/la-tienda-de-mi-abue-logo.png';
@endphp

<span class="brand-logo-shell brand-logo-shell--{{ $variant }}">
    <img
        src="{{ $brandLogo }}"
        alt="La Tienda de Mi Abue"
        class="brand-logo-image brand-logo-image--{{ $variant }}"
    >
</span>
