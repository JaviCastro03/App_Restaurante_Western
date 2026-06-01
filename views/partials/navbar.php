<?php

$base = '';

$lang     = $_SESSION['lang']  ?? 'es';
$theme    = $_SESSION['theme'] ?? 'dark';
$isLogged = isset($_SESSION['idU']);
$rol      = $_SESSION['rol'] ?? null;

$labels = [
        'inicio'    => ['es' => 'Inicio',        'en' => 'Home'],
        'menu'      => ['es' => 'Menú',          'en' => 'Menu'],
        'nosotros'  => ['es' => 'Nosotros',      'en' => 'About'],
        'contacto'  => ['es' => 'Contacto',      'en' => 'Contact'],
        'login'     => ['es' => 'Iniciar Sesión','en' => 'Sign In'],
        'registro'  => ['es' => 'Registrarse',   'en' => 'Register'],
        'dashboard' => ['es' => 'Mi Panel',      'en' => 'Dashboard'],
        'salir'     => ['es' => 'Salir',         'en' => 'Log out'],
];
$t = fn($k) => $labels[$k][$lang] ?? $labels[$k]['es'];

$rolName = match($rol) {
    ROL_ADMIN    => ($lang === 'en' ? 'Administrator' : 'Administrador'),
    ROL_CAMARERO => ($lang === 'en' ? 'Waiter'        : 'Camarero'),
    ROL_CLIENTE  => ($lang === 'en' ? 'Client'         : 'Cliente'),
    default      => ''
};

// ── URL raíz del proyecto ──────────────────────────────────────
$root = APP_URL . '/index.php';
?>
<nav class="navbar" id="mainNavbar">
    <!-- Brand → siempre vuelve al inicio -->
    <a href="<?= $root ?>?ruta=landing" class="navbar-brand">
        <span class="star">✦</span>
        <span>EL ÚLTIMO CORRAL</span>
        <span class="star">✦</span>
    </a>

    <!-- Mobile toggle -->
    <?php if ($isLogged): ?>
        <button id="sidebarToggle" class="btn-icon d-flex" style="display:none!important" aria-label="Menú">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    <?php endif; ?>

    <!-- Nav links -->
    <?php if (!$isLogged || ($_SESSION['ruta'] ?? '') === 'landing'): ?>
        <ul class="navbar-nav" style="display:flex">
            <li><a href="<?= $root ?>#nosotros"           class="nav-link-item"><?= $t('nosotros') ?></a></li>
            <li><a href="<?= $root ?>?ruta=menu_digital"  class="nav-link-item"><?= $t('menu') ?></a></li>
            <li><a href="<?= $root ?>#contacto"           class="nav-link-item"><?= $t('contacto') ?></a></li>
        </ul>
    <?php endif; ?>

    <!-- Controls -->
    <div class="navbar-controls">
        <!-- Idioma -->
        <button class="lang-btn <?= $lang === 'es' ? 'active' : '' ?>" data-lang="es">ES</button>
        <button class="lang-btn <?= $lang === 'en' ? 'active' : '' ?>" data-lang="en">EN</button>

        <!-- Tema -->
        <button class="theme-toggle" id="themeToggle"
                title="<?= $theme === 'dark' ? 'Modo claro' : 'Modo oscuro' ?>"></button>

        <!-- Auth -->
        <?php if (!$isLogged): ?>
            <form method="POST" action="<?= $root ?>" style="display:contents">
                <button name="ir_login"
                        class="btn-ghost btn-sm btn-western"
                        style="padding:8px 16px;font-size:.7rem">
                    <?= $t('login') ?>
                </button>
            </form>
            <form method="POST" action="<?= $root ?>" style="display:contents">
                <button name="ir_registro"
                        class="btn-western btn-sm"
                        style="padding:8px 16px;font-size:.7rem">
                    <?= $t('registro') ?>
                </button>
            </form>
        <?php else: ?>
            <div style="display:flex;align-items:center;gap:8px">
                <div style="text-align:right;line-height:1.2">
          <span style="font-family:var(--font-heading);font-size:.75rem;color:var(--text-primary);letter-spacing:1px;display:block">
            <?= htmlspecialchars($_SESSION['nombre']) ?>
          </span>
                    <span style="font-size:.6rem;color:var(--gold);letter-spacing:2px;text-transform:uppercase">
            <?= $rolName ?>
          </span>
                </div>
                <form method="POST" action="<?= $root ?>" style="display:contents">
                    <button name="ir_dashboard"
                            class="btn-ghost btn-sm btn-western"
                            style="padding:8px 14px;font-size:.7rem">
                        <?= $t('dashboard') ?>
                    </button>
                </form>
                <a href="<?= $root ?>?ruta=logout" class="btn-icon" title="<?= $t('salir') ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<div id="sidebarOverlay"
     class="modal-overlay"
     style="z-index:400;background:rgba(0,0,0,.5)"></div>

<style>
    @media(max-width:900px){
        .navbar-nav { display:none !important; }
        #sidebarToggle { display:flex !important; }
    }
</style>