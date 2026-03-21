@extends('layouts.store')

@section('content')
@php
    $selectedDepartment = old('department');
    $selectedCity = old('city');
@endphp

<main class="main checkout">
    <div class="page-content pt-7 pb-10 mb-10">
        <div class="step-by pr-4 pl-4">
            <h3 class="title title-simple title-step active"><a href="#">1. Checkout</a></h3>
        </div>

        <div class="container mt-7">
            <form method="POST" action="{{ route('store.checkout.store') }}" class="form checkout-form">
                @csrf

                <div class="row mb-9">
                    <div class="col-lg-7 pr-lg-4 mb-4">
                        <div class="cart-summary cart-summary-card mb-4" style="padding: 2.8rem;">
                            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
                                <div>
                                    <h3 class="title billing-title text-uppercase ls-10 pt-1 pb-2 mb-0">Datos del cliente</h3>
                                    <p class="mb-0" style="color: var(--brand-muted);">
                                        Completa tu información y selecciona tu ubicación con el listado oficial DIVIPOLA publicado por el DANE.
                                    </p>
                                </div>
                                <span class="badge" style="padding: .8rem 1.4rem; border-radius: 999px; background: var(--brand-surface-soft); color: var(--brand-primary-strong); font-size: 1.2rem; font-weight: 700;">
                                    Ubicación oficial DANE
                                </span>
                            </div>

                            <div class="row gutter-sm">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="first_name">Nombres *</label>
                                        <input id="first_name" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required>
                                        @error('first_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="last_name">Apellidos *</label>
                                        <input id="last_name" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row gutter-sm">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="email">Email *</label>
                                        <input id="email" class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="phone">Teléfono</label>
                                        <input id="phone" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row gutter-sm">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="department">Departamento *</label>
                                        <select id="department" class="form-control @error('department') is-invalid @enderror" name="department" required>
                                            <option value="">Selecciona un departamento</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department['code'] }}" @selected($selectedDepartment === $department['code'])>
                                                    {{ $department['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('department')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="city">Ciudad *</label>
                                        <select
                                            id="city"
                                            class="form-control @error('city') is-invalid @enderror"
                                            name="city"
                                            data-selected-city="{{ $selectedCity }}"
                                            required
                                            disabled
                                        >
                                            <option value="">Primero elige un departamento</option>
                                        </select>
                                        @error('city')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row gutter-sm">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="payment_method">Método de pago *</label>
                                        <select id="payment_method" class="form-control" name="payment_method">
                                            <option value="epayco" @selected(old('payment_method', 'epayco') === 'epayco')>ePayco - pago online</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="form-group">
                                        <label for="address">Dirección *</label>
                                        <input id="address" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required>
                                        @error('address')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="notes">Notas</label>
                                <textarea id="notes" class="form-control form-control-md @error('notes') is-invalid @enderror" name="notes" cols="30" rows="5">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const departmentSelect = document.getElementById('department');
        const citySelect = document.getElementById('city');
        const citiesByDepartment = @json($citiesByDepartment);

        function renderCities(departmentCode, selectedCity = '') {
            const cities = citiesByDepartment[departmentCode] || [];

            citySelect.innerHTML = '';

            if (!departmentCode || cities.length === 0) {
                citySelect.disabled = true;
                citySelect.innerHTML = '<option value="">Primero elige un departamento</option>';
                return;
            }

            citySelect.disabled = false;
            citySelect.append(new Option('Selecciona una ciudad', ''));

            cities.forEach((city) => {
                const option = new Option(city.name, city.code, false, city.code === selectedCity);
                citySelect.add(option);
            });
        }

        renderCities(departmentSelect.value, citySelect.dataset.selectedCity || '');

        departmentSelect.addEventListener('change', () => {
            renderCities(departmentSelect.value);
        });
    });
</script>
@endsection
