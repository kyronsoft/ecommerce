@php
    $isWishlisted = in_array($product->id, $wishlistProductIds ?? [], true);
    $hasComparePrice = filled($product->compare_price) && (float) $product->compare_price > (float) $product->price;
@endphp
<div class="product-wrap product-wrap--editorial">
    <div class="product">
        <figure class="product-media">
            <a href="{{ route('store.product.show', $product) }}">
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="320" height="320">
            </a>
            @if($product->is_featured)
                <div class="product-label-group">
                    <span class="product-label product-label--featured">Destacado</span>
                </div>
            @endif
            <div class="product-card__top-actions">
                @if($isWishlisted)
                    <form method="POST" action="{{ route('store.wishlist.remove', $product) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn-product-icon btn-wishlist btn-wishlist-active w-icon-heart-full" title="Quitar de favoritos"></button>
                    </form>
                @else
                    <form method="POST" action="{{ route('store.wishlist.add', $product) }}">
                        @csrf
                        <button class="btn-product-icon btn-wishlist w-icon-heart" title="Agregar a favoritos"></button>
                    </form>
                @endif
            </div>
        </figure>
        <div class="product-details">
            <div class="product-cat"><a href="{{ route('store.shop', ['category' => $product->category->slug ?? null]) }}">{{ $product->category->name ?? 'General' }}</a></div>
            <h3 class="product-name"><a href="{{ route('store.product.show', $product) }}">{{ $product->name }}</a></h3>
            <div class="product-pa-wrapper">
                <div class="product-price-wrap">
                    <div class="product-price">${{ number_format($product->price, 0, ',', '.') }}</div>
                    @if($hasComparePrice)
                        <del class="old-price">${{ number_format($product->compare_price, 0, ',', '.') }}</del>
                    @endif
                </div>
                <form method="POST" action="{{ route('store.cart.add', $product) }}" class="product-card__cart-form">
                    @csrf
                    <button class="btn-product-icon btn-cart w-icon-cart" title="Agregar al carrito"></button>
                </form>
            </div>
            <div class="product-card__meta">
                <span>{{ $product->store->name ?? 'Marketplace' }}</span>
                <span>{{ $product->stock > 0 ? $product->stock.' disponibles' : 'Agotado' }}</span>
            </div>
        </div>
    </div>
</div>
