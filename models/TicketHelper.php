<?php
/**
 * TicketHelper - Generador de tickets PDF con mPDF
 * Estética western para El Último Corral
 */

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/mpdf/mpdf/vendor/autoload.php';

class TicketHelper {

    /**
     * Construye el HTML del ticket (reutilizable)
     */
    private static function buildHtml(array $servicio, array $pedidos, float $total): string {

        $filas = '';
        foreach ($pedidos as $p) {
            if ($p['estado'] === 'cancelado') continue;
            $filas .= '
            <tr>
                <td class="item-name">' . htmlspecialchars($p['nombre_es']) . '</td>
                <td class="center">' . $p['cantidad'] . '</td>
                <td class="right">' . number_format($p['precio_unitario'], 2) . ' €</td>
                <td class="right">' . number_format($p['subtotal'], 2) . ' €</td>
            </tr>';
        }

        $iva      = $total / 1.10 * 0.10;
        $subtotal = $total - $iva;

        return '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        color: #2c1810;
        background: #fff;
    }
    .header {
        background: #2c1810;
        color: #c9a84c;
        text-align: center;
        padding: 18px 10px;
        border-bottom: 3px solid #c9a84c;
    }
    .logo {
        font-size: 20px;
        font-weight: bold;
        letter-spacing: 3px;
        margin-bottom: 4px;
    }
    .tagline {
        font-size: 9px;
        color: #f5ead8;
        font-style: italic;
    }
    .ticket-info {
        background: #f5e6c8;
        padding: 10px 16px;
        border-bottom: 1px solid #c9a84c;
        display: table;
        width: 100%;
    }
    .info-col {
        display: table-cell;
        width: 50%;
        vertical-align: top;
        font-size: 10px;
        line-height: 1.8;
    }
    .info-label {
        color: #8b4513;
        font-weight: bold;
    }
    .section-title {
        background: #8b4513;
        color: #f5ead8;
        padding: 6px 16px;
        font-size: 10px;
        letter-spacing: 2px;
        font-weight: bold;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }
    thead tr {
        background: #3d2010;
        color: #c9a84c;
    }
    thead th {
        padding: 7px 10px;
        font-size: 9px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    tbody tr:nth-child(even) { background: #fdf6ec; }
    tbody tr:nth-child(odd)  { background: #fff; }
    tbody td {
        padding: 7px 10px;
        border-bottom: 1px solid #e8d8b8;
        font-size: 10px;
    }
    .item-name { text-align: left; }
    .center    { text-align: center; }
    .right     { text-align: right; }
    .totals {
        padding: 10px 16px;
        border-top: 2px solid #c9a84c;
        background: #f5e6c8;
    }
    .total-row {
        display: table;
        width: 100%;
        padding: 3px 0;
    }
    .total-label {
        display: table-cell;
        text-align: right;
        padding-right: 20px;
        color: #8b4513;
        font-size: 10px;
        width: 70%;
    }
    .total-value {
        display: table-cell;
        text-align: right;
        font-size: 10px;
        width: 30%;
    }
    .total-final .total-label {
        font-size: 13px;
        font-weight: bold;
        color: #2c1810;
    }
    .total-final .total-value {
        font-size: 15px;
        font-weight: bold;
        color: #8b4513;
    }
    .footer {
        text-align: center;
        padding: 14px 10px;
        background: #2c1810;
        color: #c9a84c;
        font-size: 9px;
        letter-spacing: 1px;
        margin-top: 0;
    }
    .divider {
        border: none;
        border-top: 1px dashed #c9a84c;
        margin: 8px 16px;
    }
    .gracias {
        text-align: center;
        padding: 12px;
        font-size: 12px;
        color: #8b4513;
        font-style: italic;
        background: #fff;
    }
</style>
</head>
<body>

<div class="header">
    <div class="logo">★ EL ÚLTIMO CORRAL ★</div>
    <div class="tagline">C/ Maestro Pascual Baca, 2 · 23680 Alcalá la Real, Jaén</div>
    <div class="tagline" style="margin-top:3px">Tel: 953 000 000 · info@elultimocorral.es</div>
</div>

<div class="ticket-info">
    <div class="info-col">
        <span class="info-label">Ticket Nº:</span> #' . str_pad($servicio['id'], 6, '0', STR_PAD_LEFT) . '<br>
        <span class="info-label">Fecha:</span> ' . date('d/m/Y', strtotime($servicio['fecha'])) . '<br>
        <span class="info-label">Hora:</span> ' . date('H:i', strtotime($servicio['closed_at'] ?? 'now')) . '<br>
    </div>
    <div class="info-col">
        <span class="info-label">Mesa:</span> ' . htmlspecialchars($servicio['mesa'] ?? '') . '<br>
        <span class="info-label">Zona:</span> ' . htmlspecialchars($servicio['zona'] ?? '') . '<br>
        <span class="info-label">Comensales:</span> ' . ($servicio['num_comensales'] ?? '-') . '<br>
    </div>
</div>

<div class="section-title">✦ DETALLE DEL PEDIDO ✦</div>

<table>
    <thead>
        <tr>
            <th style="text-align:left">Artículo</th>
            <th style="text-align:center">Cant.</th>
            <th style="text-align:right">Precio</th>
            <th style="text-align:right">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        ' . $filas . '
    </tbody>
</table>

<div class="totals">
    <div class="total-row">
        <div class="total-label">Base imponible (sin IVA):</div>
        <div class="total-value">' . number_format($subtotal, 2) . ' €</div>
    </div>
    <div class="total-row">
        <div class="total-label">IVA (10%):</div>
        <div class="total-value">' . number_format($iva, 2) . ' €</div>
    </div>
    <hr class="divider">
    <div class="total-row total-final">
        <div class="total-label">TOTAL:</div>
        <div class="total-value">' . number_format($total, 2) . ' €</div>
    </div>
</div>

<div class="gracias">
    ¡Gracias por su visita, forastero! <br>
    <span style="font-size:9px">Esperamos verle pronto en el Corral</span>
</div>

<div class="footer">
    ✦ EL ÚLTIMO CORRAL · Alcalá la Real, Jaén ✦<br>
    CIF: B-12345678 · ' . date('d/m/Y H:i') . '
</div>

</body>
</html>';
    }

    /**
     * Genera el PDF y lo GUARDA en disco (uploads/tickets/)
     * Devuelve la ruta relativa o null si falla
     */
    public static function generarTicket(array $servicio, array $pedidos, float $total): ?string {

        $dirTickets = APP_ROOT . '/uploads/tickets/';
        if (!is_dir($dirTickets)) {
            mkdir($dirTickets, 0755, true);
        }

        $nombreArchivo = 'ticket_' . $servicio['id'] . '_' . date('Ymd_His') . '.pdf';
        $rutaCompleta  = $dirTickets . $nombreArchivo;
        $rutaRelativa  = 'uploads/tickets/' . $nombreArchivo;

        $html = self::buildHtml($servicio, $pedidos, $total);

        try {
            $mpdf = new \Mpdf\Mpdf([
                'mode'          => 'utf-8',
                'format'        => [80, 200],
                'margin_top'    => 0,
                'margin_bottom' => 0,
                'margin_left'   => 0,
                'margin_right'  => 0,
                'tempDir'       => sys_get_temp_dir(),
            ]);
            $mpdf->SetTitle('Ticket #' . $servicio['id'] . ' — El Último Corral');
            $mpdf->WriteHTML($html);
            $mpdf->Output($rutaCompleta, \Mpdf\Output\Destination::FILE);

            return $rutaRelativa;

        } catch (\Exception $e) {
            error_log('TicketHelper error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Genera el PDF y lo DESCARGA directamente al navegador
     */
    public static function generarYDescargar(array $servicio, array $pedidos, float $total): void {

        $html = self::buildHtml($servicio, $pedidos, $total);

        try {
            $mpdf = new \Mpdf\Mpdf([
                'mode'          => 'utf-8',
                'format'        => [80, 200],
                'margin_top'    => 0,
                'margin_bottom' => 0,
                'margin_left'   => 0,
                'margin_right'  => 0,
                'tempDir'       => sys_get_temp_dir(),
            ]);
            $mpdf->SetTitle('Ticket #' . $servicio['id'] . ' — El Último Corral');
            $mpdf->WriteHTML($html);
            $mpdf->Output('ticket_' . $servicio['id'] . '.pdf', \Mpdf\Output\Destination::DOWNLOAD);

        } catch (\Exception $e) {
            error_log('TicketHelper download error: ' . $e->getMessage());
        }
    }
}