<?php
/**
 * Logout - Destruye la sesión y redirige al inicio
 */
session_start();
session_unset();
session_destroy();

// Limpiar cookie de tema si existe
setcookie('theme', '', time() - 3600, '/');

header("Location: index.php");
exit;
