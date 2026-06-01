<?php
/**
 * Controlador Admin - El Último Corral
 */

if ($_SESSION['rol'] !== ROL_ADMIN) {
    $_SESSION['ruta'] = 'login';
    header("Location: " . APP_URL . "/index.php"); exit;
}

$subvista = $_GET['sv'] ?? $_POST['subvista'] ?? 'resumen';

if (isset($_POST['crear_mesa'])) {
    $ok = $restauranteModel->crearMesa($_POST['numero'], (int)$_POST['capacidad'], (int)$_POST['zona_id'], $_POST['descripcion'] ?? '');
    $_SESSION['admin_msg'] = $ok ? ['tipo'=>'success','texto'=>'Mesa creada con éxito.'] : ['tipo'=>'error','texto'=>'Error al crear la mesa.'];
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=mesas"); exit;
}
if (isset($_POST['editar_mesa'])) {
    $ok = $restauranteModel->editarMesa((int)$_POST['id'], $_POST['numero'], (int)$_POST['capacidad'], (int)$_POST['zona_id'], $_POST['descripcion'] ?? '');
    $_SESSION['admin_msg'] = $ok ? ['tipo'=>'success','texto'=>'Mesa actualizada.'] : ['tipo'=>'error','texto'=>'Error al actualizar.'];
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=mesas"); exit;
}
if (isset($_POST['eliminar_mesa'])) {
    $restauranteModel->eliminarMesa((int)$_POST['id']);
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=mesas"); exit;
}
if (isset($_POST['cambiar_estado_mesa'])) {
    $id = (int)$_POST['id'];
    $estado = in_array($_POST['nuevo_estado'], ['libre','ocupada','reservada'])
        ? $_POST['nuevo_estado'] : 'libre';
    $restauranteModel->setEstadoMesa($id, $estado);
    $_SESSION['admin_msg'] = ['tipo'=>'success','texto'=>'Estado de mesa actualizado.'];
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=mesas"); exit;
}

if (isset($_POST['crear_turno'])) {
    $ok = $restauranteModel->crearTurno($_POST['nombre'], $_POST['hora_inicio'], $_POST['hora_fin'], $_POST['tipo']);
    $_SESSION['admin_msg'] = $ok ? ['tipo'=>'success','texto'=>'Turno creado.'] : ['tipo'=>'error','texto'=>'Error al crear turno.'];
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=turnos"); exit;
}
if (isset($_POST['editar_turno'])) {
    $restauranteModel->editarTurno((int)$_POST['id'], $_POST['nombre'], $_POST['hora_inicio'], $_POST['hora_fin'], $_POST['tipo']);
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=turnos"); exit;
}
if (isset($_POST['eliminar_turno'])) {
    $restauranteModel->eliminarTurno((int)$_POST['id']);
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=turnos"); exit;
}

if (isset($_POST['guardar_horario'])) {
    for ($i = 0; $i <= 6; $i++) {
        $restauranteModel->setDiaApertura($i, isset($_POST["dia_$i"]) ? 1 : 0);
    }
    $_SESSION['admin_msg'] = ['tipo'=>'success','texto'=>'Horario semanal guardado.'];
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=horario"); exit;
}
if (isset($_POST['agregar_descanso'])) {
    $restauranteModel->agregarDiaDescanso($_POST['fecha_descanso'], $_POST['motivo_descanso']);
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=horario"); exit;
}
if (isset($_POST['eliminar_descanso'])) {
    $restauranteModel->eliminarDiaDescanso((int)$_POST['id']);
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=horario"); exit;
}

if (isset($_POST['cambiar_rol'])) {
    $usuarioModel->setRol($_POST['email_usuario'], (int)$_POST['nuevo_rol']);
    $_SESSION['admin_msg'] = ['tipo'=>'success','texto'=>'Rol actualizado.'];
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=usuarios"); exit;
}
if (isset($_POST['dar_baja'])) {
    $usuarioModel->setActivo($_POST['email_usuario'], 0);
    $_SESSION['admin_msg'] = ['tipo'=>'success','texto'=>'Usuario dado de baja.'];
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=usuarios"); exit;
}
if (isset($_POST['reactivar_usuario'])) {
    $usuarioModel->reactivarUsuario($_POST['email_usuario']);
    $_SESSION['admin_msg'] = ['tipo'=>'success','texto'=>'Usuario reactivado correctamente.'];
    header("Location: " . APP_URL . "/index.php?ruta=dashboard&sv=usuarios"); exit;
}
$datos = [];
switch ($subvista) {
    case 'mesas':
        $datos['mesas'] = $restauranteModel->getMesas(false);
        $datos['zonas'] = $restauranteModel->getZonas();
        break;
    case 'turnos':
        $datos['turnos'] = $restauranteModel->getTurnos(false);
        break;
    case 'horario':
        $datos['dias']     = $restauranteModel->getDiasApertura();
        $datos['descanso'] = $restauranteModel->getDiasDescanso(date('Y'));
        break;
    case 'usuarios':
        $datos['usuarios'] = $usuarioModel->getTodosUsuarios();
        break;
    case 'estadisticas':
        $periodo = $_GET['periodo'] ?? 'mes';
        $año     = $_GET['anyo']    ?? date('Y');
        $datos['resumen']      = $restauranteModel->getResumenHoy();
        $datos['estadisticas'] = $restauranteModel->getEstadisticasPorPeriodo($periodo, $año);
        $datos['top_platos']   = $restauranteModel->getTopPlatos();
        $datos['periodo']      = $periodo;
        $datos['año']          = $año;
        break;
    case 'reservas':
        $desde = $_GET['desde'] ?? date('Y-m-01');
        $hasta = $_GET['hasta'] ?? date('Y-m-t');
        $datos['reservas'] = $restauranteModel->getTodasReservas($desde, $hasta);
        $datos['desde'] = $desde;
        $datos['hasta'] = $hasta;
        break;
    default:
        $datos['resumen'] = $restauranteModel->getResumenHoy();
        break;
}

$datos['subvista']  = $subvista;
$datos['admin_msg'] = $_SESSION['admin_msg'] ?? null;
unset($_SESSION['admin_msg']);

require_once __DIR__ . "/../views/admin/vistaDashboardAdmin.php";