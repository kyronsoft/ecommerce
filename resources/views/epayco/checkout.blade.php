<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagar pedido {{ $localOrder->number }} con ePayco</title>
    @include('partials.favicons')
    <style>
        body {
            margin: 0;
            font-family: "Quicksand", sans-serif;
            color: #5f382a;
            background: linear-gradient(180deg, #fffdfb 0%, #fff6ef 100%);
        }
        .page {
            max-width: 860px;
            margin: 0 auto;
            padding: 48px 24px 56px;
        }
        .card {
            padding: 32px;
            border: 1px solid rgba(217, 118, 85, 0.18);
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 18px 42px rgba(95, 56, 42, 0.12);
        }
        h1 {
            margin: 0 0 12px;
            font-family: "DM Serif Display", serif;
            font-size: 42px;
            line-height: 1.05;
        }
        p {
            margin: 0 0 18px;
            font-size: 18px;
            line-height: 1.6;
        }
        .summary {
            margin: 24px 0;
            padding: 20px 22px;
            border-radius: 22px;
            background: #fff7f1;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 10px 0;
            font-size: 18px;
        }
        .summary-row strong {
            color: #d97655;
        }
        .help {
            margin-top: 24px;
            font-size: 15px;
            color: #8c6d5d;
        }
        .warning {
            margin: 18px 0 0;
            padding: 16px 18px;
            border-radius: 18px;
            font-size: 16px;
            line-height: 1.55;
            color: #7c2d12;
            background: #fff3e6;
            border: 1px solid rgba(217, 118, 85, 0.28);
        }
        .fallback-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 240px;
            min-height: 56px;
            margin-top: 8px;
            padding: 0 24px;
            border: 0;
            border-radius: 18px;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            background: #744632;
            cursor: pointer;
        }
        .fallback-btn:hover {
            background: #5f382a;
        }
        .epayco-script {
            display: inline-block;
            margin-top: 12px;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <h1>Estamos preparando tu pago</h1>
            <p>Tu pedido <strong>{{ $localOrder->number }}</strong> ya fue registrado como pendiente. Vamos a abrir el checkout de ePayco para que completes el pago.</p>

            <div class="summary">
                <div class="summary-row"><span>Pedido</span><strong>{{ $localOrder->number }}</strong></div>
                <div class="summary-row"><span>Cliente</span><strong>{{ $localOrder->customer?->full_name ?? $localOrder->customer?->email }}</strong></div>
                <div class="summary-row"><span>Total</span><strong>${{ number_format($order['amount'], 0, ',', '.') }} {{ $order['currency'] }}</strong></div>
            </div>

            <button type="button" id="open-epayco" class="fallback-btn">Continuar con ePayco</button>

            @if($rangeError)
                <div class="warning">
                    {{ $rangeError }} Ajusta los límites del comercio en el panel de ePayco o usa un pedido dentro del rango permitido antes de continuar.
                </div>
            @endif

            <div class="epayco-script">
                <script
                    src="https://checkout.epayco.co/checkout.js"
                    class="epayco-button"
                    data-epayco-key="{{ config('epayco.public_key') }}"
                    data-epayco-amount="{{ $order['amount'] }}"
                    data-epayco-tax="{{ $order['tax'] }}"
                    data-epayco-tax-base="{{ $order['tax_base'] }}"
                    data-epayco-name="{{ $order['name'] }}"
                    data-epayco-description="{{ $order['description'] }}"
                    data-epayco-currency="{{ $order['currency'] }}"
                    data-epayco-country="{{ config('epayco.country') }}"
                    data-epayco-test="{{ config('epayco.test') ? 'true' : 'false' }}"
                    data-epayco-external="false"
                    data-epayco-response="{{ $responseUrl }}"
                    data-epayco-confirmation="{{ $confirmationUrl }}"
                    data-epayco-extra1="{{ $order['id'] }}"
                    data-epayco-email-billing="{{ $order['email'] }}">
                </script>
            </div>

            <p class="help">Si el checkout no se abre automáticamente, usa el botón de arriba. Si prefieres volver, puedes cerrar esta página y tu pedido seguirá en estado pendiente.</p>
        </div>
    </div>

    <script>
        function triggerEpaycoButton() {
            if (@json((bool) $rangeError)) {
                return false;
            }

            const generatedButton = document.querySelector('.epayco-button-render, .epayco-button-payment, button.ePayco_checkout_button');
            if (generatedButton) {
                generatedButton.click();
                return true;
            }

            const wrappers = Array.from(document.querySelectorAll('button, a'));
            const fallback = wrappers.find((element) => {
                const text = (element.textContent || '').toLowerCase();
                return text.includes('pagar') || text.includes('epayco') || text.includes('checkout');
            });

            if (fallback && fallback.id !== 'open-epayco') {
                fallback.click();
                return true;
            }

            return false;
        }

        document.getElementById('open-epayco').addEventListener('click', triggerEpaycoButton);

        if (!@json((bool) $rangeError)) {
            let attempts = 0;
            const interval = setInterval(() => {
                attempts += 1;
                if (triggerEpaycoButton() || attempts > 12) {
                    clearInterval(interval);
                }
            }, 600);
        }
    </script>
</body>
</html>
