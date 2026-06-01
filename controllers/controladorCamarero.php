<?php
/**
 * Controlador Camarero - El Último Corral
 */

if ($_SESSION['rol'] !== ROL_CAMARERO && $_SESSION['rol'] !== ROL_ADMIN) {
    $_SESSION['ruta'] = 'login';
    header("Location: " . APP_URL . "/index.php"); exit;
}

$subvista   = $_GET['sv'] ?? 'servicios';
$camareroId = (int)$_SESSION['idU'];
$fechaHoy   = date('Y-m-d');

if (isset($_POST['abrir_servicio'])) {
    $mesaId     = (int)$_POST['mesa_id'];
    $turnoId    = (int)$_POST['turno_id'];
    $comensales = (int)$_POST['comensales'];
    $reservaId  = !empty($_POST['reserva_id']) ? (int)$_POST['reserva_id'] : null;

    $servicioExistente = $restauranteModel->getServicioAbiertoByMesa($mesaId);
    if ($servicioExistente) {
        $_SESSION['admin_msg'] = ['tipo'=>'error','texto'=>'Esa mesa ya tiene un servicio abierto, vaquero.'];
        header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=servicios"); exit;
    }

    $sid = $restauranteModel->abrirServicio($mesaId, $turnoId, $camareroId, $fechaHoy, $comensales, $reservaId);
    if ($sid) {
        if ($reservaId) $restauranteModel->confirmarReserva($reservaId);
        $_SESSION['admin_msg'] = ['tipo'=>'success','texto'=>'Servicio abierto. ¡A trabajar, vaquero!'];
        header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=servicio_activo&sid=$sid"); exit;
    }
}

if (isset($_POST['agregar_pedido'])) {
    $sid    = (int)$_POST['servicio_id'];
    $itemId = (int)$_POST['item_id'];
    $cant   = (int)$_POST['cantidad'];
    $notas  = $_POST['notas'] ?? '';
    $item   = $restauranteModel->getItemPorId($itemId);
    if ($item) {
        $restauranteModel->agregarPedido($sid, $itemId, $cant, (float)$item['precio'], $notas);
    }
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=servicio_activo&sid=$sid"); exit;
}

if (isset($_POST['cancelar_pedido'])) {
    $sid = (int)$_POST['servicio_id'];
    $restauranteModel->cancelarPedido((int)$_POST['pedido_id']);
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=servicio_activo&sid=$sid"); exit;
}

if (isset($_POST['cerrar_servicio'])) {
    $sid      = (int)$_POST['servicio_id'];
    $servicio = $restauranteModel->getServicioActivo($sid);
    $pedidos  = $restauranteModel->getPedidosDeServicio($sid);

    // ── Generar PDF ──
    require_once __DIR__ . '/../models/TicketHelper.php';
    $total      = $restauranteModel->cerrarServicio($sid);
    $ticketPath = TicketHelper::generarTicket(
        array_merge($servicio, ['closed_at' => date('Y-m-d H:i:s')]),
        $pedidos,
        $total
    );

    // Actualizar ruta del PDF en la BD
    if ($ticketPath) {
        // Reutilizamos cerrarServicio solo para guardar el path
        $pdo = (new ReflectionProperty($restauranteModel, 'conexion'))->getValue($restauranteModel);
        $stmt = $pdo->prepare("UPDATE servicios SET ticket_pdf = :pdf WHERE id = :id");
        $stmt->execute([':pdf' => $ticketPath, ':id' => $sid]);
    }

    // ── Email al cliente si venía de reserva ──
    if ($servicio && $servicio['reserva_id']) {
        $reserva = $restauranteModel->getReservaPorId($servicio['reserva_id']);
        if ($reserva) {
            EmailHelper::enviarFactura(
                $reserva['cliente_email'],
                explode(' ', $reserva['cliente'])[0],
                $servicio, $pedidos, $total
            );
        }
    }

    $_SESSION['admin_msg'] = ['tipo'=>'success','texto'=>"Servicio cerrado. Total cobrado: " . number_format($total, 2) . " €"];
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=servicios"); exit;
}

if (isset($_POST['confirmar_reserva'])) {
    $restauranteModel->confirmarReserva((int)$_POST['reserva_id']);
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=reservas_camarero"); exit;
}

if (isset($_POST['cancelar_reserva'])) {
    $restauranteModel->cancelarReserva((int)$_POST['reserva_id']);
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=reservas_camarero"); exit;
}

$datos = ['subvista' => $subvista];

switch ($subvista) {
    case 'servicios':
        $datos['servicios']    = $restauranteModel->getServiciosPorCamarero($camareroId, $fechaHoy);
        $datos['turnos']       = $restauranteModel->getTurnos();
        $datos['mesas']        = $restauranteModel->getMesasSinServicioAbierto();
        $datos['reservas_hoy'] = $restauranteModel->getReservasPorFecha($fechaHoy);

        // ── Total en tiempo real para servicios abiertos ──
        foreach ($datos['servicios'] as &$srv) {
            if ($srv['estado'] === 'abierto') {
                $pedidos = $restauranteModel->getPedidosDeServicio($srv['id']);
                $srv['total_actual'] = array_sum(array_map(
                    fn($p) => $p['estado'] !== 'cancelado' ? $p['subtotal'] : 0,
                    $pedidos
                ));
            } else {
                $srv['total_actual'] = $srv['total'];
            }
        }
        unset($srv);
        break;

    case 'servicio_activo':
        $sid = (int)($_GET['sid'] ?? 0);
        $datos['servicio']   = $restauranteModel->getServicioActivo($sid);
        $datos['pedidos']    = $restauranteModel->getPedidosDeServicio($sid);
        $datos['categorias'] = $restauranteModel->getCategoriasMenu();
        $datos['items']      = $restauranteModel->getItemsMenu(true);
        $datos['sid']        = $sid;
        break;

    case 'mesas_libres':
        $turnoId  = (int)($_GET['turno_id'] ?? $restauranteModel->getTurnos()[0]['id'] ?? 1);
        $fechaBus = $_GET['fecha'] ?? $fechaHoy;
        $datos['mesas_libres'] = $restauranteModel->getMesasLibres($turnoId, $fechaBus);
        $datos['turnos']       = $restauranteModel->getTurnos();
        $datos['turno_sel']    = $turnoId;
        $datos['fecha_sel']    = $fechaBus;
        break;

    case 'reservas_camarero':
        $datos['reservas'] = $restauranteModel->getReservasPorFecha($fechaHoy);
        break;
}

$datos['admin_msg'] = $_SESSION['admin_msg'] ?? null;
unset($_SESSION['admin_msg']);

require_once __DIR__ . "/../views/waiter/vistaDashboardCamarero.php";