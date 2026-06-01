<?php
/**
 * Controlador Cliente - El Último Corral
 */

if (!isset($_SESSION['idU']) || $_SESSION['rol'] !== ROL_CLIENTE) {
    $_SESSION['ruta'] = 'login';
    header("Location: " . APP_URL . "/index.php"); exit;
}

$subvista  = $_GET['sv'] ?? 'mis_reservas';
$clienteId = (int)$_SESSION['idU'];

// ── Nueva reserva ────────────────────────────────────────────
if (isset($_POST['hacer_reserva'])) {
    $fecha     = $_POST['fecha']        ?? '';
    $turnoId   = (int)$_POST['turno_id'];
    $mesaId    = (int)$_POST['mesa_id'];
    $comensales= (int)$_POST['comensales'];
    $obs       = trim($_POST['observaciones'] ?? '');

    if (!$restauranteModel->esFechaDisponible($fecha)) {
        $_SESSION['admin_msg'] = ['tipo'=>'error','texto'=>'Ese día el restaurante está cerrado.'];
        header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=nueva_reserva"); exit;
    }

    $rid = $restauranteModel->crearReserva($clienteId, $mesaId, $turnoId, $fecha, $comensales, $obs);
    if ($rid) {
        $reserva = $restauranteModel->getReservaPorId($rid);
        EmailHelper::enviarConfirmacionReserva($_SESSION['email'], [
            'fecha'      => $reserva['fecha'],
            'turno'      => $reserva['turno'],
            'hora_inicio'=> $reserva['hora_inicio'],
            'hora_fin'   => $reserva['hora_fin'],
            'mesa'       => $reserva['mesa'],
            'zona'       => $reserva['zona'],
            'comensales' => $comensales,
        ]);
        $_SESSION['admin_msg'] = ['tipo'=>'success','texto'=>'¡Reserva confirmada! Te hemos enviado un correo con los detalles.'];
    } else {
        $_SESSION['admin_msg'] = ['tipo'=>'error','texto'=>'Error al crear la reserva. Inténtalo de nuevo.'];
    }
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=mis_reservas"); exit;
}

// ── Modificar reserva ─────────────────────────────────────────
if (isset($_POST['modificar_reserva'])) {
    $rid = (int)$_POST['reserva_id'];
    $restauranteModel->actualizarReserva(
        $rid, (int)$_POST['mesa_id'], (int)$_POST['turno_id'],
        $_POST['fecha'], (int)$_POST['comensales'], $_POST['observaciones'] ?? ''
    );
    $_SESSION['admin_msg'] = ['tipo'=>'success','texto'=>'Reserva modificada con éxito.'];
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=mis_reservas"); exit;
}

// ── Cancelar reserva ──────────────────────────────────────────
if (isset($_POST['cancelar_reserva'])) {
    $restauranteModel->cancelarReserva((int)$_POST['reserva_id']);
    $_SESSION['admin_msg'] = ['tipo'=>'success','texto'=>'Reserva cancelada.'];
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=mis_reservas"); exit;
}

// ── Actualizar perfil ─────────────────────────────────────────
if (isset($_POST['actualizar_perfil'])) {
    $usuarioModel->actualizarPerfil($clienteId, [
        'nombre'    => trim($_POST['nombre']    ?? ''),
        'apellidos' => trim($_POST['apellidos'] ?? ''),
        'telefono'  => trim($_POST['telefono']  ?? ''),
        'provincia' => trim($_POST['provincia'] ?? ''),
        'municipio' => trim($_POST['municipio'] ?? ''),
        'via'       => trim($_POST['via']       ?? ''),
        'numero'    => trim($_POST['numero']    ?? ''),
    ]);
    $_SESSION['nombre']    = trim($_POST['nombre'] ?? '');
    $_SESSION['admin_msg'] = ['tipo'=>'success','texto'=>'Perfil actualizado.'];
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=mi_perfil"); exit;
}

// ── Datos para la vista ──────────────────────────────────────
$datos = ['subvista' => $subvista];

switch ($subvista) {
    case 'mis_reservas':
        $datos['reservas'] = $restauranteModel->getReservasPorUsuario($clienteId);
        break;
    case 'nueva_reserva':
        $datos['turnos'] = $restauranteModel->getTurnos();
        $datos['zonas']  = $restauranteModel->getZonas();
        $datos['mesas']  = $restauranteModel->getMesas();
        break;
    case 'mi_perfil':
        $datos['usuario'] = $usuarioModel->getUsuarioPorId($clienteId);
        break;
}

$datos['admin_msg'] = $_SESSION['admin_msg'] ?? null;
unset($_SESSION['admin_msg']);

require_once __DIR__ . "/../views/client/vistaDashboardCliente.php";