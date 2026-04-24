@extends('layouts.store')
@push('styles')
<style>
    @media (max-width: 991px) {
        .cart-page-shell .sticky-sidebar {
            position: static !important;
        }
    }
    @media (max-width: 767px) {
        .cart-page-shell .cart-table-wrap {
            overflow-x: auto;
        }
        .cart-page-shell .cart-table {
            min-width: 68rem;
        }
        .cart-page-shell .cart-summary-card {
            border-radius: 2rem;
        }
    }
    @media (max-width: 479px) {
        .cart-page-shell .cart-page-head {
            margin-bottom: 1.4rem;
        }
        .cart-page-shell .cart-table {
            min-width: 60rem;
        }
    }
</style>
@endpush
@section('content')
<main class="main cart cart-page-shell">
    <div class="page-content">
        <div class="container cart-page-container">
            <div class="cart-page-head">
                <h1 class="title title-simple text-left mb-0">Carrito</h1>
                <p class="cart-page-subtitle mb-0">Revisa tus productos, ajusta cantidades y confirma tu compra con total claridad.</p>
            </div>

            <div class="row gutter-lg cart-page-layout mb-10">
                <div class="col-xl-9 col-lg-8 pr-lg-4 mb-6">
                    @if($items->isEmpty())
                        <div class="cart-empty-state">
                            <h3>Tu carrito está vacío</h3>
                            <p>Explora el catálogo, agrega tus productos favoritos y vuelve aquí para completar tu compra.</p>
                            <a href="{{ route('store.shop') }}" class="btn btn-primary btn-rounded">Ir a la tienda</a>
                        </div>
                    @else
                        <div class="cart-table-wrap">
                            <table class="shop-table cart-table">
                                <thead>
                                    <tr>
                                        <th class="product-name" colspan="2"><span>Producto</span></th>
                                        <th class="product-price"><span>Precio</span></th>
                                        <th class="product-quantity"><span>Cantidad</span></th>
                                        <th class="product-subtotal"><span>Subtotal</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $item)
                                        <tr>
                                            <td class="product-thumbnail">
                                                <div class="cart-product-thumb">
                                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" width="120" height="120">
                                                </div>
                                            </td>
                                            <td class="product-name">
                                                <a href="{{ route('store.product.show', $item['slug']) }}">{{ $item['name'] }}</a>
                                                <div class="cart-product-meta">SKU: {{ $item['sku'] }}</div>
                                            </td>
                                            <td class="product-price">
                                                <span class="amount">${{ number_format($item['unit_price'], 0, ',', '.') }}</span>
                                            </td>
                                            <td class="product-quantity">
                                                <form method="POST" action="{{ route('store.cart.update', $item['product_id']) }}" class="cart-quantity-form">
                                                    @csrf
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" class="form-control">
                                                    <button class="btn btn-sm btn-primary btn-cart-update">Actualizar</button>
                                                </form>
                                            </td>
                                            <td class="product-subtotal">
                                                <span class="amount">${{ number_format($item['line_total'], 0, ',', '.') }}</span>
                                                <form method="POST" action="{{ route('store.cart.remove', $item['product_id']) }}" class="cart-remove-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-link cart-remove-link">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="col-xl-3 col-lg-4 sticky-sidebar-wrapper">
                    <div class="sticky-sidebar">
                        <div class="cart-summary cart-summary-card mb-4">
                            <h3 class="cart-checkout-title text-uppercase">Resumen</h3>
                            <ul class="checkout-total">
                                <li><span>Subtotal</span><span>${{ number_format($subtotal, 0, ',', '.') }}</span></li>
                                <li><span>Envío</span><span>${{ number_format($shipping, 0, ',', '.') }}</span></li>
                                <li class="cart-total-row"><span>Total</span><span>${{ number_format($total, 0, ',', '.') }}</span></li>
                            </ul>
                            <a href="{{ route('store.checkout.index') }}" class="btn btn-dark btn-rounded btn-checkout">Ir al checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
