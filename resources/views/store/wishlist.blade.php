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
                <table class="shop-table wishlist-table">
                    <thead>
                        <tr>
                            <th class="product-name"><span>Producto</span></th>
                            <th></th>
                            <th class="product-price"><span>Precio</span></th>
                            <th class="product-stock-status"><span>Disponibilidad</span></th>
                            <th class="wishlist-action">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wishlistItems as $product)
                            <tr>
                                <td class="product-thumbnail">
                                    <div class="p-relative">
                                        <a href="{{ route('store.product.show', $product) }}">
                                            <figure>
                                                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="300" height="338">
                                            </figure>
                                        </a>
                                        <form method="POST" action="{{ route('store.wishlist.remove', $product) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-close" aria-label="Quitar de favoritos"><i class="fas fa-times"></i></button>
                                        </form>
                                    </div>
                                </td>
                                <td class="product-name">
                                    <a href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a>
                                    <div class="wishlist-meta">{{ $product->category->name ?? 'General' }}</div>
                                </td>
                                <td class="product-price">
                                    <ins class="new-price">${{ number_format($product->price, 0, ',', '.') }}</ins>
                                </td>
                                <td class="product-stock-status">
                                    @if($product->stock > 0)
                                        <span class="wishlist-in-stock">Disponible</span>
                                    @else
                                        <span class="wishlist-out-stock">Agotado</span>
                                    @endif
                                </td>
                                <td class="wishlist-action">
                                    <div class="d-lg-flex">
                                        <a href="{{ route('store.product.show', $product) }}" class="btn btn-quickview btn-outline btn-default btn-rounded btn-sm mb-2 mb-lg-0">Ver producto</a>
                                        @if($product->stock > 0)
                                            <form method="POST" action="{{ route('store.cart.add', $product) }}" class="ml-lg-2">
                                                @csrf
                                                <button class="btn btn-dark btn-rounded btn-sm btn-cart">Agregar al carrito</button>
                                            </form>
                                        @else
                                            <button class="btn btn-dark btn-rounded btn-sm ml-lg-2 btn-cart" disabled>Sin stock</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

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
                    <div class="row cols-xl-4 cols-md-3 cols-2">
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
