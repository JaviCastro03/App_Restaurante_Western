<?php
/**
 * API: Mesas Libres
 * Devuelve mesas disponibles para un turno y fecha dados (JSON)
 */
header('Content-Type: application/json; charset=utf-8');

require_once '../../models/Config.php';
require_once '../../models/ConexionDB.php';
require_once '../../models/RestauranteDDBB.php';

session_start();

// Solo usuarios autenticados
if (!isset($_SESSION['idU'])) {
    echo json_encode([]);
    exit;
}

$turnoId = (int)($_GET['turno_id'] ?? 0);
$fecha   = trim($_GET['fecha'] ?? '');

// Validar fecha
if (!$turnoId || !$fecha || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
    echo json_encode([]);
    exit;
}

$model = new RestauranteDDBB();
$mesas = $model->getMesasLibres($turnoId, $fecha);

echo json_encode($mesas, JSON_UNESCAPED_UNICODE);
