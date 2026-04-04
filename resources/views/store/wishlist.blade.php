@extends('layouts.store')
@section('content')
@php
    $wishlistUrl = route('store.wishlist.index');
    $shareText = urlencode('Mira mis productos favoritos en La Tienda de Mi Abue');
    $shareUrl = urlencode($wishlistUrl);
@endphp
<main class="main wishlist-page">
    <div class="page-header">
        <div class="container">
            <h1 class="page-title mb-0">Favoritos</h1>
        </div>
    </div>

    <nav class="breadcrumb-nav mb-10">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ route('store.home') }}">Inicio</a></li>
                <li>Favoritos</li>
            </ul>
        </div>
    </nav>

    <div class="page-content">
        <div class="container">
            <h3 class="wishlist-title">Mis productos guardados</h3>

            @if($wishlistItems->isEmpty())
                <div class="wishlist-empty">
                    <div class="wishlist-empty-icon"><i class="w-icon-heart"></i></div>
                    <h4>Tu lista de favoritos está vacía</h4>
                    <p>Guarda productos que te interesen para revisarlos luego con calma y agregarlos al carrito cuando quieras.</p>
                    <a href="{{ route('store.shop') }}" class="btn btn-primary btn-rounded">Explorar catálogo</a>
                </div>
            @else
                <div class="wishlist-grid">
                    @foreach($wishlistItems as $product)
                        <div class="wishlist-card">
                            <form method="POST" action="{{ route('store.wishlist.remove', $product) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="wishlist-card__remove" aria-label="Quitar de favoritos"><i class="fas fa-times"></i></button>
                            </form>
                            @include('store.partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>

                <div class="social-links">
                    <label>Compartir:</label>
                    <div class="social-icons social-no-color border-thin">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" class="social-icon social-facebook w-icon-facebook" target="_blank" rel="noopener"></a>
                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}" class="social-icon social-twitter w-icon-twitter" target="_blank" rel="noopener"></a>
                        <a href="https://pinterest.com/pin/create/button/?url={{ $shareUrl }}&description={{ $shareText }}" class="social-icon social-pinterest w-icon-pinterest" target="_blank" rel="noopener"></a>
                        <a href="mailto:?subject=Mis%20favoritos&body={{ $shareText }}%20{{ $shareUrl }}" class="social-icon social-email far fa-envelope"></a>
                        <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" class="social-icon social-whatsapp fab fa-whatsapp" target="_blank" rel="noopener"></a>
                    </div>
                </div>
            @endif

            @if($suggestedProducts->isNotEmpty())
                <section class="wishlist-suggestions">
                    <h2 class="title title-underline mb-4">También te puede gustar</h2>
                    <div class="product-market-grid">
                        @foreach($suggestedProducts as $product)
                            @include('store.partials.product-card', ['product' => $product])
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
</main>
@endsection
