<?php
/**
 * Google OAuth Callback
 * Maneja el callback de Google OAuth2 para login/registro social
 */
$root = dirname(__DIR__, 2);

require_once $root . '/models/Config.php';
require_once $root . '/models/ConexionDB.php';
require_once $root . '/models/UsuarioDDBB.php';
require_once $root . '/models/vendor/autoload.php';

session_start();

$client = new Google\Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope('email');
$client->addScope('profile');

if (!isset($_GET['code'])) {
    $authUrl = $client->createAuthUrl();
    header("Location: $authUrl");
    exit;
}

try {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    $oauth    = new Google\Service\Oauth2($client);
    $userInfo = $oauth->userinfo->get();

    $googleId = $userInfo->getId();
    $email    = $userInfo->getEmail();
    $nombre   = $userInfo->getGivenName() ?? explode(' ', $userInfo->getName())[0];
    $apellidos = $userInfo->getFamilyName() ?? '';
    $avatar   = $userInfo->getPicture() ?? '';

    $usuarioModel = new UsuarioDDBB();

    $existente = $usuarioModel->getUsuarioPorGoogleId($googleId);
    if (!$existente && $usuarioModel->emailExiste($email)) {
        $existente = ['email' => $email];
    }

    if (!$existente) {
        $usuarioModel->insertarUsuarioGoogle($googleId, $nombre, $apellidos, $email, $avatar);
    }

    $usuario = $usuarioModel->getUsuarioPorGoogleId($googleId);
    if (!$usuario) {
        $id      = $usuarioModel->getIdUsuario($email);
        $usuario = $usuarioModel->getUsuarioPorId($id);
    }

    if ($usuario && $usuario['activo']) {
        $_SESSION['idU']    = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['email']  = $usuario['email'];
        $_SESSION['rol']    = $usuario['rol'];
        $_SESSION['ruta']   = 'dashboard';
        header("Location: " . APP_URL . "/index.php");
    } else {
        $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Tu cuenta está inactiva. Contacta con nosotros.'];
        header("Location: " . APP_URL . "/index.php");
    }

} catch (Exception $e) {
    error_log("Google OAuth error: " . $e->getMessage());
    $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Error al conectar con Google. Inténtalo de nuevo.'];
    header("Location: " . APP_URL . "/index.php");
}
exit;