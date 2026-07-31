<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $paymentContext['flow'] === 'entrepreneur_plan' ? 'Pagar plan emprendedor' : 'Pagar pedido '.$localOrder->number }} (Demo)</title>
    @include('partials.favicons')
    <style>
        body {
            margin: 0;
            font-family: "Quicksand", sans-serif;
            color: #502818;
            background: linear-gradient(180deg, #F8F0E0 0%, #F8E8D0 100%);
        }
        .page {
            max-width: 860px;
            margin: 0 auto;
            padding: 48px 24px 56px;
        }
        .card {
            padding: 32px;
            border: 1px solid rgba(208, 144, 80, 0.34);
            border-radius: 28px;
            background: rgba(248, 240, 224, 0.96);
            box-shadow: 0 18px 42px rgba(208, 104, 64, 0.16);
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
            background: #F8E8D0;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 10px 0;
            font-size: 18px;
        }
        .summary-row strong {
            color: #C86040;
        }
        .demo-badge {
            display: inline-block;
            margin-bottom: 18px;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #7A3B00;
            background: #FBD9A0;
            border: 1px solid rgba(208, 144, 80, 0.5);
        }
        .fake-form {
            margin-top: 20px;
            display: grid;
            gap: 14px;
        }
        .fake-form label {
            display: block;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 6px;
            color: #603018;
        }
        .fake-form input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(208, 144, 80, 0.5);
            font-size: 16px;
            font-family: inherit;
            background: #FFFDF8;
            color: #502818;
        }
        .row2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 22px;
        }
        .btn-pay {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 220px;
            min-height: 56px;
            padding: 0 24px;
            border: 0;
            border-radius: 18px;
            font-size: 18px;
            font-weight: 700;
            color: #F8F0E0;
            background: #2E8B57;
            cursor: pointer;
        }
        .btn-pay:hover {
            background: #279350;
        }
        .btn-reject {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 56px;
            padding: 0 22px;
            border: 1px solid #C86040;
            border-radius: 18px;
            font-size: 16px;
            font-weight: 700;
            color: #C86040;
            background: transparent;
            cursor: pointer;
        }
        .btn-reject:hover {
            background: #F8E8D0;
        }
        .btn-return {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 56px;
            padding: 0 24px;
            border: 1px solid #C86040;
            border-radius: 18px;
            font-size: 18px;
            font-weight: 700;
            color: #C86040;
            text-decoration: none;
        }
        .btn-return:hover {
            background: #F8E8D0;
        }
        .help {
            margin-top: 24px;
            font-size: 15px;
            color: #603018;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="card">
            <span class="demo-badge">Modo demostración</span>
            <h1>{{ $paymentContext['title'] }}</h1>
            <p>{!! str_replace($paymentContext['name'], '<strong>'.$paymentContext['name'].'</strong>', e($paymentContext['intro'])) !!}</p>

            <div class="summary">
                <div class="summary-row"><span>{{ $paymentContext['reference_label'] }}</span><strong>{{ $localOrder->number }}</strong></div>
                <div class="summary-row"><span>{{ $paymentContext['customer_label'] }}</span><strong>{{ $paymentContext['customer_value'] }}</strong></div>
                <div class="summary-row"><span>{{ $paymentContext['total_label'] }}</span><strong>${{ number_format($order['amount'], 0, ',', '.') }} {{ $order['currency'] }}</strong></div>
            </div>

            <form class="fake-form" onsubmit="return false;">
                <div>
                    <label>Número de tarjeta</label>
                    <input type="text" inputmode="numeric" maxlength="19" placeholder="4242 4242 4242 4242" autocomplete="off">
                </div>
                <div class="row2">
                    <div>
                        <label>Vencimiento</label>
                        <input type="text" placeholder="MM/AA" maxlength="5" autocomplete="off">
                    </div>
                    <div>
                        <label>CVC</label>
                        <input type="text" inputmode="numeric" placeholder="123" maxlength="4" autocomplete="off">
                    </div>
                </div>
                <div>
                    <label>Nombre en la tarjeta</label>
                    <input type="text" value="{{ $paymentContext['customer_value'] }}" autocomplete="off">
                </div>
            </form>

            <div class="actions">
                <form method="POST" action="{{ route('epayco.demo.process') }}">
                    @csrf
                    <input type="hidden" name="order_ref" value="{{ $localOrder->number }}">
                    <input type="hidden" name="decision" value="approve">
                    <button type="submit" class="btn-pay">Pagar ahora</button>
                </form>
                <form method="POST" action="{{ route('epayco.demo.process') }}">
                    @csrf
                    <input type="hidden" name="order_ref" value="{{ $localOrder->number }}">
                    <input type="hidden" name="decision" value="reject">
                    <button type="submit" class="btn-reject">Simular pago rechazado</button>
                </form>
                <a href="{{ $paymentContext['secondary_url'] ?? route('store.home') }}" class="btn-return">{{ $paymentContext['secondary_label'] ?? 'Regresar a la tienda' }}</a>
            </div>

            <p class="help">Esta es una pantalla de pago simulada para fines de demostración. Ningún dato de tarjeta se envía ni se procesa realmente; el pedido se marcará como pagado o rechazado según el botón que uses.</p>
        </div>
    </div>
</body>
</html>
