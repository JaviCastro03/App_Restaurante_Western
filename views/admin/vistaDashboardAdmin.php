<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'es' ?>" data-theme="<?= $_SESSION['theme'] ?? 'dark' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin — El Último Corral</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Cinzel+Decorative:wght@700&family=Lora:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/styles/main.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/styles/vistaDashboardAdmin.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🤠</text></svg>">
</head>
<body>
<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="dashboard-layout">

    <!-- ── SIDEBAR ─────────────────────────────────────────── -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-avatar">🤠</div>
            <div class="sidebar-name"><?= htmlspecialchars($_SESSION['nombre']) ?></div>
            <div class="sidebar-role">★ <?= $_SESSION['lang'] === 'en' ? 'Administrator' : 'Administrador' ?> ★</div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section"><?= $_SESSION['lang'] === 'en' ? 'Overview' : 'Resumen' ?></div>
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=resumen" class="sidebar-link <?= $datos['subvista']==='resumen'?'active':'' ?>">
                <span class="icon">📊</span><?= $_SESSION['lang'] === 'en' ? 'Dashboard' : 'Resumen' ?>
            </a>
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=estadisticas" class="sidebar-link <?= $datos['subvista']==='estadisticas'?'active':'' ?>">
                <span class="icon">📈</span><?= $_SESSION['lang'] === 'en' ? 'Statistics' : 'Estadísticas' ?>
            </a>

            <div class="sidebar-section"><?= $_SESSION['lang'] === 'en' ? 'Restaurant' : 'Restaurante' ?></div>
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=mesas" class="sidebar-link <?= $datos['subvista']==='mesas'?'active':'' ?>">
                <span class="icon">🪑</span><?= $_SESSION['lang'] === 'en' ? 'Tables' : 'Mesas' ?>
            </a>
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=turnos" class="sidebar-link <?= $datos['subvista']==='turnos'?'active':'' ?>">
                <span class="icon">⏰</span><?= $_SESSION['lang'] === 'en' ? 'Shifts' : 'Turnos' ?>
            </a>
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=horario" class="sidebar-link <?= $datos['subvista']==='horario'?'active':'' ?>">
                <span class="icon">📅</span><?= $_SESSION['lang'] === 'en' ? 'Schedule' : 'Horario' ?>
            </a>

            <div class="sidebar-section"><?= $_SESSION['lang'] === 'en' ? 'Management' : 'Gestión' ?></div>
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=reservas" class="sidebar-link <?= $datos['subvista']==='reservas'?'active':'' ?>">
                <span class="icon">📋</span><?= $_SESSION['lang'] === 'en' ? 'Reservations' : 'Reservas' ?>
            </a>
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=usuarios" class="sidebar-link <?= $datos['subvista']==='usuarios'?'active':'' ?>">
                <span class="icon">👥</span><?= $_SESSION['lang'] === 'en' ? 'Users' : 'Usuarios' ?>
            </a>

            <div class="sidebar-section"><?= $_SESSION['lang'] === 'en' ? 'General' : 'General' ?></div>
            <a href="<?= APP_URL ?>/index.php" class="sidebar-link">
                <span class="icon">🏠</span><?= $_SESSION['lang'] === 'en' ? 'Landing Page' : 'Página Principal' ?>
            </a>
            <a href="<?= APP_URL ?>/index.php?ruta=logout" class="sidebar-link" style="color:var(--rust)">
                <span class="icon">🚪</span><?= $_SESSION['lang'] === 'en' ? 'Log Out' : 'Cerrar Sesión' ?>
            </a>
        </nav>
    </aside>

    <!-- ── MAIN CONTENT ────────────────────────────────────── -->
    <main class="dashboard-main">

        <?php if (isset($datos['admin_msg'])): ?>
            <div class="alert alert-<?= $datos['admin_msg']['tipo'] ?>" data-auto-dismiss="5000" style="margin-bottom:var(--space-lg)">
                <?= htmlspecialchars($datos['admin_msg']['texto']) ?>
                <button class="alert-close" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit">✕</button>
            </div>
        <?php endif; ?>

        <!-- ════════════════ RESUMEN ════════════════ -->
        <?php if ($datos['subvista'] === 'resumen'): ?>
            <div style="margin-bottom:var(--space-xl)">
                <h2 style="margin-bottom:4px"><?= $_SESSION['lang'] === 'en' ? '★ Daily Overview' : '★ Resumen del Día' ?></h2>
                <p style="color:var(--text-muted);font-style:italic"><?= date('l, d \d\e F \d\e Y') ?></p>
            </div>

            <?php $r = $datos['resumen']; ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-icon">🍽️</span>
                    <div class="stat-value"><?= $r['servicios_hoy'] ?? 0 ?></div>
                    <div class="stat-label"><?= $_SESSION['lang'] === 'en' ? 'Services Today' : 'Servicios Hoy' ?></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">👥</span>
                    <div class="stat-value"><?= $r['comensales_hoy'] ?? 0 ?></div>
                    <div class="stat-label"><?= $_SESSION['lang'] === 'en' ? 'Diners Today' : 'Comensales Hoy' ?></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">💰</span>
                    <div class="stat-value"><?= number_format($r['ingresos_hoy'] ?? 0, 0) ?>€</div>
                    <div class="stat-label"><?= $_SESSION['lang'] === 'en' ? "Today's Revenue" : 'Ingresos Hoy' ?></div>
                </div>
                <div class="stat-card">
                    <span class="stat-icon">📋</span>
                    <div class="stat-value"><?= $r['reservas_hoy'] ?? 0 ?></div>
                    <div class="stat-label"><?= $_SESSION['lang'] === 'en' ? "Today's Reservations" : 'Reservas Hoy' ?></div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-lg);margin-top:var(--space-lg)">
                <div class="card">
                    <div class="card-header">
                        <span>📊</span>
                        <span class="card-title"><?= $_SESSION['lang'] === 'en' ? 'Quick Actions' : 'Acciones Rápidas' ?></span>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:var(--space-sm)">
                        <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=mesas" class="btn-western btn-sm">🪑 <?= $_SESSION['lang'] === 'en' ? 'Manage Tables' : 'Gestionar Mesas' ?></a>
                        <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=reservas" class="btn-ghost btn-sm btn-western">📋 <?= $_SESSION['lang'] === 'en' ? 'View Reservations' : 'Ver Reservas' ?></a>
                        <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=estadisticas" class="btn-ghost btn-sm btn-western">📈 <?= $_SESSION['lang'] === 'en' ? 'Statistics' : 'Estadísticas' ?></a>
                        <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=usuarios" class="btn-ghost btn-sm btn-western">👥 <?= $_SESSION['lang'] === 'en' ? 'Manage Users' : 'Gestionar Usuarios' ?></a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <span>🕐</span>
                        <span class="card-title"><?= $_SESSION['lang'] === 'en' ? 'System Info' : 'Info del Sistema' ?></span>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:8px">
                        <?php
                        $diasEs = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
                        $diasEn = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                        $diasArr = $_SESSION['lang'] === 'en' ? $diasEn : $diasEs;
                        ?>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border)">
                            <span style="color:var(--text-muted);font-size:.85rem"><?= $_SESSION['lang'] === 'en' ? 'Today' : 'Hoy' ?></span>
                            <span style="font-family:var(--font-heading);font-size:.85rem"><?= $diasArr[date('N')-1] ?>, <?= date('d/m/Y') ?></span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border)">
                            <span style="color:var(--text-muted);font-size:.85rem"><?= $_SESSION['lang'] === 'en' ? 'Time' : 'Hora' ?></span>
                            <span style="font-family:var(--font-heading);font-size:.85rem;color:var(--gold)" id="clock"><?= date('H:i:s') ?></span>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ════════════════ MESAS ════════════════ -->
        <?php elseif ($datos['subvista'] === 'mesas'): ?>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-xl)">
                <div>
                    <h2 style="margin-bottom:4px"><?= $_SESSION['lang'] === 'en' ? '★ Tables' : '★ Mesas' ?></h2>
                    <p style="color:var(--text-muted);font-style:italic"><?= $_SESSION['lang'] === 'en' ? 'Manage restaurant tables' : 'Gestiona las mesas del restaurante' ?></p>
                </div>
                <button class="btn-western" data-modal="modalCrearMesa">
                    + <?= $_SESSION['lang'] === 'en' ? 'New Table' : 'Nueva Mesa' ?>
                </button>
            </div>

            <?php foreach ($datos['zonas'] as $zona): ?>
                <div style="margin-bottom:var(--space-xl)">
                    <div class="western-divider">
                        <span><?= htmlspecialchars($zona['nombre']) ?></span>
                    </div>
                    <div class="mesas-grid">
                        <?php
                        $mesasZona = array_filter($datos['mesas'], fn($m) => $m['zona_id'] == $zona['id']);
                        foreach ($mesasZona as $mesa):
                            $estadoClass = $mesa['estado'] ?? 'libre';
                            ?>
                            <div class="mesa-card <?= $estadoClass ?>">
                                <div class="mesa-estado-dot"></div>
                                <span class="mesa-numero"><?= htmlspecialchars($mesa['numero']) ?></span>
                                <span class="mesa-capacidad">👥 <?= $mesa['capacidad'] ?></span>
                                <span class="badge badge-<?= $estadoClass === 'libre' ? 'success' : ($estadoClass === 'ocupada' ? 'danger' : 'warning') ?>" style="font-size:.55rem">
        <?= $estadoClass ?>
    </span>
                                <div class="mesa-actions">
                                    <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=mesas">
                                        <input type="hidden" name="id" value="<?= $mesa['id'] ?>">
                                        <input type="hidden" name="nuevo_estado" value="<?= $estadoClass === 'libre' ? 'ocupada' : 'libre' ?>">
                                        <button type="submit" name="cambiar_estado_mesa" class="btn-icon btn-sm"
                                                title="<?= $estadoClass === 'libre' ? 'Marcar ocupada' : 'Marcar libre' ?>">
                                            <?= $estadoClass === 'libre' ? '🔴' : '🟢' ?>
                                        </button>
                                    </form>
                                    <button class="btn-icon btn-sm" onclick="openModal('modalEditarMesa<?= $mesa['id'] ?>')">✏️</button>
                                    <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=mesas">
                                        <input type="hidden" name="id" value="<?= $mesa['id'] ?>">
                                        <button type="submit" name="eliminar_mesa" class="btn-icon btn-sm"
                                                onclick="return confirm('<?= $_SESSION['lang'] === 'en' ? 'Delete this table?' : '¿Eliminar esta mesa?' ?>')">🗑️</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Modal editar mesa -->
                            <div class="modal-overlay" id="modalEditarMesa<?= $mesa['id'] ?>">
                                <div class="modal" style="max-width:440px">
                                    <div class="modal-header">
                                        <span class="modal-title">✏️ <?= $_SESSION['lang'] === 'en' ? 'Edit Table' : 'Editar Mesa' ?> <?= htmlspecialchars($mesa['numero']) ?></span>
                                        <button class="modal-close" data-close-modal>✕</button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=mesas">
                                            <input type="hidden" name="id" value="<?= $mesa['id'] ?>">
                                            <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Table Number' : 'Número de Mesa' ?></label>
                                                <input type="text" name="numero" class="form-control" value="<?= htmlspecialchars($mesa['numero']) ?>" required>
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Capacity' : 'Capacidad' ?></label>
                                                    <input type="number" name="capacidad" class="form-control" value="<?= $mesa['capacidad'] ?>" min="1" max="30" required>
                                                </div>
                                                <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Zone' : 'Zona' ?></label>
                                                    <select name="zona_id" class="form-control" required>
                                                        <?php foreach ($datos['zonas'] as $z): ?>
                                                            <option value="<?= $z['id'] ?>" <?= $z['id']==$mesa['zona_id']?'selected':'' ?>><?= htmlspecialchars($z['nombre']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Description' : 'Descripción' ?></label>
                                                <input type="text" name="descripcion" class="form-control" value="<?= htmlspecialchars($mesa['descripcion'] ?? '') ?>">
                                            </div>
                                            <button type="submit" name="editar_mesa" class="btn-western w-100"><?= $_SESSION['lang'] === 'en' ? '✦ Save Changes ✦' : '✦ Guardar Cambios ✦' ?></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php if (empty($mesasZona)): ?>
                            <div style="grid-column:1/-1;text-align:center;padding:var(--space-xl);color:var(--text-muted)">
                                <?= $_SESSION['lang'] === 'en' ? 'No tables in this zone.' : 'No hay mesas en esta zona.' ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- ════════════════ TURNOS ════════════════ -->
        <?php elseif ($datos['subvista'] === 'turnos'): ?>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-xl)">
                <div>
                    <h2 style="margin-bottom:4px"><?= $_SESSION['lang'] === 'en' ? '★ Shifts' : '★ Turnos' ?></h2>
                    <p style="color:var(--text-muted);font-style:italic"><?= $_SESSION['lang'] === 'en' ? 'Manage meal shifts and schedules' : 'Gestiona los turnos de comida' ?></p>
                </div>
                <button class="btn-western" data-modal="modalCrearTurno">
                    + <?= $_SESSION['lang'] === 'en' ? 'New Shift' : 'Nuevo Turno' ?>
                </button>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:var(--space-md)">
                <?php foreach ($datos['turnos'] as $turno): ?>
                    <div class="card" style="display:flex;flex-direction:column;gap:var(--space-md)">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between">
                            <div>
                                <div style="font-family:var(--font-heading);color:var(--text-primary);font-size:1rem;margin-bottom:4px">
                                    <?= htmlspecialchars($turno['nombre']) ?>
                                </div>
                                <div class="badge badge-<?= $turno['activo']?'success':'neutral' ?>">
                                    <?= $turno['activo'] ? ($_SESSION['lang']==='en'?'Active':'Activo') : ($_SESSION['lang']==='en'?'Inactive':'Inactivo') ?>
                                </div>
                            </div>
                            <div style="font-size:2rem">
                                <?= match($turno['tipo']){ 'comida'=>'🍽️','cena'=>'🌙','desayuno'=>'☀️', default=>'🕐' } ?>
                            </div>
                        </div>
                        <div style="background:var(--bg-surface);border-radius:var(--radius-sm);padding:var(--space-md);text-align:center">
        <span style="font-family:var(--font-heading);font-size:1.3rem;color:var(--gold)">
          <?= $turno['hora_inicio'] ?> – <?= $turno['hora_fin'] ?>
        </span>
                        </div>
                        <div style="display:flex;gap:8px;margin-top:auto">
                            <button class="btn-ghost btn-sm btn-western flex-1" data-modal="modalEditarTurno<?= $turno['id'] ?>">
                                ✏️ <?= $_SESSION['lang'] === 'en' ? 'Edit' : 'Editar' ?>
                            </button>
                            <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=turnos">
                                <input type="hidden" name="id" value="<?= $turno['id'] ?>">
                                <button type="submit" name="eliminar_turno" class="btn-danger btn-western btn-sm"
                                        onclick="return confirm('<?= $_SESSION['lang'] === 'en' ? 'Delete?' : '¿Eliminar?' ?>')">🗑️</button>
                            </form>
                        </div>
                    </div>

                    <!-- Modal editar turno -->
                    <div class="modal-overlay" id="modalEditarTurno<?= $turno['id'] ?>">
                        <div class="modal" style="max-width:440px">
                            <div class="modal-header">
                                <span class="modal-title">✏️ <?= $_SESSION['lang'] === 'en' ? 'edit Shift' : 'Editar Turno' ?></span>
                                <button class="modal-close" data-close-modal>✕</button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=turnos">
                                    <input type="hidden" name="id" value="<?= $turno['id'] ?>">
                                    <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Name' : 'Nombre' ?></label>
                                        <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($turno['nombre']) ?>" required>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Start Time' : 'Hora Inicio' ?></label>
                                            <input type="time" name="hora_inicio" class="form-control" value="<?= $turno['hora_inicio'] ?>" required>
                                        </div>
                                        <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'End Time' : 'Hora Fin' ?></label>
                                            <input type="time" name="hora_fin" class="form-control" value="<?= $turno['hora_fin'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Type' : 'Tipo' ?></label>
                                        <select name="tipo" class="form-control">
                                            <?php foreach(['comida','cena','desayuno','merienda'] as $t): ?>
                                                <option value="<?= $t ?>" <?= $t===$turno['tipo']?'selected':'' ?>><?= ucfirst($t) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" name="editar_turno" class="btn-western w-100"><?= $_SESSION['lang'] === 'en' ? '✦ Save ✦' : '✦ Guardar ✦' ?></button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- ════════════════ HORARIO ════════════════ -->
        <?php elseif ($datos['subvista'] === 'horario'): ?>
            <div style="margin-bottom:var(--space-xl)">
                <h2 style="margin-bottom:4px"><?= $_SESSION['lang'] === 'en' ? '★ Schedule' : '★ Horario' ?></h2>
                <p style="color:var(--text-muted);font-style:italic"><?= $_SESSION['lang'] === 'en' ? 'Opening days and rest days' : 'Días de apertura y descanso' ?></p>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:var(--space-xl)">
                <!-- Weekly schedule -->
                <div class="card">
                    <div class="card-header"><span>📅</span><span class="card-title"><?= $_SESSION['lang'] === 'en' ? 'Weekly Schedule' : 'Horario Semanal' ?></span></div>
                    <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=horario">
                        <?php
                        $diasEs = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
                        $diasEn = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                        foreach ($datos['dias'] as $dia):
                            $nombre = $_SESSION['lang'] === 'en' ? $diasEn[$dia['dia_semana']] : $diasEs[$dia['dia_semana']];
                            ?>
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border)">
                                <span style="font-family:var(--font-heading);font-size:.85rem;letter-spacing:1px"><?= $nombre ?></span>
                                <label class="check-western">
                                    <input type="checkbox" name="dia_<?= $dia['dia_semana'] ?>" <?= $dia['abierto']?'checked':'' ?>>
                                    <span style="font-size:.75rem"><?= $_SESSION['lang'] === 'en' ? 'Open' : 'Abierto' ?></span>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        <button type="submit" name="guardar_horario" class="btn-western w-100" style="margin-top:var(--space-lg)">
                            <?= $_SESSION['lang'] === 'en' ? '✦ Save Schedule ✦' : '✦ Guardar Horario ✦' ?>
                        </button>
                    </form>
                </div>

                <!-- Rest days -->
                <div class="card">
                    <div class="card-header"><span>🌙</span><span class="card-title"><?= $_SESSION['lang'] === 'en' ? 'Rest Days / Holidays' : 'Días de Descanso / Festivos' ?></span></div>
                    <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=horario" style="margin-bottom:var(--space-lg)">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Date' : 'Fecha' ?></label>
                                <input type="date" name="fecha_descanso" class="form-control" required min="<?= date('Y-m-d') ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Reason' : 'Motivo' ?></label>
                                <input type="text" name="motivo_descanso" class="form-control" placeholder="<?= $_SESSION['lang'] === 'en' ? 'Holiday, etc.' : 'Festivo, etc.' ?>">
                            </div>
                        </div>
                        <button type="submit" name="agregar_descanso" class="btn-gold btn-western btn-sm">
                            + <?= $_SESSION['lang'] === 'en' ? 'Add' : 'Añadir' ?>
                        </button>
                    </form>
                    <div style="max-height:280px;overflow-y:auto">
                        <?php foreach ($datos['descanso'] as $d): ?>
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border)">
                                <div>
                                    <span style="font-family:var(--font-heading);font-size:.85rem;color:var(--gold)"><?= $d['fecha'] ?></span>
                                    <?php if($d['motivo']): ?>
                                        <span style="color:var(--text-muted);font-size:.8rem;margin-left:8px">— <?= htmlspecialchars($d['motivo']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=horario">
                                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                    <button type="submit" name="eliminar_descanso" class="btn-icon">🗑️</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                        <?php if(empty($datos['descanso'])): ?>
                            <p style="color:var(--text-muted);font-style:italic;text-align:center;padding:var(--space-lg)">
                                <?= $_SESSION['lang'] === 'en' ? 'No rest days scheduled.' : 'No hay días de descanso registrados.' ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ════════════════ USUARIOS ════════════════ -->
        <?php elseif ($datos['subvista'] === 'usuarios'): ?>
            <div style="margin-bottom:var(--space-xl)">
                <h2 style="margin-bottom:4px"><?= $_SESSION['lang'] === 'en' ? '★ Users' : '★ Usuarios' ?></h2>
                <p style="color:var(--text-muted);font-style:italic"><?= $_SESSION['lang'] === 'en' ? 'Manage users, roles and access' : 'Gestiona usuarios, roles y accesos' ?></p>
            </div>

            <div class="card" style="overflow-x:auto">
                <table class="table-western">
                    <thead>
                    <tr>
                        <th><?= $_SESSION['lang'] === 'en' ? 'Name' : 'Nombre' ?></th>
                        <th>Email</th>
                        <th>DNI</th>
                        <th>Rol</th>
                        <th><?= $_SESSION['lang'] === 'en' ? 'Status' : 'Estado' ?></th>
                        <th><?= $_SESSION['lang'] === 'en' ? 'Registered' : 'Registrado' ?></th>
                        <th><?= $_SESSION['lang'] === 'en' ? 'Actions' : 'Acciones' ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($datos['usuarios'] as $u): ?>
                        <tr>
                            <td style="font-weight:600;color:var(--text-primary)"><?= htmlspecialchars($u['nombre'].' '.$u['apellidos']) ?></td>
                            <td style="font-size:.83rem"><?= htmlspecialchars($u['email']) ?></td>
                            <td style="font-size:.8rem;color:var(--text-muted)"><?= htmlspecialchars($u['dni']) ?></td>
                            <td>
            <span class="badge badge-<?= match((int)$u['rol']){ROL_ADMIN=>'admin',ROL_CAMARERO=>'camarero',ROL_CLIENTE=>'cliente',default=>'neutral'} ?>">
              <?= match((int)$u['rol']){
                  ROL_ADMIN    => ($_SESSION['lang']==='en'?'Admin':'Admin'),
                  ROL_CAMARERO => ($_SESSION['lang']==='en'?'Waiter':'Camarero'),
                  ROL_CLIENTE  => ($_SESSION['lang']==='en'?'Client':'Cliente'),
                  default      => ($_SESSION['lang']==='en'?'Unverified':'Sin verificar')
              } ?>
            </span>
                            </td>
                            <td>
            <span class="badge badge-<?= $u['activo']?'success':'danger' ?>">
              <?= $u['activo'] ? ($_SESSION['lang']==='en'?'Active':'Activo') : ($_SESSION['lang']==='en'?'Inactive':'Inactivo') ?>
            </span>
                            </td>
                            <td style="font-size:.8rem;color:var(--text-muted)"><?= date('d/m/Y', strtotime($u['fecha_registro'])) ?></td>
                            <td>
                                <div style="display:flex;gap:6px;flex-wrap:wrap">
                                    <?php if ((int)$u['rol'] !== ROL_CAMARERO && (int)$u['id'] !== $_SESSION['idU']): ?>
                                        <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=usuarios">
                                            <input type="hidden" name="email_usuario" value="<?= htmlspecialchars($u['email']) ?>">
                                            <input type="hidden" name="nuevo_rol" value="<?= ROL_CAMARERO ?>">
                                            <button type="submit" name="cambiar_rol" class="btn-western btn-sm" style="padding:6px 10px;font-size:.65rem"
                                                    title="<?= $_SESSION['lang'] === 'en' ? 'Make Waiter' : 'Hacer Camarero' ?>">🤵</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ((int)$u['rol'] !== ROL_CLIENTE && (int)$u['id'] !== $_SESSION['idU']): ?>
                                        <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=usuarios">
                                            <input type="hidden" name="email_usuario" value="<?= htmlspecialchars($u['email']) ?>">
                                            <input type="hidden" name="nuevo_rol" value="<?= ROL_CLIENTE ?>">
                                            <button type="submit" name="cambiar_rol" class="btn-ghost btn-western btn-sm" style="padding:6px 10px;font-size:.65rem"
                                                    title="<?= $_SESSION['lang'] === 'en' ? 'Make Client' : 'Hacer Cliente' ?>">👤</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ((int)$u['id'] !== $_SESSION['idU']): ?>
                                        <?php if ((int)$u['activo']): ?>
                                            <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=usuarios">
                                                <input type="hidden" name="email_usuario" value="<?= htmlspecialchars($u['email']) ?>">
                                                <button type="submit" name="dar_baja" class="btn-danger btn-western btn-sm" style="padding:6px 10px;font-size:.65rem"
                                                        onclick="return confirm('<?= $_SESSION['lang'] === 'en' ? 'Deactivate this user?' : '¿Dar de baja a este usuario?' ?>')"
                                                        title="<?= $_SESSION['lang'] === 'en' ? 'Deactivate' : 'Dar de Baja' ?>">🚫</button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=usuarios">
                                                <input type="hidden" name="email_usuario" value="<?= htmlspecialchars($u['email']) ?>">
                                                <button type="submit" name="reactivar_usuario" class="btn-western btn-sm" style="padding:6px 10px;font-size:.65rem;background:linear-gradient(135deg,#2e7d32,#388e3c,#2e7d32);border-color:#4caf50"
                                                        onclick="return confirm('<?= $_SESSION['lang'] === 'en' ? 'Reactivate this user?' : '¿Reactivar este usuario?' ?>')"
                                                        title="<?= $_SESSION['lang'] === 'en' ? 'Reactivate' : 'Reactivar' ?>">✅</button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- ════════════════ RESERVAS ════════════════ -->
        <?php elseif ($datos['subvista'] === 'reservas'): ?>
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:var(--space-xl);flex-wrap:wrap;gap:var(--space-md)">
                <div>
                    <h2 style="margin-bottom:4px"><?= $_SESSION['lang'] === 'en' ? '★ Reservations' : '★ Reservas' ?></h2>
                    <p style="color:var(--text-muted);font-style:italic"><?= $_SESSION['lang'] === 'en' ? 'All reservation records' : 'Registro completo de reservas' ?></p>
                </div>
                <form method="GET" action="<?= APP_URL ?>/index.php" style="display:flex;gap:8px;align-items:flex-end;flex-wrap:wrap">
                    <input type="hidden" name="ruta" value="dashboard">
                    <input type="hidden" name="sv" value="reservas">
                    <div>
                        <label class="form-label" style="font-size:.65rem"><?= $_SESSION['lang'] === 'en' ? 'From' : 'Desde' ?></label>
                        <input type="date" name="desde" class="form-control" value="<?= $datos['desde'] ?>" style="padding:8px 12px;font-size:.85rem">
                    </div>
                    <div>
                        <label class="form-label" style="font-size:.65rem"><?= $_SESSION['lang'] === 'en' ? 'To' : 'Hasta' ?></label>
                        <input type="date" name="hasta" class="form-control" value="<?= $datos['hasta'] ?>" style="padding:8px 12px;font-size:.85rem">
                    </div>
                    <button type="submit" class="btn-western btn-sm">🔍</button>
                </form>
            </div>

            <div class="card" style="overflow-x:auto">
                <table class="table-western">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th><?= $_SESSION['lang'] === 'en' ? 'Client' : 'Cliente' ?></th>
                        <th><?= $_SESSION['lang'] === 'en' ? 'Date' : 'Fecha' ?></th>
                        <th><?= $_SESSION['lang'] === 'en' ? 'Shift' : 'Turno' ?></th>
                        <th><?= $_SESSION['lang'] === 'en' ? 'Table / Zone' : 'Mesa / Zona' ?></th>
                        <th><?= $_SESSION['lang'] === 'en' ? 'Diners' : 'Comensales' ?></th>
                        <th><?= $_SESSION['lang'] === 'en' ? 'Status' : 'Estado' ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($datos['reservas'] as $r): ?>
                        <tr>
                            <td style="font-family:var(--font-heading);color:var(--gold);font-size:.85rem">#<?= $r['id'] ?></td>
                            <td>
                                <div style="font-weight:600;font-size:.9rem;color:var(--text-primary)"><?= htmlspecialchars($r['cliente']) ?></div>
                                <div style="font-size:.75rem;color:var(--text-muted)"><?= htmlspecialchars($r['cliente_email']) ?></div>
                            </td>
                            <td style="font-family:var(--font-heading);font-size:.85rem"><?= $r['fecha'] ?></td>
                            <td>
                                <div style="font-size:.85rem"><?= htmlspecialchars($r['turno']) ?></div>
                                <div style="font-size:.75rem;color:var(--text-muted)"><?= $r['hora_inicio'] ?> – <?= $r['hora_fin'] ?></div>
                            </td>
                            <td>
                                <span style="font-family:var(--font-heading);color:var(--gold)"><?= htmlspecialchars($r['mesa']) ?></span>
                                <span style="color:var(--text-muted);font-size:.8rem"> — <?= htmlspecialchars($r['zona']) ?></span>
                            </td>
                            <td style="text-align:center;font-family:var(--font-heading)">👥 <?= $r['num_comensales'] ?></td>
                            <td>
                                <?php
                                $fechaPasada = strtotime($r['fecha']) < strtotime(date('Y-m-d')) && $r['estado'] !== 'cancelada';
                                ?>
                                <?php if ($fechaPasada && $r['estado'] === 'pendiente'): ?>
                                    <span class="badge badge-neutral">
            <?= $_SESSION['lang'] === 'en' ? 'Expired' : 'Expirada' ?>
        </span>
                                <?php else: ?>
                                    <span class="badge badge-<?= match($r['estado']){'pendiente'=>'warning','confirmada'=>'success','cancelada'=>'danger',default=>'neutral'} ?>">
            <?= match($r['estado']){
                'pendiente'  => ($_SESSION['lang']==='en'?'Pending':'Pendiente'),
                'confirmada' => ($_SESSION['lang']==='en'?'Confirmed':'Confirmada'),
                'cancelada'  => ($_SESSION['lang']==='en'?'Cancelled':'Cancelada'),
                default      => $r['estado']
            } ?>
        </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if(empty($datos['reservas'])): ?>
                        <tr><td colspan="7" style="text-align:center;padding:var(--space-xl);color:var(--text-muted);font-style:italic">
                                <?= $_SESSION['lang'] === 'en' ? 'No reservations in this period.' : 'No hay reservas en este período.' ?>
                            </td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- ════════════════ ESTADÍSTICAS ════════════════ -->
        <?php elseif ($datos['subvista'] === 'estadisticas'): ?>
            <div style="margin-bottom:var(--space-xl)">
                <h2 style="margin-bottom:4px"><?= $_SESSION['lang'] === 'en' ? '★ Statistics' : '★ Estadísticas' ?></h2>
                <p style="color:var(--text-muted);font-style:italic"><?= $_SESSION['lang'] === 'en' ? 'Performance metrics and revenue analysis' : 'Métricas de rendimiento e ingresos' ?></p>
            </div>

            <!-- Summary cards -->
            <?php $r = $datos['resumen']; ?>
            <div class="stats-grid" style="margin-bottom:var(--space-xl)">
                <div class="stat-card"><span class="stat-icon">🍽️</span><div class="stat-value"><?= $r['servicios_hoy']??0 ?></div><div class="stat-label"><?= $_SESSION['lang']==='en'?'Services Today':'Servicios Hoy' ?></div></div>
                <div class="stat-card"><span class="stat-icon">👥</span><div class="stat-value"><?= $r['comensales_hoy']??0 ?></div><div class="stat-label"><?= $_SESSION['lang']==='en'?'Diners Today':'Comensales Hoy' ?></div></div>
                <div class="stat-card"><span class="stat-icon">💶</span><div class="stat-value"><?= number_format($r['ingresos_hoy']??0,2) ?>€</div><div class="stat-label"><?= $_SESSION['lang']==='en'?'Revenue Today':'Ingresos Hoy' ?></div></div>
                <div class="stat-card"><span class="stat-icon">📋</span><div class="stat-value"><?= $r['reservas_hoy']??0 ?></div><div class="stat-label"><?= $_SESSION['lang']==='en'?'Reservations':'Reservas' ?></div></div>
            </div>

            <!-- Period filter -->
            <div style="display:flex;gap:8px;margin-bottom:var(--space-lg);flex-wrap:wrap">
                <?php foreach(['dia'=>($_SESSION['lang']==='en'?'Day':'Día'),'semana'=>($_SESSION['lang']==='en'?'Week':'Semana'),'mes'=>($_SESSION['lang']==='en'?'Month':'Mes'),'anyo'=>($_SESSION['lang']==='en'?'Year':'Año')] as $k=>$v): ?>
                    <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=estadisticas&periodo=<?= $k ?>&anyo=<?= $datos['año'] ?>"
                       class="btn-<?= $datos['periodo']===$k?'western':'ghost btn-western' ?> btn-sm"><?= $v ?></a>
                <?php endforeach; ?>
            </div>

            <div style="display:grid;grid-template-columns:2fr 1fr;gap:var(--space-lg)">
                <!-- Revenue chart -->
                <div class="card">
                    <div class="card-header"><span>📈</span><span class="card-title"><?= $_SESSION['lang'] === 'en' ? 'Revenue' : 'Ingresos' ?></span></div>
                    <div style="height:280px;position:relative">
                        <canvas id="chartIngresos"></canvas>
                    </div>
                </div>
                <!-- Top dishes -->
                <div class="card">
                    <div class="card-header"><span>🏆</span><span class="card-title"><?= $_SESSION['lang'] === 'en' ? 'Top Dishes' : 'Top Platos' ?></span></div>
                    <div style="display:flex;flex-direction:column;gap:8px;max-height:300px;overflow-y:auto">
                        <?php foreach(array_slice($datos['top_platos'],0,8) as $i=>$p): ?>
                            <div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid var(--border)">
                                <span style="font-family:var(--font-heading);color:var(--gold);font-size:.75rem;width:18px">#<?= $i+1 ?></span>
                                <div style="flex:1;min-width:0">
                                    <div style="font-size:.85rem;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($p['nombre_es']) ?></div>
                                    <div style="font-size:.7rem;color:var(--text-muted)"><?= $p['total_vendido'] ?> <?= $_SESSION['lang'] === 'en' ? 'sold' : 'vendidos' ?></div>
                                </div>
                                <span style="font-family:var(--font-heading);color:var(--gold);font-size:.85rem"><?= number_format($p['total_ingresos'],0) ?>€</span>
                            </div>
                        <?php endforeach; ?>
                        <?php if(empty($datos['top_platos'])): ?>
                            <p style="color:var(--text-muted);font-style:italic;text-align:center;padding:var(--space-lg)"><?= $_SESSION['lang'] === 'en' ? 'No data yet.' : 'Sin datos aún.' ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </main>
</div>

<!-- ════ MODALES GLOBALES ════ -->

<!-- Modal Crear Mesa -->
<div class="modal-overlay" id="modalCrearMesa">
    <div class="modal" style="max-width:440px">
        <div class="modal-header">
            <span class="modal-title">🪑 <?= $_SESSION['lang'] === 'en' ? 'New Table' : 'Nueva Mesa' ?></span>
            <button class="modal-close" data-close-modal>✕</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=mesas">
                <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Table Number / Code' : 'Número / Código Mesa' ?></label>
                    <input type="text" name="numero" class="form-control" required placeholder="M1, T2, P1...">
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Capacity' : 'Capacidad' ?></label>
                        <input type="number" name="capacidad" class="form-control" required min="1" max="30" value="4">
                    </div>
                    <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Zone' : 'Zona' ?></label>
                        <select name="zona_id" class="form-control" required>
                            <?php foreach ($datos['zonas'] ?? [] as $z): ?>
                                <option value="<?= $z['id'] ?>"><?= htmlspecialchars($z['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Description' : 'Descripción' ?></label>
                    <input type="text" name="descripcion" class="form-control" placeholder="<?= $_SESSION['lang'] === 'en' ? 'Optional note' : 'Nota opcional' ?>">
                </div>
                <button type="submit" name="crear_mesa" class="btn-western w-100"><?= $_SESSION['lang'] === 'en' ? '✦ Create Table ✦' : '✦ Crear Mesa ✦' ?></button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Crear Turno -->
<div class="modal-overlay" id="modalCrearTurno">
    <div class="modal" style="max-width:440px">
        <div class="modal-header">
            <span class="modal-title">⏰ <?= $_SESSION['lang'] === 'en' ? 'New Shift' : 'Nuevo Turno' ?></span>
            <button class="modal-close" data-close-modal>✕</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=turnos">
                <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Shift Name' : 'Nombre del Turno' ?></label>
                    <input type="text" name="nombre" class="form-control" required placeholder="<?= $_SESSION['lang'] === 'en' ? 'e.g. Lunch 1st Shift' : 'ej: Comida 1er Turno' ?>">
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Start' : 'Inicio' ?></label>
                        <input type="time" name="hora_inicio" class="form-control" required>
                    </div>
                    <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'End' : 'Fin' ?></label>
                        <input type="time" name="hora_fin" class="form-control" required>
                    </div>
                </div>
                <div class="form-group"><label class="form-label"><?= $_SESSION['lang'] === 'en' ? 'Type' : 'Tipo' ?></label>
                    <select name="tipo" class="form-control">
                        <option value="comida"><?= $_SESSION['lang'] === 'en' ? 'Lunch' : 'Comida' ?></option>
                        <option value="cena"><?= $_SESSION['lang'] === 'en' ? 'Dinner' : 'Cena' ?></option>
                        <option value="desayuno"><?= $_SESSION['lang'] === 'en' ? 'Breakfast' : 'Desayuno' ?></option>
                        <option value="merienda"><?= $_SESSION['lang'] === 'en' ? 'Snack' : 'Merienda' ?></option>
                    </select>
                </div>
                <button type="submit" name="crear_turno" class="btn-western w-100"><?= $_SESSION['lang'] === 'en' ? '✦ Create Shift ✦' : '✦ Crear Turno ✦' ?></button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script src="<?= APP_URL ?>/js/main.js"></script>
<script>
    // Clock
    setInterval(() => {
        const el = document.getElementById('clock');
        if (el) el.textContent = new Date().toLocaleTimeString('es-ES');
    }, 1000);

    // Statistics chart
    <?php if ($datos['subvista'] === 'estadisticas' && !empty($datos['estadisticas'])): ?>
    const labels = <?= json_encode(array_column($datos['estadisticas'],'etiqueta')) ?>;
    const values = <?= json_encode(array_column($datos['estadisticas'],'ingresos')) ?>;
    renderWesternChart('chartIngresos', 'bar', labels, values, '<?= $_SESSION['lang'] === 'en' ? 'Revenue (€)' : 'Ingresos (€)' ?>');
    <?php endif; ?>
</script>
</body>
</html>