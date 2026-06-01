<?php
/**
 * EmailHelper - Gestión centralizada de correos electrónicos
 * Utiliza PHPMailer con plantillas HTML con estética western.
 */

require_once __DIR__ . '/../models/Config.php';

class EmailHelper {

    /**
     * Envía un email con plantilla western estilizada.
     */
    public static function enviar(string $destinatario, string $asunto, string $cuerpo): bool {
        try {
            require_once __DIR__ . '/../models/vendor/autoload.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USER;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(MAIL_FROM, MAIL_NAME);
            $mail->addAddress($destinatario);

            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = self::plantilla($asunto, $cuerpo);
            $mail->AltBody = strip_tags($cuerpo);

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Plantilla HTML western para emails
     */
    private static function plantilla(string $asunto, string $cuerpo): string {
        return <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$asunto}</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Lora:ital,wght@0,400;1,400&display=swap');
  body { margin:0; padding:0; background:#1a0a00; font-family:'Lora',Georgia,serif; }
  .wrapper { max-width:640px; margin:0 auto; background:#f5ead8; }
  .header  { background:linear-gradient(135deg,#2c1810,#8b4513); padding:40px 30px; text-align:center; border-bottom:4px solid #c9a84c; }
  .logo    { font-family:'Cinzel',serif; font-size:28px; color:#c9a84c; letter-spacing:3px; text-shadow:2px 2px 4px rgba(0,0,0,0.5); }
  .tagline { color:#f5ead8; font-size:13px; font-style:italic; margin-top:6px; opacity:.85; }
  .body    { padding:40px 36px; color:#3d2010; line-height:1.8; }
  .body h2 { font-family:'Cinzel',serif; color:#8b4513; border-bottom:2px solid #c9a84c; padding-bottom:8px; }
  .badge   { display:inline-block; background:#8b4513; color:#f5ead8; padding:12px 28px; border-radius:4px; font-family:'Cinzel',serif; font-size:14px; letter-spacing:1px; text-decoration:none; margin:18px 0; }
  .divider { border:none; border-top:1px dashed #c9a84c; margin:28px 0; }
  .footer  { background:#2c1810; color:#c9a84c; text-align:center; padding:22px; font-size:12px; letter-spacing:1px; }
  .star    { color:#c9a84c; font-size:20px; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <div class="logo">★ EL ÚLTIMO CORRAL ★</div>
    <div class="tagline">Alcalá la Real · Jaén · Desde el Lejano Oeste</div>
  </div>
  <div class="body">
    {$cuerpo}
  </div>
  <div class="footer">
    <span class="star">✦</span> Calle Rodríguez Acosta · 23680 Alcalá la Real · Jaén <span class="star">✦</span><br>
    info@elultimocorral.es · Tel: 953 000 000
  </div>
</div>
</body>
</html>
HTML;
    }

    // ── Plantillas específicas ────────────────────────────────────

    public static function enviarVerificacion(string $email, string $token): bool {
        $url  = APP_URL . "/controllers/verificar/verificar.php?token={$token}";
        $body = "
            <h2>¡Bienvenido al Corral, forastero!</h2>
            <p>Has dado el primer paso para unirte a la familia de <strong>El Último Corral</strong>. 
            Solo nos falta verificar que eres quien dices ser.</p>
            <p>Pulsa el botón de abajo para activar tu cuenta y comenzar a hacer reservas:</p>
            <p style='text-align:center'>
                <a href='{$url}' class='badge'>✦ VERIFICAR MI CUENTA ✦</a>
            </p>
            <hr class='divider'>
            <p style='font-size:13px;color:#888'>Si no te has registrado en nuestro restaurante, ignora este mensaje.</p>
        ";
        return self::enviar($email, "Verifica tu cuenta — El Último Corral", $body);
    }

    public static function enviarConfirmacionReserva(string $email, array $reserva): bool {
        $body = "
            <h2>Reserva Confirmada 🤠</h2>
            <p>Tu mesa en <strong>El Último Corral</strong> está reservada. Aquí tienes los detalles:</p>
            <table style='width:100%;border-collapse:collapse;margin:16px 0;'>
              <tr style='background:#f5e6c8'><td style='padding:10px;font-weight:bold;color:#8b4513'>📅 Fecha</td><td style='padding:10px'>{$reserva['fecha']}</td></tr>
              <tr><td style='padding:10px;font-weight:bold;color:#8b4513'>⏰ Turno</td><td style='padding:10px'>{$reserva['turno']} ({$reserva['hora_inicio']} - {$reserva['hora_fin']})</td></tr>
              <tr style='background:#f5e6c8'><td style='padding:10px;font-weight:bold;color:#8b4513'>🪑 Mesa</td><td style='padding:10px'>{$reserva['mesa']} — {$reserva['zona']}</td></tr>
              <tr><td style='padding:10px;font-weight:bold;color:#8b4513'>👥 Comensales</td><td style='padding:10px'>{$reserva['comensales']}</td></tr>
            </table>
            <p>Si necesitas modificar o cancelar tu reserva, puedes hacerlo desde tu perfil hasta 2 horas antes.</p>
            <p>¡Te esperamos, vaquero!</p>
        ";
        return self::enviar($email, "✅ Reserva confirmada — El Último Corral", $body);
    }

    public static function enviarFactura(string $email, string $nombre, array $servicio, array $pedidos, float $total): bool {
        $lineas = '';
        foreach ($pedidos as $p) {
            $lineas .= "<tr>
                <td style='padding:8px;border-bottom:1px solid #e8d8b8'>{$p['nombre_es']}</td>
                <td style='padding:8px;border-bottom:1px solid #e8d8b8;text-align:center'>{$p['cantidad']}</td>
                <td style='padding:8px;border-bottom:1px solid #e8d8b8;text-align:right'>" . number_format($p['precio_unitario'],2) . " €</td>
                <td style='padding:8px;border-bottom:1px solid #e8d8b8;text-align:right'>" . number_format($p['subtotal'],2) . " €</td>
            </tr>";
        }
        $body = "
            <h2>Gracias por tu visita, {$nombre}!</h2>
            <p>Ha sido un placer tenerte en <strong>El Último Corral</strong>. Aquí tienes el resumen de tu consumición:</p>
            <table style='width:100%;border-collapse:collapse;margin:16px 0;font-size:14px'>
              <thead>
                <tr style='background:#8b4513;color:#f5ead8'>
                  <th style='padding:10px;text-align:left'>Artículo</th>
                  <th style='padding:10px;text-align:center'>Cant.</th>
                  <th style='padding:10px;text-align:right'>Precio</th>
                  <th style='padding:10px;text-align:right'>Subtotal</th>
                </tr>
              </thead>
              <tbody>{$lineas}</tbody>
              <tfoot>
                <tr style='background:#f5e6c8;font-weight:bold'>
                  <td colspan='3' style='padding:12px;text-align:right;font-family:Cinzel,serif'>TOTAL</td>
                  <td style='padding:12px;text-align:right;font-size:18px;color:#8b4513'>" . number_format($total,2) . " €</td>
                </tr>
              </tfoot>
            </table>
            <p style='font-size:13px;color:#888'>IVA (10%) incluido · Fecha: {$servicio['fecha']}</p>
            <p>¡Hasta la próxima, forastero! 🤠</p>
        ";
        return self::enviar($email, "🧾 Tu factura de El Último Corral", $body);
    }
}
