<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
/**
 * API: Descarga el ticket PDF de un servicio cerrado
 * Ruta: controllers/api/descargar_ticket.php
 */

$root = dirname(__DIR__, 2);
require_once $root . '/models/Config.php';
require_once $root . '/models/ConexionDB.php';
require_once $root . '/models/RestauranteDDBB.php';
require_once $root . '/models/TicketHelper.php';

session_start();

// ── Seguridad: solo usuarios logueados ────────────────────────
if (!isset($_SESSION['idU'])) {
    http_response_code(403);
    exit('Acceso denegado');
}

$sid = (int)($_GET['id'] ?? 0);
if (!$sid) {
    http_response_code(400);
    exit('ID de servicio no válido');
}

$model    = new RestauranteDDBB();
$servicio = $model->getServicioPorId($sid);
$pedidos  = $model->getPedidosDeServicio($sid);
$total    = (float)($servicio['total'] ?? 0);

if (!$servicio) {
    http_response_code(404);
    exit('Servicio no encontrado');
}

// ── Si ya hay PDF guardado en disco, sírvelo directamente ─────
if (!empty($servicio['ticket_path'])) {
    $rutaPdf = APP_ROOT . '/' . $servicio['ticket_path'];
    if (file_exists($rutaPdf)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="ticket_' . $sid . '.pdf"');
        header('Content-Length: ' . filesize($rutaPdf));
        readfile($rutaPdf);
        exit;
    }
}

// ── Si no existe en disco, generarlo al vuelo y descargar ─────
TicketHelper::generarYDescargar($servicio, $pedidos, $total);
exit;