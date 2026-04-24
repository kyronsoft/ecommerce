@php
    $planName = $plan['name'] ?? 'Plan emprendedor';
    $planPrice = isset($plan['price']) ? '$'.number_format((float) $plan['price'], 0, ',', '.') : 'Segun plan';
    $entrepreneurName = trim(($entrepreneur['first_name'] ?? '').' '.($entrepreneur['last_name'] ?? '')) ?: ($user->name ?? 'Emprendedor');
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenida al backoffice</title>
</head>
<body style="margin:0;padding:0;background:#F8F1E8;font-family:Arial,sans-serif;color:#3A241C;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#F8F1E8;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="680" cellspacing="0" cellpadding="0" style="max-width:680px;width:100%;background:#FFFFFF;border:1px solid #E7D4C3;border-radius:24px;overflow:hidden;">
                    <tr>
                        <td style="padding:32px;background:linear-gradient(135deg,#572B1A,#D05F32);color:#FFFFFF;">
                            <div style="font-size:12px;letter-spacing:.08em;text-transform:uppercase;font-weight:700;opacity:.9;">Backoffice emprendedores</div>
                            <h1 style="margin:10px 0 8px;font-size:34px;line-height:1.1;font-family:Georgia,serif;">Tu acceso ya esta listo</h1>
                            <p style="margin:0;font-size:16px;line-height:1.7;color:rgba(255,255,255,.92);">Confirmamos el pago de <strong>{{ $planName }}</strong> y ahora puedes ingresar a tu espacio de gestion dentro del marketplace.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px;">
                            <p style="margin:0 0 16px;font-size:16px;line-height:1.7;">Hola {{ $entrepreneurName }},</p>
                            <p style="margin:0 0 18px;font-size:16px;line-height:1.7;">Tu solicitud para <strong>{{ $store->name }}</strong> fue aprobada. Adjuntamos el documento de condiciones esenciales para vendedores y abajo encuentras las credenciales iniciales para ingresar al backoffice.</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:20px 0;background:#FBF1E1;border:1px solid #E7D4C3;border-radius:18px;">
                                <tr>
                                    <td style="padding:22px;">
                                        <div style="font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#8A6A5D;margin-bottom:10px;">Resumen</div>
                                        <div style="font-size:16px;line-height:1.8;"><strong>Plan:</strong> {{ $planName }}</div>
                                        <div style="font-size:16px;line-height:1.8;"><strong>Valor:</strong> {{ $planPrice }}</div>
                                        <div style="font-size:16px;line-height:1.8;"><strong>Solicitud:</strong> {{ $transaction->order_ref }}</div>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:20px 0;background:#FFF8F3;border:1px solid #E7D4C3;border-radius:18px;">
                                <tr>
                                    <td style="padding:22px;">
                                        <div style="font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#8A6A5D;margin-bottom:10px;">Credenciales de acceso</div>
                                        <div style="font-size:16px;line-height:1.8;"><strong>Usuario:</strong> {{ $user->email }}</div>
                                        <div style="font-size:16px;line-height:1.8;"><strong>Contrasena temporal:</strong> {{ $plainPassword }}</div>
                                        <div style="font-size:14px;line-height:1.7;color:#8A6A5D;margin-top:10px;">Te recomendamos cambiar esta contrasena cuando ingreses por primera vez.</div>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" style="margin:24px 0 20px;">
                                <tr>
                                    <td>
                                        <a href="{{ $backofficeUrl }}" style="display:inline-block;padding:14px 24px;border-radius:999px;background:#D05F32;color:#FFFFFF;text-decoration:none;font-size:15px;font-weight:700;">Ingresar al backoffice</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0;font-size:15px;line-height:1.7;">Si tienes dudas sobre el documento adjunto o sobre la activacion comercial de tu plan, puedes responder a este mismo correo.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
