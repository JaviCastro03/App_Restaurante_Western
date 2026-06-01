<?php
require_once "../../models/Config.php";
require_once "../../models/ConexionDB.php";
require_once "../../models/UsuarioDDBB.php";

session_start();
$usuarioModel = new UsuarioDDBB();
$token = trim($_GET['token'] ?? '');
$ok    = false;

if ($token) {
    $ok = $usuarioModel->verificarTokenRegistro($token);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Verificación — El Último Corral</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Lora:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
<style>
  :root { --gold:#c9a84c; --brown:#8b4513; --dark:#1a0a00; --cream:#f5ead8; }
  * { margin:0;padding:0;box-sizing:border-box; }
  body {
    min-height:100vh; display:flex; align-items:center; justify-content:center;
    background:var(--dark);
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c9a84c' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    font-family:'Lora',serif;
  }
  .card {
    background:var(--cream);
    border:3px solid var(--gold);
    border-radius:8px;
    padding:60px 48px;
    max-width:480px;
    width:90%;
    text-align:center;
    position:relative;
    box-shadow:0 0 60px rgba(201,168,76,.2), inset 0 0 40px rgba(0,0,0,.05);
  }
  .card::before {
    content:'';
    position:absolute; inset:-6px; border:1px solid rgba(201,168,76,.3);
    border-radius:10px; pointer-events:none;
  }
  .icon  { font-size:72px; margin-bottom:20px; display:block; }
  h1     { font-family:'Cinzel',serif; color:var(--brown); font-size:26px; margin-bottom:16px; }
  p      { color:#5a3a1a; line-height:1.7; margin-bottom:24px; }
  a.btn  {
    display:inline-block; background:var(--brown); color:var(--cream);
    font-family:'Cinzel',serif; letter-spacing:2px; padding:14px 36px;
    border-radius:4px; text-decoration:none; font-size:14px;
    transition:background .2s;
  }
  a.btn:hover { background:#6b3410; }
  .logo { font-family:'Cinzel',serif; color:var(--gold); font-size:13px; letter-spacing:3px; margin-bottom:32px; display:block; opacity:.7; }
</style>
</head>
<body>
<div class="card">
  <span class="logo">★ EL ÚLTIMO CORRAL ★</span>
  <?php if ($ok): ?>
    <span class="icon">🤠</span>
    <h1>¡Bienvenido al Corral!</h1>
    <p>Tu cuenta ha sido verificada con éxito. Ya puedes acceder al saloon y hacer tus reservas.</p>
      <a href="<?= APP_URL ?>" class="btn">✦ Ir al Saloon ✦</a>

  <?php else: ?>
    <span class="icon">💀</span>
    <h1>Enlace Inválido</h1>
    <p>Este enlace de verificación no es válido o ya ha sido utilizado. Si crees que es un error, vuelve a registrarte.</p>
    <a href="../../index.php" class="btn">✦ Volver ✦</a>
  <?php endif; ?>
</div>
</body>
</html>
