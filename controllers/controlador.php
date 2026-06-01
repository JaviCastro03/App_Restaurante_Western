<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../models/Config.php";
require_once __DIR__ . "/../models/ConexionDB.php";
require_once __DIR__ . "/../models/UsuarioDDBB.php";
require_once __DIR__ . "/../models/RestauranteDDBB.php";
require_once __DIR__ . "/../models/EmailHelper.php";

session_start();

// ── Idioma ──────────────────────────────────────────────────────
if (isset($_GET['lang']) && in_array($_GET['lang'], ['es','en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}
if (!isset($_SESSION['lang'])) $_SESSION['lang'] = 'es';

// ── Tema ────────────────────────────────────────────────────────
if (isset($_GET['theme'])) {
    $_SESSION['theme'] = $_GET['theme'] === 'dark' ? 'dark' : 'light';
}
if (!isset($_SESSION['theme'])) $_SESSION['theme'] = 'dark';

// ── Modelos ─────────────────────────────────────────────────────
$usuarioModel     = new UsuarioDDBB();
$restauranteModel = new RestauranteDDBB();
$config           = $restauranteModel->getConfiguracion();

// ── Ruta por defecto ─────────────────────────────────────────────
if (!isset($_SESSION['ruta'])) $_SESSION['ruta'] = 'landing';

// ── Navegación POST ──────────────────────────────────────────────
if (isset($_POST['ir_landing']))   $_SESSION['ruta'] = 'landing';
if (isset($_POST['ir_login']))     $_SESSION['ruta'] = 'login';
if (isset($_POST['ir_registro']))  $_SESSION['ruta'] = 'registro';
if (isset($_POST['ir_menu']))      $_SESSION['ruta'] = 'menu_digital';
if (isset($_POST['ir_dashboard'])) {
    $_SESSION['ruta'] = isset($_SESSION['idU']) ? 'dashboard' : 'login';
}

// ── Navegación GET ───────────────────────────────────────────────
if (!empty($_GET['ruta'])) $_SESSION['ruta'] = $_GET['ruta'];

$ruta = $_SESSION['ruta'];

// ════════════════════════════════════════════════════════════════
//  LANDING
// ════════════════════════════════════════════════════════════════
if ($ruta === 'landing') {
    require_once __DIR__ . "/../views/vistaLanding.php";
    exit;
}

// ════════════════════════════════════════════════════════════════
//  MENÚ DIGITAL
// ════════════════════════════════════════════════════════════════
if ($ruta === 'menu_digital') {
    $categorias = $restauranteModel->getCategoriasMenu();
    $items      = $restauranteModel->getItemsMenu(true);
    require_once __DIR__ . "/../views/vistaMenuDigital.php";
    exit;
}

// ════════════════════════════════════════════════════════════════
//  LOGIN
// ════════════════════════════════════════════════════════════════
if ($ruta === 'login') {

    if (isset($_POST['iniciarSesion'])) {
        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password']  ?? '';

        if (!$usuarioModel->emailExiste($email)) {
            $_SESSION['mensaje'] = ['tipo'=>'error','texto'=>'Credenciales incorrectas, forastero.'];
            header("Location: " . APP_URL . "/index.php"); exit;
        }
        $hashDB = $usuarioModel->getPassword($email);
        if (!password_verify($pass, $hashDB)) {
            $_SESSION['mensaje'] = ['tipo'=>'error','texto'=>'Credenciales incorrectas, forastero.'];
            header("Location: " . APP_URL . "/index.php"); exit;
        }
        $rol = $usuarioModel->getRol($email);
        if ($rol === ROL_SIN_VERIFICAR) {
            $_SESSION['mensaje'] = ['tipo'=>'warning','texto'=>'Debes verificar tu correo antes de entrar al saloon.'];
            header("Location: " . APP_URL . "/index.php"); exit;
        }

        $_SESSION['idU']    = $usuarioModel->getIdUsuario($email);
        $_SESSION['nombre'] = $usuarioModel->getNombreUsuario($email);
        $_SESSION['email']  = $email;
        $_SESSION['rol']    = $rol;
        $_SESSION['ruta']   = 'dashboard';
        header("Location: " . APP_URL . "/index.php"); exit;
    }

    require_once __DIR__ . "/../views/vistaLogin.php";
    exit;
}

// ════════════════════════════════════════════════════════════════
//  REGISTRO
// ════════════════════════════════════════════════════════════════
if ($ruta === 'registro') {

    if (isset($_POST['registrar'])) {
        $email    = trim($_POST['email']     ?? '');
        $password = $_POST['password']       ?? '';
        $cpass    = $_POST['cpassword']      ?? '';
        $dni      = trim($_POST['dni']       ?? '');
        $nombre   = trim($_POST['nombre']    ?? '');
        $ape      = trim($_POST['apellidos'] ?? '');
        $tel      = trim($_POST['telefono']  ?? '');
        $prov     = trim($_POST['provincia'] ?? '');
        $mun      = trim($_POST['municipio'] ?? '');
        $via      = trim($_POST['via']       ?? '');
        $numero   = trim($_POST['numero']    ?? '');

        if ($usuarioModel->emailExiste($email)) {
            $_SESSION['mensaje'] = ['tipo'=>'error','texto'=>'Ese correo ya existe en nuestros registros.'];
            header("Location: " . APP_URL . "/index.php"); exit;
        }
        if (!UsuarioDDBB::comprobarPassword($password, $cpass)) {
            $_SESSION['mensaje'] = ['tipo'=>'error','texto'=>'Las contraseñas no coinciden, vaquero.'];
            header("Location: " . APP_URL . "/index.php"); exit;
        }

        $hash  = password_hash($password, PASSWORD_DEFAULT);
        $token = bin2hex(random_bytes(32));

        if ($usuarioModel->insertarUsuario($dni, $nombre, $ape, $email, $hash, ROL_SIN_VERIFICAR, $tel, $prov, $mun, $via, $numero)) {            $usuarioModel->setTokenVerificacion($email, $token);
            if (EmailHelper::enviarVerificacion($email, $token)) {
                $_SESSION['mensaje'] = ['tipo'=>'success','texto'=>'¡Bienvenido al corral! Revisa tu correo para verificar la cuenta.'];
            } else {
                $_SESSION['mensaje'] = ['tipo'=>'warning','texto'=>'Cuenta creada, pero no pudimos enviar el correo.'];
            }
        } else {
            $_SESSION['mensaje'] = ['tipo'=>'error','texto'=>'Error al registrar. Inténtalo de nuevo.'];
        }
        header("Location: " . APP_URL . "/index.php"); exit;
    }

    require_once __DIR__ . "/../views/vistaRegistro.php";
    exit;
}

// ════════════════════════════════════════════════════════════════
//  DASHBOARD
// ════════════════════════════════════════════════════════════════
if ($ruta === 'dashboard') {
    if (!isset($_SESSION['idU'])) {
        $_SESSION['ruta'] = 'login';
        header("Location: " . APP_URL . "/index.php"); exit;
    }
    $rol = $_SESSION['rol'] ?? $usuarioModel->getRol($_SESSION['email']);
    switch ($rol) {
        case ROL_ADMIN:
            require_once __DIR__ . "/controladorAdmin.php";
            break;
        case ROL_CAMARERO:
            require_once __DIR__ . "/controladorCamarero.php";
            break;
        case ROL_CLIENTE:
            require_once __DIR__ . "/controladorCliente.php";
            break;
        default:
            $_SESSION['ruta'] = 'login';
            header("Location: " . APP_URL . "/index.php"); exit;
    }
    exit;
}

// ════════════════════════════════════════════════════════════════
//  LOGOUT
// ════════════════════════════════════════════════════════════════
if ($ruta === 'logout') {
    session_destroy();
    header("Location: " . APP_URL . "/index.php"); exit;
}

// Fallback
$_SESSION['ruta'] = 'landing';
header("Location: " . APP_URL . "/index.php");