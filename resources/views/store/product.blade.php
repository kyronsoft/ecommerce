@extends('layouts.store')
@section('content')
@php($isWishlisted = in_array($product->id, $wishlistProductIds ?? [], true))
<main class="main product-detail-page mb-10 pb-1">
    <div class="page-content">
        <div class="container">
            <div class="row gutter-lg">
                <div class="main-content">
                    <div class="product product-single product-detail-card row align-items-center">
                        <div class="col-lg-5 col-md-6 mb-6">
                            <figure class="product-gallery product-gallery-hero"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}"></figure>
                        </div>
                        <div class="col-lg-7 col-md-6 mb-4 mb-md-6 product-detail-content">
                            <h1 class="product-title">{{ $product->name }}</h1>
                            <div class="product-price">${{ number_format($product->price, 0, ',', '.') }}</div>
                            <div class="product-short-desc">{{ $product->short_description }}</div>
                            <p class="mb-4">{{ $product->description }}</p>
                            <ul class="product-meta"><li><span class="label">SKU:</span> {{ $product->sku }}</li><li><span class="label">Categoría:</span> {{ $product->category->name }}</li><li><span class="label">Stock:</span> {{ $product->stock }}</li></ul>
                            <div class="product-single-actions">
                                <form method="POST" action="{{ route('store.cart.add', $product) }}" class="form cart">
                                    @csrf
                                    <div class="quantity-form"><input class="quantity form-control" type="number" min="1" max="99" name="quantity" value="1"></div>
                                    <button class="btn btn-primary btn-rounded">Agregar al carrito</button>
                                </form>
                                @if($isWishlisted)
                                    <form method="POST" action="{{ route('store.wishlist.remove', $product) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline btn-rounded">Quitar de favoritos</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('store.wishlist.add', $product) }}">
                                        @csrf
                                        <button class="btn btn-outline btn-rounded">Guardar en favoritos</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    <section class="vendor-product-section mt-10"><h2 class="title title-center mb-5">Productos relacionados</h2><div class="row cols-2 cols-md-4">
                        @foreach($relatedProducts as $product)
                            @include('store.partials.product-card', ['product' => $product])
                        @endforeach
                    </div></section>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
