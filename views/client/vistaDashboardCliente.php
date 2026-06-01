<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'es' ?>" data-theme="<?= $_SESSION['theme'] ?? 'dark' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_SESSION['lang'] === 'en' ? 'My Panel' : 'Mi Panel' ?> — El Último Corral</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Cinzel+Decorative:wght@700&family=Lora:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/styles/main.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/styles/vistaDashboardCliente.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🤠</text></svg>">
    <style>
        /* ── Custom Select ── */
        .custom-select-wrapper { position: relative; width: 100%; }
        .custom-select-trigger {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 16px;
            background: var(--bg-secondary, rgba(139,69,19,.08));
            border: 1px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            font-family: var(--font-body);
            font-size: .9rem;
            color: var(--text-primary);
            transition: border-color .2s;
            user-select: none;
        }
        .custom-select-trigger:hover { border-color: var(--gold); }
        .custom-select-trigger.open  { border-color: var(--gold); }
        .custom-select-trigger.disabled { opacity: .45; cursor: not-allowed; pointer-events: none; }
        .custom-select-trigger .arrow { transition: transform .2s; font-size: .7rem; color: var(--gold); }
        .custom-select-trigger.open .arrow { transform: rotate(180deg); }

        .custom-select-dropdown {
            display: none; position: absolute;
            top: calc(100% + 4px); left: 0; right: 0;
            background: var(--bg-card, #2a1a0e);
            border: 1px solid var(--gold);
            border-radius: 8px;
            max-height: 220px; overflow-y: auto;
            z-index: 999;
            box-shadow: 0 8px 24px rgba(0,0,0,.35);
        }
        .custom-select-dropdown.open { display: block; }

        .custom-select-option {
            padding: 10px 16px; font-size: .88rem;
            color: var(--text-primary); cursor: pointer;
            transition: background .15s;
        }
        .custom-select-option:hover,
        .custom-select-option.focused { background: rgba(139,69,19,.25); color: var(--gold); }
        .custom-select-option.selected { color: var(--gold); font-weight: 600; }

        .cs-search {
            width: 100%; box-sizing: border-box;
            padding: 8px 12px;
            border: none; border-bottom: 1px solid var(--border);
            background: transparent; color: var(--text-primary);
            font-size: .85rem; outline: none;
        }
    </style>
</head>
<body>
<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="dashboard-layout">

    <!-- ── SIDEBAR ─────────────────────────────────────────── -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-avatar">🤠</div>
            <div class="sidebar-name"><?= htmlspecialchars($_SESSION['nombre']) ?></div>
            <div class="sidebar-role">★ <?= $_SESSION['lang'] === 'en' ? 'Client' : 'Cliente' ?> ★</div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-section"><?= $_SESSION['lang'] === 'en' ? 'My Account' : 'Mi Cuenta' ?></div>
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=mis_reservas" class="sidebar-link <?= $datos['subvista']==='mis_reservas'?'active':'' ?>">
                <span class="icon">📋</span><?= $_SESSION['lang'] === 'en' ? 'My Reservations' : 'Mis Reservas' ?>
            </a>
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=nueva_reserva" class="sidebar-link <?= $datos['subvista']==='nueva_reserva'?'active':'' ?>">
                <span class="icon">➕</span><?= $_SESSION['lang'] === 'en' ? 'New Reservation' : 'Nueva Reserva' ?>
            </a>
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=mi_perfil" class="sidebar-link <?= $datos['subvista']==='mi_perfil'?'active':'' ?>">
                <span class="icon">👤</span><?= $_SESSION['lang'] === 'en' ? 'My Profile' : 'Mi Perfil' ?>
            </a>
            <div class="sidebar-section"><?= $_SESSION['lang'] === 'en' ? 'Explore' : 'Explorar' ?></div>
            <a href="<?= APP_URL ?>/index.php?ruta=menu_digital" class="sidebar-link">
                <span class="icon">🍽️</span><?= $_SESSION['lang'] === 'en' ? 'Digital Menu' : 'Menú Digital' ?>
            </a>
            <a href="<?= APP_URL ?>/index.php" class="sidebar-link">
                <span class="icon">🏠</span><?= $_SESSION['lang'] === 'en' ? 'Home' : 'Inicio' ?>
            </a>
            <a href="<?= APP_URL ?>/index.php?ruta=logout" class="sidebar-link" style="color:var(--rust)">
                <span class="icon">🚪</span><?= $_SESSION['lang'] === 'en' ? 'Log Out' : 'Cerrar Sesión' ?>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=nueva_reserva" class="btn-western btn-sm w-100">
                🤠 <?= $_SESSION['lang'] === 'en' ? 'Book a Table' : 'Reservar Mesa' ?>
            </a>
        </div>
    </aside>

    <!-- ── MAIN ────────────────────────────────────────────── -->
    <main class="dashboard-main">

        <?php if (isset($datos['admin_msg'])): ?>
            <div class="alert alert-<?= $datos['admin_msg']['tipo'] ?>" data-auto-dismiss="5000" style="margin-bottom:var(--space-lg)">
                <?= htmlspecialchars($datos['admin_msg']['texto']) ?>
                <button class="alert-close" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit">✕</button>
            </div>
        <?php endif; ?>

        <!-- ════════════════ MIS RESERVAS ════════════════ -->
        <?php if ($datos['subvista'] === 'mis_reservas'): ?>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-xl)">
                <div>
                    <h2 style="margin-bottom:4px"><?= $_SESSION['lang'] === 'en' ? '★ My Reservations' : '★ Mis Reservas' ?></h2>
                    <p style="color:var(--text-muted);font-style:italic">
                        <?= $_SESSION['lang'] === 'en' ? 'Your reservation history' : 'Historial de tus reservas' ?>
                    </p>
                </div>
                <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=nueva_reserva" class="btn-western">
                    + <?= $_SESSION['lang'] === 'en' ? 'New Reservation' : 'Nueva Reserva' ?>
                </a>
            </div>

            <?php if (empty($datos['reservas'])): ?>
                <div class="card" style="text-align:center;padding:var(--space-3xl)">
                    <div style="font-size:5rem;margin-bottom:var(--space-lg)">🏕️</div>
                    <h3 style="color:var(--text-muted);margin-bottom:var(--space-md)">
                        <?= $_SESSION['lang'] === 'en' ? 'No reservations yet, partner!' : '¡Aún no tienes reservas, vaquero!' ?>
                    </h3>
                    <p style="color:var(--text-muted);font-style:italic;margin-bottom:var(--space-xl);max-width:400px;margin-left:auto;margin-right:auto">
                        <?= $_SESSION['lang'] === 'en'
                                ? 'Reserve your table at El Último Corral and enjoy the best western cuisine in Alcalá la Real.'
                                : 'Reserva tu mesa en El Último Corral y disfruta de la mejor cocina western de Alcalá la Real.'
                        ?>
                    </p>
                    <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=nueva_reserva" class="btn-western btn-lg">
                        🤠 <?= $_SESSION['lang'] === 'en' ? 'Make My First Reservation' : 'Hacer Mi Primera Reserva' ?>
                    </a>
                </div>
            <?php else: ?>
                <div style="display:flex;flex-direction:column;gap:var(--space-md)">
                    <?php foreach ($datos['reservas'] as $r): ?>
                        <div class="reserva-card <?= $r['estado'] ?>">
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:var(--space-md)">

                                <div style="flex:1;min-width:200px">
                                    <div style="display:flex;align-items:center;gap:var(--space-sm);margin-bottom:8px">
                                        <span style="font-family:var(--font-heading);font-size:1.1rem;color:var(--text-primary)">
                                            📅 <?= date($_SESSION['lang']==='en'?'F j, Y':'d/m/Y', strtotime($r['fecha'])) ?>
                                        </span>
                                        <span class="badge badge-<?= match($r['estado']){'pendiente'=>'warning','confirmada'=>'success','cancelada'=>'danger','completada'=>'info',default=>'neutral'} ?>">
                                            <?= match($r['estado']){
                                                'pendiente'  => ($_SESSION['lang']==='en'?'Pending':'Pendiente'),
                                                'confirmada' => ($_SESSION['lang']==='en'?'Confirmed':'Confirmada'),
                                                'cancelada'  => ($_SESSION['lang']==='en'?'Cancelled':'Cancelada'),
                                                'completada' => ($_SESSION['lang']==='en'?'Completed':'Completada'),
                                                default      => $r['estado']
                                            } ?>
                                        </span>
                                    </div>

                                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:10px">
                                        <div>
                                            <span style="font-size:.65rem;color:var(--text-muted);letter-spacing:1.5px;text-transform:uppercase;display:block;font-family:var(--font-heading)">
                                                <?= $_SESSION['lang'] === 'en' ? 'Shift' : 'Turno' ?>
                                            </span>
                                            <span style="font-size:.9rem;color:var(--text-primary)"><?= htmlspecialchars($r['turno']) ?></span>
                                            <br><span style="font-size:.8rem;color:var(--gold)"><?= $r['hora_inicio'] ?> – <?= $r['hora_fin'] ?></span>
                                        </div>
                                        <div>
                                            <span style="font-size:.65rem;color:var(--text-muted);letter-spacing:1.5px;text-transform:uppercase;display:block;font-family:var(--font-heading)">
                                                <?= $_SESSION['lang'] === 'en' ? 'Table' : 'Mesa' ?>
                                            </span>
                                            <span style="font-size:.9rem;color:var(--gold);font-family:var(--font-heading)"><?= htmlspecialchars($r['mesa']) ?></span>
                                            <br><span style="font-size:.8rem;color:var(--text-muted)"><?= htmlspecialchars($r['zona']) ?></span>
                                        </div>
                                        <div>
                                            <span style="font-size:.65rem;color:var(--text-muted);letter-spacing:1.5px;text-transform:uppercase;display:block;font-family:var(--font-heading)">
                                                <?= $_SESSION['lang'] === 'en' ? 'Diners' : 'Comensales' ?>
                                            </span>
                                            <span style="font-size:1.1rem">👥 <?= $r['num_comensales'] ?></span>
                                        </div>
                                    </div>
                                </div>

                                <?php if (in_array($r['estado'], ['pendiente','confirmada']) && strtotime($r['fecha']) >= strtotime(date('Y-m-d'))): ?>
                                    <div style="display:flex;flex-direction:column;gap:8px;min-width:160px">
                                        <button class="btn-ghost btn-western btn-sm" data-modal="modalEditReserva<?= $r['id'] ?>">
                                            ✏️ <?= $_SESSION['lang'] === 'en' ? 'Modify' : 'Modificar' ?>
                                        </button>
                                        <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=mis_reservas">
                                            <input type="hidden" name="reserva_id" value="<?= $r['id'] ?>">
                                            <button type="submit" name="cancelar_reserva" class="btn-danger btn-western btn-sm w-100"
                                                    onclick="return confirm('<?= $_SESSION['lang'] === 'en' ? 'Cancel this reservation?' : '¿Cancelar esta reserva?' ?>')">
                                                ✕ <?= $_SESSION['lang'] === 'en' ? 'Cancel' : 'Cancelar' ?>
                                            </button>
                                        </form>
                                    </div>
                                <?php elseif ($r['estado'] === 'completada'): ?>
                                    <div style="font-size:1.5rem" title="<?= $_SESSION['lang'] === 'en' ? 'Completed' : 'Completada' ?>">⭐</div>
                                <?php endif; ?>
                            </div>

                            <?php if ($r['observaciones']): ?>
                                <div style="margin-top:var(--space-md);padding:10px var(--space-md);background:var(--bg-surface);border-radius:var(--radius-sm);font-size:.85rem;font-style:italic;color:var(--text-muted)">
                                    💬 <?= htmlspecialchars($r['observaciones']) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Modal editar reserva -->
                        <div class="modal-overlay" id="modalEditReserva<?= $r['id'] ?>">
                            <div class="modal" style="max-width:500px">
                                <div class="modal-header">
                                    <span class="modal-title">✏️ <?= $_SESSION['lang'] === 'en' ? 'Modify Reservation' : 'Modificar Reserva' ?></span>
                                    <button class="modal-close" data-close-modal>✕</button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=mis_reservas">
                                        <input type="hidden" name="reserva_id" value="<?= $r['id'] ?>">
                                        <div class="form-group">
                                            <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Date' : '✦ Fecha' ?></label>
                                            <input type="date" name="fecha" class="form-control" value="<?= $r['fecha'] ?>"
                                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group">
                                                <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Diners' : '✦ Comensales' ?></label>
                                                <input type="number" name="comensales" class="form-control" value="<?= $r['num_comensales'] ?>" min="1" max="20" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Table' : '✦ Mesa' ?></label>
                                                <input type="hidden" name="turno_id" value="<?= $r['turno_id'] ?>">
                                                <input type="hidden" name="mesa_id" value="<?= $r['mesa_id'] ?>">
                                                <input type="text" class="form-control" value="<?= htmlspecialchars($r['mesa']) ?> — <?= htmlspecialchars($r['zona']) ?>" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Notes' : '✦ Observaciones' ?></label>
                                            <textarea name="observaciones" class="form-control" rows="2" style="resize:vertical"><?= htmlspecialchars($r['observaciones'] ?? '') ?></textarea>
                                        </div>
                                        <button type="submit" name="modificar_reserva" class="btn-western w-100">
                                            <?= $_SESSION['lang'] === 'en' ? '✦ Save Changes ✦' : '✦ Guardar Cambios ✦' ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- ════════════════ NUEVA RESERVA ════════════════ -->
        <?php elseif ($datos['subvista'] === 'nueva_reserva'): ?>
            <div style="margin-bottom:var(--space-xl)">
                <h2 style="margin-bottom:4px"><?= $_SESSION['lang'] === 'en' ? '★ New Reservation' : '★ Nueva Reserva' ?></h2>
                <p style="color:var(--text-muted);font-style:italic">
                    <?= $_SESSION['lang'] === 'en' ? 'Reserve your table at the Corral' : 'Reserva tu mesa en el Corral' ?>
                </p>
            </div>

            <div style="display:grid;grid-template-columns:1fr 380px;gap:var(--space-xl);align-items:start">

                <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=nueva_reserva" data-validate>

                    <div class="card" style="margin-bottom:var(--space-lg)">
                        <div class="card-header">
                            <span style="font-size:1.2rem">📅</span>
                            <span class="card-title"><?= $_SESSION['lang'] === 'en' ? 'Date & Shift' : 'Fecha y Turno' ?></span>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="fecha_reserva">
                                    <?= $_SESSION['lang'] === 'en' ? '✦ Date' : '✦ Fecha' ?>
                                </label>
                                <input type="date" id="fecha_reserva" name="fecha" class="form-control" required
                                       min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="turno_id">
                                    <?= $_SESSION['lang'] === 'en' ? '✦ Shift' : '✦ Turno' ?>
                                </label>
                                <select id="turno_id" name="turno_id" class="form-control" required>
                                    <option value="">— <?= $_SESSION['lang'] === 'en' ? 'Select Shift' : 'Selecciona Turno' ?> —</option>
                                    <?php foreach ($datos['turnos'] as $t): ?>
                                        <option value="<?= $t['id'] ?>">
                                            <?= htmlspecialchars($t['nombre']) ?> · <?= $t['hora_inicio'] ?>–<?= $t['hora_fin'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="comensales_reserva">
                                <?= $_SESSION['lang'] === 'en' ? '✦ Number of Diners' : '✦ Número de Comensales' ?>
                            </label>
                            <input type="number" id="comensales_reserva" name="comensales" class="form-control"
                                   min="1" max="20" value="2" required>
                        </div>
                    </div>

                    <div class="card" style="margin-bottom:var(--space-lg)">
                        <div class="card-header">
                            <span style="font-size:1.2rem">🪑</span>
                            <span class="card-title"><?= $_SESSION['lang'] === 'en' ? 'Select Table' : 'Seleccionar Mesa' ?></span>
                        </div>
                        <p style="color:var(--text-muted);font-size:.85rem;font-style:italic;margin-bottom:var(--space-md)">
                            <?= $_SESSION['lang'] === 'en'
                                    ? 'Select a date and shift first, then choose your table.'
                                    : 'Primero selecciona fecha y turno, luego elige tu mesa.'
                            ?>
                        </p>
                        <input type="hidden" name="mesa_id" id="mesa_id_hidden" required>
                        <div id="mesaGrid" class="mesas-grid" style="grid-template-columns:repeat(auto-fill,minmax(110px,1fr))">
                            <p style="grid-column:1/-1;color:var(--text-muted);font-style:italic;padding:var(--space-lg);text-align:center">
                                <?= $_SESSION['lang'] === 'en' ? 'Choose a date and shift above.' : 'Elige fecha y turno arriba.' ?>
                            </p>
                        </div>
                    </div>

                    <div class="card" style="margin-bottom:var(--space-lg)">
                        <div class="card-header">
                            <span style="font-size:1.2rem">💬</span>
                            <span class="card-title"><?= $_SESSION['lang'] === 'en' ? 'Notes' : 'Observaciones' ?></span>
                        </div>
                        <div class="form-group" style="margin:0">
                            <textarea name="observaciones" class="form-control" rows="3" style="resize:vertical"
                                      placeholder="<?= $_SESSION['lang'] === 'en' ? 'Allergies, high chair, special occasion...' : 'Alergias, trona, ocasión especial...' ?>"></textarea>
                        </div>
                    </div>

                    <button type="submit" name="hacer_reserva" class="btn-western btn-lg w-100">
                        🤠 <?= $_SESSION['lang'] === 'en' ? '✦ Confirm Reservation ✦' : '✦ Confirmar Reserva ✦' ?>
                    </button>
                </form>

                <div style="position:sticky;top:84px">
                    <div class="card card-gold" style="margin-bottom:var(--space-md)">
                        <div style="font-size:2.5rem;text-align:center;margin-bottom:var(--space-md)">🤠</div>
                        <h3 style="text-align:center;font-size:1rem;margin-bottom:var(--space-md)">
                            <?= $_SESSION['lang'] === 'en' ? 'Reservation Tips' : 'Consejos para Reservar' ?>
                        </h3>
                        <?php $tips = $_SESSION['lang'] === 'en' ? [
                                '📅 Reservations can be made up to 30 days in advance',
                                '⏰ You can modify up to 2 hours before',
                                '✉️ You will receive an email confirmation',
                                '👥 Indicate the exact number of diners',
                                '💬 Add any allergy notes'
                        ] : [
                                '📅 Puedes reservar hasta 30 días antes',
                                '⏰ Puedes modificar hasta 2 horas antes',
                                '✉️ Recibirás un correo de confirmación',
                                '👥 Indica el número exacto de comensales',
                                '💬 Añade notas sobre alergias'
                        ]; ?>
                        <?php foreach ($tips as $tip): ?>
                            <div style="display:flex;align-items:flex-start;gap:10px;padding:8px 0;border-bottom:1px solid var(--border);font-size:.85rem;color:var(--text-secondary)">
                                <div class="timeline-dot" style="margin-top:5px"></div>
                                <span><?= $tip ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <span>🕐</span>
                            <span class="card-title"><?= $_SESSION['lang'] === 'en' ? 'Our Schedule' : 'Nuestro Horario' ?></span>
                        </div>
                        <?php foreach ($datos['turnos'] as $t): ?>
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid var(--border)">
                                <span style="font-size:.85rem;color:var(--text-secondary)"><?= htmlspecialchars($t['nombre']) ?></span>
                                <span style="font-family:var(--font-heading);font-size:.85rem;color:var(--gold)"><?= $t['hora_inicio'] ?> – <?= $t['hora_fin'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- ════════════════ MI PERFIL ════════════════ -->
        <?php elseif ($datos['subvista'] === 'mi_perfil'): ?>
            <?php $u = $datos['usuario']; ?>
            <div style="margin-bottom:var(--space-xl)">
                <h2 style="margin-bottom:4px"><?= $_SESSION['lang'] === 'en' ? '★ My Profile' : '★ Mi Perfil' ?></h2>
                <p style="color:var(--text-muted);font-style:italic">
                    <?= $_SESSION['lang'] === 'en' ? 'Update your personal information' : 'Actualiza tu información personal' ?>
                </p>
            </div>

            <div style="display:grid;grid-template-columns:300px 1fr;gap:var(--space-xl);align-items:start">

                <!-- Profile card -->
                <div class="card card-gold" style="text-align:center">
                    <div class="profile-avatar-large">🤠</div>
                    <h3 style="font-size:1.1rem;margin-bottom:4px"><?= htmlspecialchars($u['nombre'].' '.$u['apellidos']) ?></h3>
                    <p style="color:var(--text-muted);font-size:.85rem;margin-bottom:var(--space-md)"><?= htmlspecialchars($u['email']) ?></p>
                    <span class="badge badge-cliente" style="margin:0 auto">
                        ★ <?= $_SESSION['lang'] === 'en' ? 'Client' : 'Cliente' ?> ★
                    </span>

                    <div class="separator"></div>

                    <div style="text-align:left">
                        <?php $meta = [
                                ['label' => $_SESSION['lang']==='en'?'DNI / ID':'DNI',          'val' => $u['dni']],
                                ['label' => $_SESSION['lang']==='en'?'Phone':'Teléfono',         'val' => $u['telefono'] ?? '—'],
                                ['label' => $_SESSION['lang']==='en'?'Province':'Provincia',     'val' => $u['provincia'] ?? '—'],
                                ['label' => $_SESSION['lang']==='en'?'Municipality':'Municipio', 'val' => $u['municipio'] ?? '—'],
                                ['label' => $_SESSION['lang']==='en'?'Registered':'Registrado',  'val' => date('d/m/Y',strtotime($u['fecha_registro']))],
                        ]; ?>
                        <?php foreach ($meta as $m): ?>
                            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:.82rem">
                                <span style="color:var(--text-muted);font-family:var(--font-heading);letter-spacing:1px"><?= $m['label'] ?></span>
                                <span style="color:var(--text-primary)"><?= htmlspecialchars($m['val']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($u['google_id']): ?>
                        <div style="margin-top:var(--space-md);padding:10px;background:rgba(66,133,244,.1);border:1px solid rgba(66,133,244,.3);border-radius:var(--radius-sm)">
                            <span style="font-size:.75rem;color:#64b5f6;font-family:var(--font-heading);letter-spacing:1px">
                                🔗 <?= $_SESSION['lang'] === 'en' ? 'Linked with Google' : 'Vinculado con Google' ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Edit form -->
                <div class="card">
                    <div class="card-header">
                        <span>✏️</span>
                        <span class="card-title"><?= $_SESSION['lang'] === 'en' ? 'Edit Information' : 'Editar Información' ?></span>
                    </div>
                    <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=mi_perfil" data-validate>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ First Name' : '✦ Nombre' ?></label>
                                <input type="text" name="nombre" class="form-control" required
                                       value="<?= htmlspecialchars($u['nombre']) ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Surname(s)' : '✦ Apellidos' ?></label>
                                <input type="text" name="apellidos" class="form-control" required
                                       value="<?= htmlspecialchars($u['apellidos']) ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Phone' : '✦ Teléfono' ?></label>
                            <input type="tel" name="telefono" class="form-control"
                                   value="<?= htmlspecialchars($u['telefono'] ?? '') ?>" placeholder="6XX XXX XXX">
                        </div>

                        <div class="western-divider" style="margin:var(--space-lg) 0">
                            <span>📍 <?= $_SESSION['lang'] === 'en' ? 'Address (Catastro)' : 'Dirección (Catastro)' ?></span>
                        </div>

                        <!-- Hidden inputs para el POST -->
                        <input type="hidden" id="perfil_provincia" name="provincia" value="<?= htmlspecialchars($u['provincia'] ?? '') ?>">
                        <input type="hidden" id="perfil_municipio" name="municipio" value="<?= htmlspecialchars($u['municipio'] ?? '') ?>">
                        <input type="hidden" id="perfil_via"       name="via"       value="<?= htmlspecialchars($u['via'] ?? '') ?>">

                        <div class="form-group">
                            <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Province' : '✦ Provincia' ?></label>
                            <div class="custom-select-wrapper">
                                <div class="custom-select-trigger disabled" id="cs-perfil-provincia">
                                    <span class="cs-label"><?= $u['provincia'] ? htmlspecialchars($u['provincia']) : ($_SESSION['lang']==='en'?'Loading...':'Cargando...') ?></span>
                                    <span class="arrow">▼</span>
                                </div>
                                <div class="custom-select-dropdown" id="dd-perfil-provincia">
                                    <input class="cs-search" type="text" placeholder="<?= $_SESSION['lang']==='en'?'Search...':'Buscar...' ?>">
                                    <div class="cs-options"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Municipality' : '✦ Municipio' ?></label>
                                <div class="custom-select-wrapper">
                                    <div class="custom-select-trigger disabled" id="cs-perfil-municipio">
                                        <span class="cs-label"><?= $u['municipio'] ? htmlspecialchars($u['municipio']) : '— '.($_SESSION['lang']==='en'?'Municipality':'Municipio').' —' ?></span>
                                        <span class="arrow">▼</span>
                                    </div>
                                    <div class="custom-select-dropdown" id="dd-perfil-municipio">
                                        <input class="cs-search" type="text" placeholder="<?= $_SESSION['lang']==='en'?'Search...':'Buscar...' ?>">
                                        <div class="cs-options"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Street' : '✦ Vía' ?></label>
                                <div class="custom-select-wrapper">
                                    <div class="custom-select-trigger disabled" id="cs-perfil-via">
                                        <span class="cs-label"><?= $u['via'] ? htmlspecialchars($u['via']) : '— '.($_SESSION['lang']==='en'?'Street':'Vía').' —' ?></span>
                                        <span class="arrow">▼</span>
                                    </div>
                                    <div class="custom-select-dropdown" id="dd-perfil-via">
                                        <input class="cs-search" type="text" placeholder="<?= $_SESSION['lang']==='en'?'Search...':'Buscar...' ?>">
                                        <div class="cs-options"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Number / Floor' : '✦ Número / Piso' ?></label>
                            <input type="text" name="numero" class="form-control"
                                   value="<?= htmlspecialchars($u['numero'] ?? '') ?>"
                                   placeholder="<?= $_SESSION['lang'] === 'en' ? 'e.g. 14, 2nd floor B' : 'Ej. 14, 2º B' ?>"
                                   maxlength="30">
                        </div>

                        <p class="form-hint">
                            🛰️ <?= $_SESSION['lang'] === 'en'
                                    ? 'Catastro integration: Select Province first to load municipalities and streets.'
                                    : 'Integración Catastro: Selecciona Provincia para cargar municipios y vías.'
                            ?>
                        </p>

                        <div style="margin-top:var(--space-xl)">
                            <button type="submit" name="actualizar_perfil" class="btn-western btn-lg w-100">
                                <?= $_SESSION['lang'] === 'en' ? '✦ Save Profile ✦' : '✦ Guardar Perfil ✦' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

    </main>
</div>

<script src="<?= APP_URL ?>/js/main.js"></script>
<script>
    // ── Mesas disponibles ─────────────────────────────────────────
    const turnoSel = document.getElementById('turno_id');
    const fechaSel = document.getElementById('fecha_reserva');
    const mesaGrid = document.getElementById('mesaGrid');
    const mesaInput = document.getElementById('mesa_id_hidden');

    if (turnoSel && fechaSel && mesaGrid) {
        function loadMesasDisponibles() {
            const turno = turnoSel.value;
            const fecha = fechaSel.value;
            if (!turno || !fecha) return;

            mesaGrid.innerHTML = '<p style="grid-column:1/-1;color:var(--text-muted);font-style:italic;text-align:center;padding:var(--space-lg)">🔍 <?= $_SESSION['lang'] === 'en' ? 'Searching available tables...' : 'Buscando mesas disponibles...' ?></p>';

            fetch(`<?= APP_URL ?>/controllers/api/mesas_libres.php?turno_id=${turno}&fecha=${fecha}`)
                .then(r => r.json())
                .then(mesas => {
                    mesaGrid.innerHTML = '';
                    if (!mesas.length) {
                        mesaGrid.innerHTML = '<p style="grid-column:1/-1;text-align:center;padding:var(--space-xl);color:var(--gold);font-style:italic">🤠 <?= $_SESSION['lang'] === 'en' ? 'No tables available for this shift.' : 'No hay mesas disponibles en este turno.' ?></p>';
                        return;
                    }
                    const comensales = parseInt(document.getElementById('comensales_reserva').value) || 1;
                    mesas.forEach(m => {
                        const div = document.createElement('div');
                        const sinAforo = m.capacidad < comensales;
                        div.className = sinAforo ? 'mesa-card ocupada' : 'mesa-card libre';
                        div.dataset.id = m.id;
                        div.innerHTML = `
                            <div class="mesa-estado-dot"></div>
                            <span class="mesa-numero">${m.numero}</span>
                            <span class="mesa-capacidad">👥 ${m.capacidad}</span>
                            <span style="font-size:.6rem;color:var(--text-muted);letter-spacing:1px;text-align:center">${m.zona_nombre}</span>
                            ${sinAforo ? `<span style="font-size:.55rem;color:#ef5350;letter-spacing:1px;text-align:center"><?= $_SESSION['lang']==='en'?'INSUFFICIENT CAPACITY':'AFORO INSUFICIENTE' ?></span>` : ''}
                        `;
                        if (!sinAforo) {
                            div.addEventListener('click', () => {
                                mesaGrid.querySelectorAll('.mesa-card').forEach(c => {
                                    c.classList.remove('selected','reservada');
                                    c.style.borderColor = '';
                                    c.style.boxShadow = '';
                                });
                                div.classList.add('selected','reservada');
                                div.style.borderColor = 'var(--gold)';
                                div.style.boxShadow = '0 0 20px rgba(201,168,76,.3)';
                                mesaInput.value = m.id;
                            });
                        } else {
                            div.style.cursor = 'not-allowed';
                            div.style.opacity = '.5';
                        }
                        mesaGrid.appendChild(div);
                    });
                })
                .catch(() => {
                    mesaGrid.innerHTML = '<p style="grid-column:1/-1;color:#ef5350;text-align:center;padding:var(--space-lg)"><?= $_SESSION['lang'] === 'en' ? 'Error loading tables.' : 'Error al cargar mesas.' ?></p>';
                });
        }

        turnoSel.addEventListener('change', loadMesasDisponibles);
        document.getElementById('comensales_reserva').addEventListener('change', loadMesasDisponibles);
        fechaSel.addEventListener('change', loadMesasDisponibles);
    }
</script>
<script>
    // ── Custom Select (reutilizable) ──────────────────────────────
    function buildCS(triggerId, dropdownId, hiddenId, placeholder) {
        const trigger  = document.getElementById(triggerId);
        const dropdown = document.getElementById(dropdownId);
        const hidden   = document.getElementById(hiddenId);
        if (!trigger || !dropdown || !hidden) return null;

        const search  = dropdown.querySelector('.cs-search');
        const optWrap = dropdown.querySelector('.cs-options');
        let allItems  = [];

        trigger.addEventListener('click', () => {
            if (trigger.classList.contains('disabled')) return;
            const isOpen = dropdown.classList.toggle('open');
            trigger.classList.toggle('open', isOpen);
            if (isOpen) { search.value = ''; renderOptions(allItems); search.focus(); }
        });

        search.addEventListener('input', () => {
            const q = search.value.toLowerCase();
            renderOptions(allItems.filter(i => i.toLowerCase().includes(q)));
        });

        document.addEventListener('click', e => {
            if (!trigger.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
                trigger.classList.remove('open');
            }
        });

        function renderOptions(items) {
            optWrap.innerHTML = '';
            items.forEach(val => {
                const div = document.createElement('div');
                div.className = 'custom-select-option' + (val === hidden.value ? ' selected' : '');
                div.textContent = val;
                div.addEventListener('click', () => {
                    hidden.value = val;
                    trigger.querySelector('.cs-label').textContent = val;
                    dropdown.classList.remove('open');
                    trigger.classList.remove('open');
                    trigger.dispatchEvent(new CustomEvent('cs-change', { detail: val }));
                });
                optWrap.appendChild(div);
            });
        }

        return {
            load(items) {
                allItems = items;
                trigger.classList.remove('disabled');
                // Si ya hay valor guardado, mantenerlo marcado como selected
                if (hidden.value && items.includes(hidden.value)) {
                    trigger.querySelector('.cs-label').textContent = hidden.value;
                }
                renderOptions(items);
            },
            reset(ph) {
                allItems = [];
                hidden.value = '';
                trigger.querySelector('.cs-label').textContent = ph;
                trigger.classList.add('disabled');
                dropdown.classList.remove('open');
                trigger.classList.remove('open');
                optWrap.innerHTML = '';
            },
            get trigger() { return trigger; }
        };
    }

    // ── Catastro Mi Perfil ────────────────────────────────────────
    (function () {
        const apiUrl = '<?= APP_URL ?>/controllers/api/catastro.php';
        const lang   = '<?= $_SESSION['lang'] ?? 'es' ?>';

        const ph = {
            prov: lang === 'en' ? '— Province —'     : '— Provincia —',
            mun:  lang === 'en' ? '— Municipality —' : '— Municipio —',
            via:  lang === 'en' ? '— Street —'       : '— Vía —',
        };

        const csProv = buildCS('cs-perfil-provincia', 'dd-perfil-provincia', 'perfil_provincia', ph.prov);
        const csMun  = buildCS('cs-perfil-municipio', 'dd-perfil-municipio', 'perfil_municipio', ph.mun);
        const csVia  = buildCS('cs-perfil-via',       'dd-perfil-via',       'perfil_via',       ph.via);

        if (!csProv) return; // no estamos en mi_perfil

        function post(body) {
            return fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body
            }).then(r => r.json());
        }

        const provActual = document.getElementById('perfil_provincia').value;
        const munActual  = document.getElementById('perfil_municipio').value;
        const viaActual  = document.getElementById('perfil_via').value;

        // 1. Cargar provincias y preseleccionar la guardada
        post('accion=provincias').then(data => {
            const nombres = data.map(p => typeof p === 'object' ? p.nombre : p);
            csProv.load(nombres);

            // Si ya tenía provincia → cargar municipios automáticamente
            if (provActual) {
                post(`accion=municipios&provincia=${encodeURIComponent(provActual)}`).then(data2 => {
                    const muns = data2.map(m => typeof m === 'object' ? (m.nombre ?? m) : m);
                    csMun.load(muns);

                    // Si ya tenía municipio → cargar vías automáticamente
                    if (munActual) {
                        post(`accion=vias&provincia=${encodeURIComponent(provActual)}&municipio=${encodeURIComponent(munActual)}`).then(data3 => {
                            const vias = data3.map(v => typeof v === 'object' ? (v.nombre ?? v) : v);
                            csVia.load(vias);
                        });
                    }
                });
            }
        });

        // 2. Provincia → Municipios
        csProv.trigger.addEventListener('cs-change', e => {
            csMun.reset(ph.mun);
            csVia.reset(ph.via);
            post(`accion=municipios&provincia=${encodeURIComponent(e.detail)}`).then(data => {
                const nombres = data.map(m => typeof m === 'object' ? (m.nombre ?? m) : m);
                csMun.load(nombres);
            });
        });

        // 3. Municipio → Vías
        csMun.trigger.addEventListener('cs-change', e => {
            csVia.reset(ph.via);
            const prov = document.getElementById('perfil_provincia').value;
            post(`accion=vias&provincia=${encodeURIComponent(prov)}&municipio=${encodeURIComponent(e.detail)}`).then(data => {
                const nombres = data.map(v => typeof v === 'object' ? (v.nombre ?? v) : v);
                csVia.load(nombres);
            });
        });
    })();
</script>
</body>
</html>