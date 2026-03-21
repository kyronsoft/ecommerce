@php($isWishlisted = in_array($product->id, $wishlistProductIds ?? [], true))
<div class="product-wrap mb-4">
    <div class="product text-center">
        <figure class="product-media">
            <a href="{{ route('store.product.show', $product) }}"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="300" height="338" style="height:260px; object-fit:cover;"></a>
            <div class="product-action-horizontal">
                <form method="POST" action="{{ route('store.cart.add', $product) }}">@csrf<button class="btn-product-icon btn-cart w-icon-cart" title="Agregar"></button></form>
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
            <div class="product-pa-wrapper"><div class="product-price">${{ number_format($product->price, 0, ',', '.') }}</div></div>
        </div>
    </div>
</div>
