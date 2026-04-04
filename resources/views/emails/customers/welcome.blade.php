@php
    $firstName = $user->first_name ?: $user->name;
    $logoSrc = 'https://latiendademiabue.atl1.cdn.digitaloceanspaces.com/images/la-tienda-de-mi-abue-logo.png';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro confirmado</title>
</head>
<body style="margin:0;padding:0;background:#F8E8D0;font-family:Arial,Helvetica,sans-serif;color:#502818;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(180deg,#F8F0E0 0%,#F8E8D0 100%);margin:0;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:680px;background:#F8F0E0;border-radius:28px;overflow:hidden;box-shadow:0 24px 60px rgba(208,104,64,0.16);">
                    <tr>
                        <td style="padding:0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:linear-gradient(135deg,#502818 0%,#603018 100%);">
                                <tr>
                                    <td style="padding:24px 32px 32px 32px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td valign="top" align="left" style="padding:0 16px 14px 0;">
                                                    <img src="{{ $logoSrc }}" alt="La Tienda de Mi Abue" style="display:block;width:148px;max-width:148px;height:auto;">
                                                </td>
                                                <td valign="top" align="right" style="padding:10px 0 14px 0;">
                                                    <span style="display:inline-block;padding:8px 14px;border-radius:999px;background:rgba(248,232,208,0.16);color:#F8F0E0;font-size:12px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">
                                                        Registro confirmado
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <h1 style="margin:4px 0 12px 0;font-size:34px;line-height:1.1;color:#F8F0E0;font-family:Georgia,'Times New Roman',serif;">
                                            Bienvenido a La Tienda de Mi Abue
                                        </h1>
                                        <p style="margin:0;max-width:520px;font-size:16px;line-height:1.7;color:#F8E8D0;">
                                            Hola {{ $firstName }}, tu cuenta ya fue creada correctamente y desde este momento puedes ingresar y comprar dentro del marketplace.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F8E8D0;border:1px solid #D09050;border-radius:22px;">
                                <tr>
                                    <td style="padding:22px 24px;">
                                        <p style="margin:0 0 8px 0;font-size:13px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:#C86040;">
                                            Cuenta de cliente activa
                                        </p>
                                        <p style="margin:0;font-size:18px;line-height:1.7;color:#502818;">
                                            Tus datos quedaron registrados para comprar con una experiencia más ágil y clara dentro de la plataforma.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px 18px 32px;">
                            <h2 style="margin:0;font-size:18px;color:#502818;">Resumen de tu registro</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px 32px 32px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F8F0E0;border:1px solid #D09050;border-radius:20px;">
                                <tr>
                                    <td style="padding:24px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding:0 0 14px 0;border-bottom:1px solid #D09050;">
                                                    <span style="font-size:13px;color:#603018;">Nombre</span><br>
                                                    <strong style="font-size:16px;color:#502818;">{{ $user->name }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;border-bottom:1px solid #D09050;">
                                                    <span style="font-size:13px;color:#603018;">Correo</span><br>
                                                    <strong style="font-size:16px;color:#502818;">{{ $user->email }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0;border-bottom:1px solid #D09050;">
                                                    <span style="font-size:13px;color:#603018;">Telefono</span><br>
                                                    <strong style="font-size:16px;color:#502818;">{{ $user->phone ?: 'No registrado' }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:14px 0 0 0;">
                                                    <span style="font-size:13px;color:#603018;">Ubicación</span><br>
                                                    <strong style="font-size:16px;line-height:1.7;color:#502818;">{{ collect([$user->city, $user->department])->filter()->implode(', ') ?: 'Sin ubicación registrada' }}</strong>
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
                            <a href="{{ route('store.login') }}" style="display:inline-block;padding:15px 28px;border-radius:999px;background:#C86040;color:#F8F0E0;font-size:15px;font-weight:700;text-decoration:none;">
                                Ingresar a mi cuenta
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 32px 32px 32px;">
                            <p style="margin:0;font-size:13px;line-height:1.8;text-align:center;color:#603018;">
                                Este correo confirma tu registro como cliente en La Tienda de Mi Abue.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
