@extends('layouts.store')
@section('content')
<main class="main checkout">
    <div class="page-content pt-7 pb-10 mb-10">
        <div class="step-by pr-4 pl-4"><h3 class="title title-simple title-step active"><a href="#">1. Checkout</a></h3></div>
        <div class="container mt-7">
            <form method="POST" action="{{ route('store.checkout.store') }}" class="form checkout-form">
                @csrf
                <div class="row mb-9">
                    <div class="col-lg-7 pr-lg-4 mb-4">
                        <h3 class="title billing-title text-uppercase ls-10 pt-1 pb-3 mb-0">Datos del cliente</h3>
                        <div class="row gutter-sm"><div class="col-xs-6"><div class="form-group"><label>Nombres *</label><input class="form-control" name="first_name" required></div></div><div class="col-xs-6"><div class="form-group"><label>Apellidos *</label><input class="form-control" name="last_name" required></div></div></div>
                        <div class="row gutter-sm"><div class="col-xs-6"><div class="form-group"><label>Email *</label><input class="form-control" type="email" name="email" required></div></div><div class="col-xs-6"><div class="form-group"><label>Teléfono</label><input class="form-control" name="phone"></div></div></div>
                        <div class="row gutter-sm"><div class="col-xs-6"><div class="form-group"><label>Ciudad *</label><input class="form-control" name="city" required></div></div><div class="col-xs-6"><div class="form-group"><label>Método de pago *</label><select class="form-control" name="payment_method"><option value="epayco">ePayco - pago online</option></select></div></div></div>
                        <div class="form-group"><label>Dirección *</label><input class="form-control" name="address" required></div>
                        <div class="form-group"><label>Notas</label><textarea class="form-control form-control-md" name="notes" cols="30" rows="5"></textarea></div>
                    </div>
                    <div class="col-lg-5 mb-4 sticky-sidebar-wrapper">
                        <div class="order-summary-wrapper checkout-order-panel sticky-sidebar">
                            <h3 class="title text-uppercase ls-10 checkout-order-title">Tu pedido</h3>
                            <div class="order-summary checkout-order-summary">
                                <table class="order-table checkout-order-table">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            <tr class="checkout-order-item">
                                                <td class="product-name">
                                                    <div class="checkout-order-product">
                                                        <figure class="checkout-order-thumb">
                                                            <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" width="72" height="72">
                                                        </figure>
                                                        <div class="checkout-order-product-copy">
                                                            <span class="checkout-order-product-name">{{ $item['name'] }}</span>
                                                            <span class="checkout-order-qty"><i class="fas fa-times"></i> {{ $item['quantity'] }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="product-total">${{ number_format($item['line_total'], 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="checkout-order-meta">
                                            <td><b>Subtotal</b></td>
                                            <td><b>${{ number_format($subtotal, 0, ',', '.') }}</b></td>
                                        </tr>
                                        <tr class="checkout-order-meta">
                                            <td><b>Impuestos</b></td>
                                            <td><b>${{ number_format($tax, 0, ',', '.') }}</b></td>
                                        </tr>
                                        <tr class="checkout-order-meta">
                                            <td><b>Envío</b></td>
                                            <td>${{ number_format($shipping, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr class="order-total checkout-order-total">
                                            <th><b>Total</b></th>
                                            <td><b>${{ number_format($total, 0, ',', '.') }}</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="checkout-order-actions">
                                    <button type="submit" class="btn btn-dark btn-rounded btn-order">Crear pedido</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
