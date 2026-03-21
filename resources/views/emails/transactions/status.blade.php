@php
    $status = $transaction->status;
    $isApproved = $status === 'approved';
    $currency = strtoupper((string) ($transaction->currency ?: 'COP'));
    $usesZeroDecimals = in_array($currency, ['COP'], true);
    $formatMoney = static function ($value) use ($currency, $usesZeroDecimals): string {
        $amount = (float) $value;

        return '$'.number_format($amount, $usesZeroDecimals ? 0 : 2, ',', '.').' '.$currency;
    };

    $palette = $isApproved
        ? [
            'accent' => '#2f7d4d',
            'accentSoft' => '#eaf7ef',
            'badge' => 'Pago aprobado',
            'title' => 'Tu compra fue confirmada',
            'message' => 'Recibimos la confirmacion de tu pago y tu pedido ya quedo registrado correctamente.',
            'ctaLabel' => 'Seguir comprando',
            'ctaUrl' => route('store.shop'),
            'highlight' => 'Ahora podemos continuar con la preparacion de tu pedido con total confianza.',
        ]
        : [
            'accent' => '#b84848',
            'accentSoft' => '#fcecec',
            'badge' => 'Pago rechazado',
            'title' => 'No pudimos confirmar tu pago',
            'message' => 'Recibimos una respuesta rechazada para tu transaccion. Puedes revisar el detalle e intentar nuevamente.',
            'ctaLabel' => 'Intentar nuevamente',
            'ctaUrl' => route('store.checkout.index'),
            'highlight' => 'Tu pedido sigue disponible para que completes la compra con otro medio de pago.',
        ];

    $reason = $transaction->confirmation_payload['x_response_reason_text']
        ?? $transaction->response_payload['x_response_reason_text']
        ?? ($isApproved ? 'Aprobacion confirmada por la pasarela de pago.' : 'La pasarela reporto que la transaccion no fue aprobada.');

    $reference = $transaction->confirmation_payload['x_ref_payco']
        ?? $transaction->confirmation_payload['ref_payco']
        ?? $transaction->response_payload['x_ref_payco']
        ?? $transaction->response_payload['ref_payco']
        ?? 'Sin referencia';

    $paymentMethod = $transaction->confirmation_payload['x_franchise']
        ?? $transaction->response_payload['x_franchise']
        ?? ($order?->payment_method ?: 'ePayco');

    $transactionDate = $transaction->confirmation_payload['x_transaction_date']
        ?? $transaction->response_payload['x_transaction_date']
        ?? optional($transaction->updated_at)->format('d/m/Y H:i');

    $logoSrc = 'https://latiendademiabue.atl1.cdn.digitaloceanspaces.com/images/la-tienda-de-mi-abue-logo.png';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $palette['title'] }}</title>
</head>
<body style="margin:0;padding:0;background:#f7efe9;font-family:Arial,Helvetica,sans-serif;color:#5f382a;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(180deg,#fffaf6 0%,#f3e4d8 100%);margin:0;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:680px;background:#ffffff;border-radius:28px;overflow:hidden;box-shadow:0 24px 60px rgba(95,56,42,0.14);">
                    <tr>
                        <td style="padding:0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(135deg,#5f382a 0%,#d97655 100%);">
                                <tr>
                                    <td style="padding:24px 32px 32px 32px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td valign="top" align="left" style="padding:0 16px 14px 0;">
                                                    <img src="{{ $logoSrc }}" alt="La Tienda de Mi Abue" style="display:block;width:148px;max-width:148px;height:auto;">
                                                </td>
                                                <td valign="top" align="right" style="padding:10px 0 14px 0;">
                                                    <span style="display:inline-block;padding:8px 14px;border-radius:999px;background:rgba(255,255,255,0.16);color:#fff7f1;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">
                                                        {{ $palette['badge'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <h1 style="margin:4px 0 12px 0;font-size:34px;line-height:1.1;color:#ffffff;font-family:Georgia,'Times New Roman',serif;">
                                            {{ $palette['title'] }}
                                        </h1>
                                        <p style="margin:0;max-width:520px;font-size:16px;line-height:1.7;color:#fff1e7;">
                                            {{ $palette['message'] }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:{{ $palette['accentSoft'] }};border:1px solid rgba(95,56,42,0.08);border-radius:22px;">
                                <tr>
                                    <td style="padding:22px 24px;">
                                        <p style="margin:0 0 8px 0;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:{{ $palette['accent'] }};">
                                            Pedido {{ $order?->number ?? $transaction->order_ref }}
                                        </p>
                                        <p style="margin:0;font-size:18px;line-height:1.7;color:#5f382a;">
                                            {{ $palette['highlight'] }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px 12px 32px;">
                            <h2 style="margin:0 0 18px 0;font-size:18px;color:#5f382a;">Resumen de la transaccion</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px 32px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="50%" valign="top" style="padding:0 8px 16px 0;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fffaf6;border:1px solid #ecd8c8;border-radius:20px;">
                                            <tr><td style="padding:20px 22px;"><strong style="display:block;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#a08677;margin-bottom:8px;">Cliente</strong><span style="font-size:16px;line-height:1.7;color:#5f382a;">{{ $customer?->full_name ?: 'Cliente' }}<br>{{ $customer?->email ?: 'Sin correo' }}</span></td></tr>
                                        </table>
                                    </td>
                                    <td width="50%" valign="top" style="padding:0 0 16px 8px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fffaf6;border:1px solid #ecd8c8;border-radius:20px;">
                                            <tr><td style="padding:20px 22px;"><strong style="display:block;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#a08677;margin-bottom:8px;">Total pagado</strong><span style="font-size:24px;font-weight:700;line-height:1.4;color:#5f382a;">{{ $formatMoney($transaction->amount) }}</span></td></tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #ecd8c8;border-radius:20px;">
                                <tr>
                                    <td style="padding:24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding:0 0 14px 0;border-bottom:1px solid #f0dfd1;">
                                                    <span style="font-size:13px;color:#a08677;">Estado</span><br>
                                                    <strong style="font-size:16px;color:{{ $palette['accent'] }};">{{ $palette['badge'] }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;border-bottom:1px solid #f0dfd1;">
                                                    <span style="font-size:13px;color:#a08677;">Referencia ePayco</span><br>
                                                    <strong style="font-size:16px;color:#5f382a;">{{ $reference }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;border-bottom:1px solid #f0dfd1;">
                                                    <span style="font-size:13px;color:#a08677;">Medio de pago</span><br>
                                                    <strong style="font-size:16px;color:#5f382a;">{{ $paymentMethod }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;border-bottom:1px solid #f0dfd1;">
                                                    <span style="font-size:13px;color:#a08677;">Fecha de confirmacion</span><br>
                                                    <strong style="font-size:16px;color:#5f382a;">{{ $transactionDate ?: 'Sin dato' }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0 0 0;">
                                                    <span style="font-size:13px;color:#a08677;">Detalle reportado</span><br>
                                                    <strong style="font-size:16px;line-height:1.7;color:#5f382a;">{{ $reason }}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px 14px 32px;">
                            <h2 style="margin:0;font-size:18px;color:#5f382a;">Detalle del pedido</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px 16px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fffaf6;border:1px solid #ecd8c8;border-radius:20px;overflow:hidden;">
                                @forelse($items as $item)
                                    <tr>
                                        <td style="padding:18px 20px;border-bottom:1px solid #f0dfd1;">
                                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td valign="top">
                                                        <strong style="display:block;font-size:16px;line-height:1.5;color:#5f382a;">{{ $item->name }}</strong>
                                                        <span style="display:block;font-size:13px;color:#a08677;">SKU: {{ $item->sku ?: 'N/D' }}</span>
                                                    </td>
                                                    <td valign="top" align="right">
                                                        <strong style="display:block;font-size:15px;color:#5f382a;">{{ $item->quantity }} x {{ $formatMoney($item->unit_price) }}</strong>
                                                        <span style="display:block;font-size:14px;color:#d97655;">{{ $formatMoney($item->total) }}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td style="padding:18px 20px;font-size:15px;color:#6f5448;">No encontramos items asociados a este pedido.</td>
                                    </tr>
                                @endforelse
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px 32px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="50%" valign="top" style="padding:0 8px 0 0;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #ecd8c8;border-radius:20px;">
                                            <tr>
                                                <td style="padding:20px 22px;">
                                                    <strong style="display:block;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#a08677;margin-bottom:10px;">Entrega</strong>
                                                    <span style="font-size:15px;line-height:1.8;color:#5f382a;">{{ $order?->shipping_address ?: 'Sin direccion registrada' }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td width="50%" valign="top" style="padding:0 0 0 8px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#ffffff;border:1px solid #ecd8c8;border-radius:20px;">
                                            <tr>
                                                <td style="padding:20px 22px;">
                                                    <strong style="display:block;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:#a08677;margin-bottom:10px;">Totales</strong>
                                                    <span style="display:block;font-size:14px;line-height:1.8;color:#6f5448;">Subtotal: {{ $formatMoney($order?->subtotal ?? 0) }}</span>
                                                    <span style="display:block;font-size:14px;line-height:1.8;color:#6f5448;">Envio: {{ $formatMoney($order?->shipping ?? 0) }}</span>
                                                    <span style="display:block;font-size:14px;line-height:1.8;color:#6f5448;">Impuestos: {{ $formatMoney($order?->tax ?? 0) }}</span>
                                                    <strong style="display:block;font-size:18px;line-height:1.8;color:#5f382a;">Total: {{ $formatMoney($order?->total ?? $transaction->amount) }}</strong>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:0 32px 16px 32px;">
                            <a href="{{ $palette['ctaUrl'] }}" style="display:inline-block;padding:15px 28px;border-radius:999px;background:{{ $isApproved ? '#d97655' : '#5f382a' }};color:#ffffff;font-size:15px;font-weight:700;text-decoration:none;">
                                {{ $palette['ctaLabel'] }}
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px 32px 32px;">
                            <p style="margin:0;font-size:13px;line-height:1.8;text-align:center;color:#8d7366;">
                                Este correo fue enviado por La Tienda de Mi Abue para informarte el estado de tu compra.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
