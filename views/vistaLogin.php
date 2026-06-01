<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'es' ?>" data-theme="<?= $_SESSION['theme'] ?? 'dark' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_SESSION['lang'] === 'en' ? 'Sign In' : 'Iniciar Sesión' ?> — El Último Corral</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Cinzel+Decorative:wght@700&family=Lora:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/vistaLogin.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🤠</text></svg>">
</head>
<body>
<?php require_once './views/partials/navbar.php'; ?>

<div class="page-wrapper" style="display:flex;align-items:center;justify-content:center;min-height:100vh;padding:var(--space-xl);background:radial-gradient(ellipse at 30% 50%,rgba(139,69,19,.2),transparent 60%),var(--bg-primary)">

    <!-- Particles -->
    <div id="heroParticles" style="position:fixed;inset:0;pointer-events:none;z-index:0"></div>

    <div style="width:100%;max-width:480px;z-index:1">

        <!-- Header -->
        <div style="text-align:center;margin-bottom:var(--space-xl)">
            <div style="font-size:4rem;margin-bottom:var(--space-md)">🤠</div>
            <h1 style="font-size:1.8rem;margin-bottom:8px">
                <?= $_SESSION['lang'] === 'en' ? 'Enter the Saloon' : 'Entrar al Saloon' ?>
            </h1>
            <p style="color:var(--text-muted);font-style:italic">
                <?= $_SESSION['lang'] === 'en' ? 'Welcome back, partner' : 'Bienvenido de vuelta, forastero' ?>
            </p>
        </div>

        <!-- Alert -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?>" data-auto-dismiss="6000">
                <span><?= htmlspecialchars($_SESSION['mensaje']['texto']) ?></span>
                <button class="alert-close" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit">✕</button>
            </div>
            <?php unset($_SESSION['mensaje']); endif; ?>

        <!-- Card -->
        <div class="card card-gold" style="padding:var(--space-xl)">

            <!-- Google Login -->
            <a href="controllers/api/google_auth.php" style="
        display:flex;align-items:center;justify-content:center;gap:12px;
        width:100%;padding:14px;border-radius:var(--radius-sm);
        background:var(--bg-surface);border:1px solid var(--border);
        color:var(--text-primary);font-family:var(--font-heading);
        font-size:.8rem;letter-spacing:1.5px;text-transform:uppercase;
        transition:all var(--transition-med);text-decoration:none;
        margin-bottom:var(--space-lg);
      " onmouseover="this.style.borderColor='var(--gold)';this.style.background='rgba(201,168,76,.06)'"
               onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--bg-surface)'">
                <svg width="20" height="20" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <?= $_SESSION['lang'] === 'en' ? 'Continue with Google' : 'Continuar con Google' ?>
            </a>

            <div class="western-divider">
                <span><?= $_SESSION['lang'] === 'en' ? 'or' : 'o' ?></span>
            </div>

            <!-- Login Form -->
            <form method="POST" action="index.php" data-validate style="margin-top:var(--space-lg)">
                <div class="form-group">
                    <label class="form-label" for="email">
                        <?= $_SESSION['lang'] === 'en' ? '✦ Email Address' : '✦ Correo Electrónico' ?>
                    </label>
                    <input type="email" id="email" name="email" class="form-control" required
                           placeholder="<?= $_SESSION['lang'] === 'en' ? 'your@email.com' : 'tu@correo.com' ?>"
                           autocomplete="email">
                </div>

                <div class="form-group" style="position:relative">
                    <label class="form-label" for="password">
                        <?= $_SESSION['lang'] === 'en' ? '✦ Password' : '✦ Contraseña' ?>
                    </label>
                    <input type="password" id="password" name="password" class="form-control" required
                           placeholder="••••••••" autocomplete="current-password">
                    <button type="button" onclick="togglePass('password', this)"
                            style="position:absolute;right:14px;top:42px;background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:1rem"
                            aria-label="Mostrar contraseña">👁</button>
                </div>

                <div style="display:flex;justify-content:flex-end;margin-bottom:var(--space-lg)">
                    <a href="#" style="font-size:.8rem;color:var(--text-muted)">
                        <?= $_SESSION['lang'] === 'en' ? 'Forgot password?' : '¿Olvidaste tu contraseña?' ?>
                    </a>
                </div>

                <button type="submit" name="iniciarSesion" class="btn-western w-100" style="padding:16px">
                    <?= $_SESSION['lang'] === 'en' ? '✦ Enter the Saloon ✦' : '✦ Entrar al Saloon ✦' ?>
                </button>
            </form>
        </div>

        <p style="text-align:center;margin-top:var(--space-lg);font-size:.87rem;color:var(--text-muted)">
            <?= $_SESSION['lang'] === 'en' ? "Don't have an account?" : '¿No tienes cuenta?' ?>
        <form method="POST" style="display:inline">
            <button name="ir_registro" style="background:none;border:none;cursor:pointer;color:var(--gold);font-family:var(--font-heading);font-size:.87rem;letter-spacing:1px">
                <?= $_SESSION['lang'] === 'en' ? 'Register here' : 'Regístrate aquí' ?>
            </button>
        </form>
        </p>

        <p style="text-align:center;margin-top:8px">
        <form method="POST" style="display:inline">
            <button name="ir_landing" style="background:none;border:none;cursor:pointer;color:var(--text-muted);font-size:.8rem;font-family:var(--font-body)">
                ← <?= $_SESSION['lang'] === 'en' ? 'Back to Home' : 'Volver al inicio' ?>
            </button>
        </form>
        </p>

    </div>
</div>

<script src="js/main.js"></script>
<script>
    function togglePass(id, btn) {
        const inp = document.getElementById(id);
        if (inp.type === 'password') { inp.type = 'text'; btn.textContent = '🙈'; }
        else { inp.type = 'password'; btn.textContent = '👁'; }
    }
</script>
</body>
</html>