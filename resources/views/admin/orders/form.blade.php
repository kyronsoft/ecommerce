@extends('layouts.admin', [
    'breadcrumb' => $order->exists ? 'Editar pedido' : 'Nuevo pedido',
    'pageTitle' => $order->exists ? 'Editar pedido' : 'Crear pedido',
    'pageDescription' => 'Carga pedidos manuales con cliente, estado, resumen de cobro e items vinculados al catalogo.',
])

@php
    $formItems = old('items');

    if ($formItems === null) {
        $formItems = $order->exists
            ? $order->items->map(fn ($item) => [
                'product_id' => $item->product_id,
                'name' => $item->name,
                'sku' => $item->sku,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
            ])->all()
            : [[
                'product_id' => '',
                'name' => '',
                'sku' => '',
                'quantity' => 1,
                'unit_price' => 0,
            ]];
    }
@endphp

@section('page_actions')
    <a href="{{ route('admin.orders.index') }}" class="admin-btn">Volver al listado</a>
    @if($order->exists)
        <a href="{{ route('admin.orders.show', $order) }}" class="admin-btn admin-btn--primary">Ver detalle</a>
    @endif
@endsection

@section('content')
    <form method="POST" action="{{ $order->exists ? route('admin.orders.update', $order) : route('admin.orders.store') }}">
        @csrf
        @if($order->exists)
            @method('PUT')
        @endif

        <div class="admin-grid-2">
            <section class="admin-panel">
                <div class="admin-section-head">
                    <div>
                        <h2>Cabecera del pedido</h2>
                        <p>Selecciona cliente, estado, metodo de pago y costos del pedido.</p>
                    </div>
                </div>

                <div class="admin-field">
                    <label for="customer_id">Cliente</label>
                    <select id="customer_id" name="customer_id" class="form-control" required>
                        <option value="">Selecciona un cliente</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" @selected((string) old('customer_id', $order->customer_id) === (string) $customer->id)>
                                {{ $customer->full_name }} - {{ $customer->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="status">Estado</label>
                        <select id="status" name="status" class="form-control" required>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $order->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="admin-field">
                        <label for="payment_method">Metodo de pago</label>
                        <select id="payment_method" name="payment_method" class="form-control">
                            <option value="">Selecciona un metodo</option>
                            @foreach($paymentMethodOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('payment_method', $order->payment_method) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="admin-field">
                    <label for="shipping">Costo de envio</label>
                    <input id="shipping" type="number" name="shipping" class="form-control js-order-summary-input" min="0" step="0.01" value="{{ old('shipping', $order->shipping ?? 0) }}">
                </div>

                <div class="admin-field">
                    <label for="shipping_address">Direccion de envio</label>
                    <textarea id="shipping_address" name="shipping_address" class="form-control" rows="4">{{ old('shipping_address', $order->shipping_address) }}</textarea>
                    <small>Si la dejas vacia, se usara la direccion registrada del cliente.</small>
                </div>

                <div class="admin-field">
                    <label for="notes">Notas internas</label>
                    <textarea id="notes" name="notes" class="form-control" rows="5">{{ old('notes', $order->notes) }}</textarea>
                </div>
            </section>

            <section class="admin-panel">
                <div class="admin-section-head">
                    <div>
                        <h2>Resumen calculado</h2>
                        <p>El sistema recalcula el total con base en los items del pedido.</p>
                    </div>
                </div>

                <div class="admin-summary-box">
                    <div class="admin-kv">
                        <div class="admin-kv-item">
                            <span class="admin-kv-label">Subtotal productos</span>
                            <span class="admin-kv-value" id="order-subtotal-display">$0</span>
                        </div>
                        <div class="admin-kv-item">
                            <span class="admin-kv-label">Envio</span>
                            <span class="admin-kv-value" id="order-shipping-display">$0</span>
                        </div>
                        <div class="admin-kv-item">
                            <span class="admin-kv-label">Total del pedido</span>
                            <span class="admin-kv-value" id="order-total-display">$0</span>
                        </div>
                    </div>
                </div>

                <div class="admin-separator"></div>

                <p class="admin-helper">
                    Consejo: primero crea categorias y productos, luego registra clientes. Asi podras armar pedidos manuales completos sin tener que escribir lineas a mano.
                </p>
            </section>
        </div>

        <section class="admin-panel" style="margin-top: 1.8rem;">
            <div class="admin-section-head">
                <div>
                    <h2>Items del pedido</h2>
                    <p>Agrega productos del catalogo o ajusta manualmente nombre, SKU y precio si lo necesitas.</p>
                </div>
                <button type="button" class="admin-btn" id="add-order-item">Agregar item</button>
            </div>

            <div class="admin-table-wrap">
                <table class="admin-table admin-order-items">
                    <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Nombre</th>
                        <th>SKU</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="order-items-body">
                    @foreach($formItems as $index => $item)
                        <tr class="order-item-row">
                            <td>
                                <select name="items[{{ $index }}][product_id]" class="form-control js-product-select">
                                    <option value="">Selecciona</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" @selected((string) ($item['product_id'] ?? '') === (string) $product->id)>
                                            {{ $product->name }} ({{ $product->sku }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="items[{{ $index }}][name]" class="form-control js-item-name" value="{{ $item['name'] ?? '' }}"></td>
                            <td><input type="text" name="items[{{ $index }}][sku]" class="form-control js-item-sku" value="{{ $item['sku'] ?? '' }}"></td>
                            <td><input type="number" name="items[{{ $index }}][quantity]" class="form-control js-item-quantity" min="1" step="1" value="{{ $item['quantity'] ?? 1 }}"></td>
                            <td><input type="number" name="items[{{ $index }}][unit_price]" class="form-control js-item-price" min="0" step="0.01" value="{{ $item['unit_price'] ?? 0 }}"></td>
                            <td><span class="admin-row-total">$0</span></td>
                            <td><button type="button" class="admin-link js-remove-row" style="border: 0; background: transparent; padding: 0;">Quitar</button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <div class="admin-actions" style="margin-top: 1.8rem;">
            <button type="submit" class="admin-btn admin-btn--primary">Guardar pedido</button>
            <a href="{{ route('admin.orders.index') }}" class="admin-btn admin-btn--secondary">Cancelar</a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const products = @json(
                $products->map(fn ($product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'price' => (float) $product->price,
                    'stock' => (int) $product->stock,
                ])->values()
            );
            const customers = @json(
                $customers->mapWithKeys(fn ($customer) => [
                    $customer->id => [
                        'address' => collect([$customer->address, $customer->city, $customer->department])->filter()->implode(', '),
                    ],
                ])
            );

            const body = document.getElementById('order-items-body');
            const addButton = document.getElementById('add-order-item');
            const customerSelect = document.getElementById('customer_id');
            const shippingAddress = document.getElementById('shipping_address');
            const shippingInput = document.getElementById('shipping');

            function money(value) {
                return new Intl.NumberFormat('es-CO', {
                    style: 'currency',
                    currency: 'COP',
                    maximumFractionDigits: 0,
                }).format(Number(value || 0));
            }

            function productOptions(selected = '') {
                const base = ['<option value="">Selecciona</option>'];

                products.forEach((product) => {
                    const isSelected = String(selected) === String(product.id) ? 'selected' : '';
                    base.push(`<option value="${product.id}" ${isSelected}>${product.name} (${product.sku})</option>`);
                });

                return base.join('');
            }

            function rowTemplate(index) {
                return `
                    <tr class="order-item-row">
                        <td>
                            <select name="items[${index}][product_id]" class="form-control js-product-select">
                                ${productOptions()}
                            </select>
                        </td>
                        <td><input type="text" name="items[${index}][name]" class="form-control js-item-name"></td>
                        <td><input type="text" name="items[${index}][sku]" class="form-control js-item-sku"></td>
                        <td><input type="number" name="items[${index}][quantity]" class="form-control js-item-quantity" min="1" step="1" value="1"></td>
                        <td><input type="number" name="items[${index}][unit_price]" class="form-control js-item-price" min="0" step="0.01" value="0"></td>
                        <td><span class="admin-row-total">$0</span></td>
                        <td><button type="button" class="admin-link js-remove-row" style="border: 0; background: transparent; padding: 0;">Quitar</button></td>
                    </tr>
                `;
            }

            function reindexRows() {
                Array.from(body.querySelectorAll('.order-item-row')).forEach((row, index) => {
                    row.querySelectorAll('input, select').forEach((input) => {
                        input.name = input.name.replace(/items\[\d+]/, `items[${index}]`);
                    });
                });
            }

            function hydrateFromProduct(row) {
                const select = row.querySelector('.js-product-select');
                const product = products.find((item) => String(item.id) === String(select.value));

                if (!product) {
                    recalcRow(row);
                    return;
                }

                row.querySelector('.js-item-name').value = product.name;
                row.querySelector('.js-item-sku').value = product.sku;

                const priceInput = row.querySelector('.js-item-price');

                if (!Number(priceInput.value)) {
                    priceInput.value = product.price;
                }

                recalcRow(row);
            }

            function recalcRow(row) {
                const quantity = Number(row.querySelector('.js-item-quantity').value || 0);
                const price = Number(row.querySelector('.js-item-price').value || 0);
                const total = quantity * price;
                row.querySelector('.admin-row-total').textContent = money(total);
                recalcSummary();
            }

            function recalcSummary() {
                let subtotal = 0;

                body.querySelectorAll('.order-item-row').forEach((row) => {
                    const quantity = Number(row.querySelector('.js-item-quantity').value || 0);
                    const price = Number(row.querySelector('.js-item-price').value || 0);
                    subtotal += quantity * price;
                });

                const shipping = Number(shippingInput.value || 0);
                const total = subtotal + shipping;

                document.getElementById('order-subtotal-display').textContent = money(subtotal);
                document.getElementById('order-shipping-display').textContent = money(shipping);
                document.getElementById('order-total-display').textContent = money(total);
            }

            function bindRow(row) {
                row.querySelector('.js-product-select').addEventListener('change', () => hydrateFromProduct(row));
                row.querySelector('.js-item-quantity').addEventListener('input', () => recalcRow(row));
                row.querySelector('.js-item-price').addEventListener('input', () => recalcRow(row));
                row.querySelector('.js-remove-row').addEventListener('click', () => {
                    if (body.querySelectorAll('.order-item-row').length === 1) {
                        row.querySelectorAll('input').forEach((input) => {
                            if (input.type === 'number') {
                                input.value = input.classList.contains('js-item-quantity') ? 1 : 0;
                            } else {
                                input.value = '';
                            }
                        });
                        row.querySelector('.js-product-select').value = '';
                        recalcRow(row);
                        return;
                    }

                    row.remove();
                    reindexRows();
                    recalcSummary();
                });

                recalcRow(row);
            }

            addButton.addEventListener('click', () => {
                const index = body.querySelectorAll('.order-item-row').length;
                body.insertAdjacentHTML('beforeend', rowTemplate(index));
                bindRow(body.lastElementChild);
            });

            customerSelect.addEventListener('change', () => {
                if (shippingAddress.value.trim() !== '') {
                    return;
                }

                shippingAddress.value = customers[customerSelect.value]?.address || '';
            });

            body.querySelectorAll('.order-item-row').forEach(bindRow);
            document.querySelectorAll('.js-order-summary-input').forEach((input) => input.addEventListener('input', recalcSummary));
            if (customerSelect.value && shippingAddress.value.trim() === '') {
                shippingAddress.value = customers[customerSelect.value]?.address || '';
            }
            recalcSummary();
        });
    </script>
@endpush
