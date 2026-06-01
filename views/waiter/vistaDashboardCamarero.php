<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'es' ?>" data-theme="<?= $_SESSION['theme'] ?? 'dark' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_SESSION['lang'] === 'en' ? 'Waiter Panel' : 'Panel Camarero' ?> — El Último Corral</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Cinzel+Decorative:wght@700&family=Lora:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/styles/main.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/styles/vistaDashboardCamarero.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🤵</text></svg>">
</head>
<body>
<?php require_once __DIR__ . '/../partials/navbar.php'; ?>

<div class="dashboard-layout">

    <!-- ── SIDEBAR ─────────────────────────────────────────── -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-avatar">🤵</div>
            <div class="sidebar-name"><?= htmlspecialchars($_SESSION['nombre']) ?></div>
            <div class="sidebar-role">★ <?= $_SESSION['lang'] === 'en' ? 'Waiter' : 'Camarero' ?> ★</div>
        </div>
        <nav class="sidebar-nav">
            <div class="sidebar-section"><?= $_SESSION['lang'] === 'en' ? 'Work' : 'Trabajo' ?></div>
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=servicios" class="sidebar-link <?= $datos['subvista']==='servicios'?'active':'' ?>">
                <span class="icon">🍽️</span><?= $_SESSION['lang'] === 'en' ? 'Services' : 'Servicios' ?>
            </a>
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=mesas_libres" class="sidebar-link <?= $datos['subvista']==='mesas_libres'?'active':'' ?>">
                <span class="icon">🪑</span><?= $_SESSION['lang'] === 'en' ? 'Free Tables' : 'Mesas Libres' ?>
            </a>
            <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=reservas_camarero" class="sidebar-link <?= $datos['subvista']==='reservas_camarero'?'active':'' ?>">
                <span class="icon">📋</span><?= $_SESSION['lang'] === 'en' ? "Today's Reservations" : 'Reservas Hoy' ?>
            </a>
            <div class="sidebar-section"><?= $_SESSION['lang'] === 'en' ? 'General' : 'General' ?></div>
            <a href="<?= APP_URL ?>/index.php" class="sidebar-link"><span class="icon">🏠</span><?= $_SESSION['lang'] === 'en' ? 'Home' : 'Inicio' ?></a>
            <a href="<?= APP_URL ?>/index.php?ruta=logout" class="sidebar-link" style="color:var(--rust)"><span class="icon">🚪</span><?= $_SESSION['lang'] === 'en' ? 'Log Out' : 'Salir' ?></a>
        </nav>
    </aside>

    <!-- ── MAIN ────────────────────────────────────────────── -->
    <main class="dashboard-main">

        <?php if (isset($datos['admin_msg'])): ?>
            <div class="alert alert-<?= $datos['admin_msg']['tipo'] ?>" data-auto-dismiss="5000" style="margin-bottom:var(--space-lg)">
                <?= htmlspecialchars($datos['admin_msg']['texto']) ?>
                <button class="alert-close" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit">✕</button>
            </div>
        <?php endif; ?>

        <!-- ════════════════ SERVICIOS ════════════════ -->
        <?php if ($datos['subvista'] === 'servicios'): ?>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-xl)">
                <div>
                    <h2 style="margin-bottom:4px"><?= $_SESSION['lang'] === 'en' ? '★ Services' : '★ Servicios' ?></h2>
                    <p style="color:var(--text-muted);font-style:italic"><?= date('d/m/Y') ?> · <?= $_SESSION['lang'] === 'en' ? 'Today' : 'Hoy' ?></p>
                </div>
                <button class="btn-western" data-modal="modalAbrirServicio">
                    + <?= $_SESSION['lang'] === 'en' ? 'New Service' : 'Nuevo Servicio' ?>
                </button>
            </div>

            <?php if (empty($datos['servicios'])): ?>
                <div class="card" style="text-align:center;padding:var(--space-2xl)">
                    <div style="font-size:4rem;margin-bottom:var(--space-md)">🤠</div>
                    <h3 style="color:var(--text-muted);font-size:1.2rem;margin-bottom:8px">
                        <?= $_SESSION['lang'] === 'en' ? 'No active services today.' : 'No hay servicios hoy.' ?>
                    </h3>
                    <p style="color:var(--text-muted);font-style:italic;margin-bottom:var(--space-xl)">
                        <?= $_SESSION['lang'] === 'en' ? 'The corral is quiet... open a new service to get started.' : 'El corral está tranquilo... abre un servicio para empezar.' ?>
                    </p>
                    <button class="btn-western btn-lg" data-modal="modalAbrirServicio">
                        🍽️ <?= $_SESSION['lang'] === 'en' ? 'Open First Service' : 'Abrir Primer Servicio' ?>
                    </button>
                </div>
            <?php else: ?>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:var(--space-md)">
                    <?php foreach ($datos['servicios'] as $s): ?>
                        <div class="card" style="border-color:<?= $s['estado']==='abierto'?'rgba(76,175,80,.4)':'var(--border)' ?>">
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:var(--space-md)">
                                <div>
                                    <div style="font-family:var(--font-heading);font-size:1.2rem;color:var(--gold)">Mesa <?= htmlspecialchars($s['mesa']) ?></div>
                                    <div style="font-size:.8rem;color:var(--text-muted)"><?= htmlspecialchars($s['zona']) ?> · <?= htmlspecialchars($s['turno']) ?></div>
                                </div>
                                <span class="badge badge-<?= $s['estado']==='abierto'?'success':'neutral' ?>"><?= $s['estado'] ?></span>
                            </div>
                            <div style="display:flex;justify-content:space-between;margin-bottom:var(--space-md)">
                                <span style="font-size:.85rem;color:var(--text-secondary)">👥 <?= $s['num_comensales'] ?> <?= $_SESSION['lang'] === 'en' ? 'diners' : 'comensales' ?></span>
                                <span style="font-family:var(--font-heading);color:var(--gold)">
    <?= number_format($s['total_actual'] ?? $s['total'], 2) ?> €
</span>                            </div>
                            <?php if ($s['estado'] === 'abierto'): ?>
                                <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=servicio_activo&sid=<?= $s['id'] ?>" class="btn-western w-100 btn-sm">
                                    🎯 <?= $_SESSION['lang'] === 'en' ? 'Manage Service' : 'Gestionar Servicio' ?>
                                </a>
                            <?php else: ?>
                                <div style="font-size:.8rem;color:var(--text-muted);text-align:center;font-style:italic;margin-bottom:var(--space-sm)">
                                    <?= $_SESSION['lang'] === 'en' ? 'Closed' : 'Cerrado' ?> · <?= $s['closed_at'] ? date('H:i',strtotime($s['closed_at'])) : '' ?>
                                </div>
                                <a href="<?= APP_URL ?>/controllers/api/descargar_ticket.php?id=<?= $s['id'] ?>"
                                   class="btn-gold btn-western btn-sm w-100"
                                   target="_blank">
                                    🧾 <?= $_SESSION['lang'] === 'en' ? 'Download Ticket' : 'Descargar Ticket' ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Reservas del día (resumen) -->
            <?php if (!empty($datos['reservas_hoy'])): ?>
                <div style="margin-top:var(--space-xl)">
                    <div class="western-divider"><span><?= $_SESSION['lang'] === 'en' ? 'Pending Reservations' : 'Reservas Pendientes' ?></span></div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:var(--space-md)">
                        <?php foreach (array_filter($datos['reservas_hoy'], fn($r) => $r['estado'] === 'pendiente') as $r): ?>
                            <div class="card" style="border-color:rgba(201,168,76,.3)">
                                <div style="font-family:var(--font-heading);color:var(--text-primary);margin-bottom:6px"><?= htmlspecialchars($r['cliente']) ?></div>
                                <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:10px">
                                    Mesa <?= htmlspecialchars($r['mesa']) ?> · <?= $r['hora_inicio'] ?> · 👥 <?= $r['num_comensales'] ?>
                                </div>
                                <div style="display:flex;gap:6px">
                                    <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=servicios" style="flex:1">
                                        <input type="hidden" name="mesa_id" value="<?= $r['mesa_id'] ?>">
                                        <input type="hidden" name="turno_id" value="<?= $r['turno_id'] ?>">
                                        <input type="hidden" name="comensales" value="<?= $r['num_comensales'] ?>">
                                        <input type="hidden" name="reserva_id" value="<?= $r['id'] ?>">
                                        <button type="submit" name="abrir_servicio" class="btn-western btn-sm w-100">
                                            ▶ <?= $_SESSION['lang'] === 'en' ? 'Start' : 'Iniciar' ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ════════════════ SERVICIO ACTIVO ════════════════ -->
        <?php elseif ($datos['subvista'] === 'servicio_activo'): ?>
            <?php $s = $datos['servicio']; $sid = $datos['sid']; ?>

            <?php if (!$s): ?>
                <div class="card" style="text-align:center;padding:var(--space-2xl)">
                    <p style="color:var(--text-muted)"><?= $_SESSION['lang'] === 'en' ? 'Service not found.' : 'Servicio no encontrado.' ?></p>
                </div>
            <?php else: ?>

                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--space-xl);flex-wrap:wrap;gap:var(--space-md)">
                    <div>
                        <h2 style="margin-bottom:4px">
                            🍽️ <?= $_SESSION['lang'] === 'en' ? 'Active Service' : 'Servicio Activo' ?>
                            — <span style="color:var(--gold)">Mesa <?= htmlspecialchars($s['mesa']) ?></span>
                        </h2>
                        <p style="color:var(--text-muted)"><?= htmlspecialchars($s['zona']) ?> · <?= htmlspecialchars($s['turno']) ?> · 👥 <?= $s['num_comensales'] ?> <?= $_SESSION['lang'] === 'en' ? 'diners' : 'comensales' ?></p>
                    </div>
                    <div style="display:flex;gap:10px">
                        <a href="<?= APP_URL ?>/index.php?ruta=dashboard&sv=servicios" class="btn-ghost btn-western btn-sm">← <?= $_SESSION['lang'] === 'en' ? 'Back' : 'Volver' ?></a>
                        <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=servicios">
                            <input type="hidden" name="servicio_id" value="<?= $sid ?>">
                            <button type="submit" name="cerrar_servicio" class="btn-gold btn-western"
                                    onclick="return confirm('<?= $_SESSION['lang'] === 'en' ? 'Close service and generate ticket?' : '¿Cerrar servicio y generar ticket?' ?>')">
                                💳 <?= $_SESSION['lang'] === 'en' ? 'Close & Charge' : 'Cerrar y Cobrar' ?>
                            </button>
                        </form>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 380px;gap:var(--space-lg);align-items:start">

                    <div>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:var(--space-lg)">
                            <button class="categoria-btn active" onclick="filterCat('all', this)">
                                🍽️ <?= $_SESSION['lang'] === 'en' ? 'All' : 'Todo' ?>
                            </button>
                            <?php foreach ($datos['categorias'] as $cat): ?>
                                <button class="categoria-btn" onclick="filterCat('<?= $cat['id'] ?>', this)">
                                    <?= $cat['icono'] ?> <?= $_SESSION['lang'] === 'en' ? $cat['nombre_en'] : $cat['nombre_es'] ?>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:var(--space-sm)" id="itemsGrid">
                            <?php foreach ($datos['items'] as $item): ?>
                                <div class="card item-card" data-cat="<?= $item['categoria_id'] ?>"
                                     style="cursor:pointer;padding:var(--space-md);transition:all .2s"
                                     onclick="openAddItem(<?= $item['id'] ?>, '<?= addslashes($_SESSION['lang']==='en'?$item['nombre_en']:$item['nombre_es']) ?>', <?= $item['precio'] ?>)">
                                    <div style="font-size:1.8rem;text-align:center;margin-bottom:8px"><?= $item['icono'] ?? '🍽️' ?></div>
                                    <div style="font-size:.85rem;color:var(--text-primary);font-weight:600;text-align:center;margin-bottom:4px">
                                        <?= htmlspecialchars($_SESSION['lang'] === 'en' ? $item['nombre_en'] : $item['nombre_es']) ?>
                                    </div>
                                    <div style="font-family:var(--font-heading);color:var(--gold);text-align:center;font-size:1rem">
                                        <?= number_format($item['precio'],2) ?> €
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div>
                        <div class="card card-gold" style="position:sticky;top:84px">
                            <div class="card-header">
                                <span>🧾</span>
                                <span class="card-title"><?= $_SESSION['lang'] === 'en' ? 'Current Order' : 'Pedido Actual' ?></span>
                                <span id="totalBadge" style="margin-left:auto;font-family:var(--font-heading);color:var(--gold);font-size:1.1rem">0.00 €</span>
                            </div>

                            <?php if (empty($datos['pedidos'])): ?>
                                <p style="color:var(--text-muted);font-style:italic;text-align:center;padding:var(--space-lg)">
                                    <?= $_SESSION['lang'] === 'en' ? 'No items yet. Select from the menu.' : 'Sin items. Selecciona del menú.' ?>
                                </p>
                            <?php else: ?>
                                <div style="display:flex;flex-direction:column;gap:8px;max-height:400px;overflow-y:auto">
                                    <?php $total = 0; foreach ($datos['pedidos'] as $p): if($p['estado']==='cancelado') continue; $total += $p['subtotal']; ?>
                                        <div class="pedido-card">
                                            <div style="flex:1;min-width:0">
                                                <div style="font-size:.85rem;color:var(--text-primary);font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                                    <?= htmlspecialchars($p['nombre_es']) ?>
                                                </div>
                                                <div style="font-size:.75rem;color:var(--text-muted)">
                                                    <?= $p['cantidad'] ?> × <?= number_format($p['precio_unitario'],2) ?> €
                                                    = <span style="color:var(--gold)"><?= number_format($p['subtotal'],2) ?> €</span>
                                                </div>
                                            </div>
                                            <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=servicio_activo&sid=<?= $sid ?>">
                                                <input type="hidden" name="servicio_id" value="<?= $sid ?>">
                                                <input type="hidden" name="pedido_id" value="<?= $p['id'] ?>">
                                                <button type="submit" name="cancelar_pedido" class="btn-icon btn-sm" style="color:#ef5350"
                                                        onclick="return confirm('<?= $_SESSION['lang'] === 'en' ? 'Remove?' : '¿Quitar?' ?>')">✕</button>
                                            </form>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="separator"></div>
                                <div style="display:flex;justify-content:space-between;align-items:center;padding:var(--space-sm) 0">
                                    <span style="font-family:var(--font-heading);font-size:.8rem;letter-spacing:2px;color:var(--text-muted)">TOTAL</span>
                                    <span style="font-family:var(--font-heading);font-size:1.6rem;color:var(--gold)"><?= number_format($total,2) ?> €</span>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=servicios" style="margin-top:var(--space-md)">
                                <input type="hidden" name="servicio_id" value="<?= $sid ?>">
                                <button type="submit" name="cerrar_servicio" class="btn-gold btn-western w-100"
                                        onclick="return confirm('<?= $_SESSION['lang'] === 'en' ? 'Close and charge?' : '¿Cerrar y cobrar?' ?>')">
                                    💳 <?= $_SESSION['lang'] === 'en' ? 'Close & Charge' : 'Cerrar y Cobrar' ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ════════════════ MESAS LIBRES ════════════════ -->
        <?php elseif ($datos['subvista'] === 'mesas_libres'): ?>
            <div style="margin-bottom:var(--space-xl)">
                <h2 style="margin-bottom:4px"><?= $_SESSION['lang'] === 'en' ? '★ Free Tables' : '★ Mesas Libres' ?></h2>
                <p style="color:var(--text-muted);font-style:italic"><?= $_SESSION['lang'] === 'en' ? 'Find available tables by shift' : 'Busca mesas disponibles por turno' ?></p>
            </div>
            <div class="card" style="margin-bottom:var(--space-xl)">
                <form method="GET" action="<?= APP_URL ?>/index.php" style="display:flex;gap:var(--space-md);flex-wrap:wrap;align-items:flex-end">
                    <input type="hidden" name="ruta" value="dashboard">
                    <input type="hidden" name="sv" value="mesas_libres">
                    <div class="form-group" style="margin:0;flex:1;min-width:180px">
                        <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Date' : '✦ Fecha' ?></label>
                        <input type="date" name="fecha" class="form-control" value="<?= $datos['fecha_sel'] ?>">
                    </div>
                    <div class="form-group" style="margin:0;flex:1;min-width:220px">
                        <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Shift' : '✦ Turno' ?></label>
                        <select name="turno_id" class="form-control">
                            <?php foreach ($datos['turnos'] as $t): ?>
                                <option value="<?= $t['id'] ?>" <?= $t['id']==$datos['turno_sel']?'selected':'' ?>>
                                    <?= htmlspecialchars($t['nombre']) ?> (<?= $t['hora_inicio'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn-western">🔍 <?= $_SESSION['lang'] === 'en' ? 'Search' : 'Buscar' ?></button>
                </form>
            </div>
            <div class="mesas-grid">
                <?php foreach ($datos['mesas_libres'] as $m): ?>
                    <div class="mesa-card libre">
                        <div class="mesa-estado-dot"></div>
                        <span class="mesa-numero"><?= htmlspecialchars($m['numero']) ?></span>
                        <span class="mesa-capacidad">👥 <?= $m['capacidad'] ?></span>
                        <span style="font-size:.65rem;color:var(--text-muted);letter-spacing:1px"><?= htmlspecialchars($m['zona_nombre']) ?></span>
                        <span class="badge badge-success"><?= $_SESSION['lang'] === 'en' ? 'Free' : 'Libre' ?></span>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($datos['mesas_libres'])): ?>
                    <div style="grid-column:1/-1;text-align:center;padding:var(--space-2xl);color:var(--text-muted)">
                        <div style="font-size:3rem;margin-bottom:var(--space-md)">😔</div>
                        <?= $_SESSION['lang'] === 'en' ? 'No free tables for this shift.' : 'No hay mesas libres en este turno.' ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ════════════════ RESERVAS CAMARERO ════════════════ -->
        <?php elseif ($datos['subvista'] === 'reservas_camarero'): ?>
            <div style="margin-bottom:var(--space-xl)">
                <h2 style="margin-bottom:4px"><?= $_SESSION['lang'] === 'en' ? "★ Today's Reservations" : '★ Reservas de Hoy' ?></h2>
                <p style="color:var(--text-muted);font-style:italic"><?= date('d/m/Y') ?></p>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:var(--space-md)">
                <?php foreach ($datos['reservas'] as $r): ?>
                    <div class="card" style="border-color:<?= $r['estado']==='pendiente'?'rgba(201,168,76,.3)':($r['estado']==='confirmada'?'rgba(76,175,80,.3)':'rgba(229,57,53,.3)') ?>">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:var(--space-md)">
                            <div>
                                <div style="font-family:var(--font-heading);color:var(--text-primary);font-size:.95rem"><?= htmlspecialchars($r['cliente']) ?></div>
                                <div style="font-size:.75rem;color:var(--text-muted)"><?= htmlspecialchars($r['cliente_email']) ?></div>
                                <?php if($r['telefono']): ?><div style="font-size:.75rem;color:var(--gold)">📞 <?= htmlspecialchars($r['telefono']) ?></div><?php endif; ?>
                            </div>
                            <span class="badge badge-<?= match($r['estado']){'pendiente'=>'warning','confirmada'=>'success','cancelada'=>'danger',default=>'neutral'} ?>">
            <?= $r['estado'] ?>
          </span>
                        </div>
                        <div style="background:var(--bg-surface);border-radius:var(--radius-sm);padding:var(--space-md);margin-bottom:var(--space-md);display:grid;grid-template-columns:1fr 1fr;gap:8px">
                            <div><span style="font-size:.65rem;color:var(--text-muted);letter-spacing:1px;display:block">TURNO</span><span style="font-size:.85rem;font-family:var(--font-heading)"><?= $r['hora_inicio'] ?> – <?= $r['hora_fin'] ?></span></div>
                            <div><span style="font-size:.65rem;color:var(--text-muted);letter-spacing:1px;display:block">MESA</span><span style="font-size:.85rem;font-family:var(--font-heading);color:var(--gold)"><?= $r['mesa'] ?> — <?= $r['zona'] ?></span></div>
                            <div><span style="font-size:.65rem;color:var(--text-muted);letter-spacing:1px;display:block">COMENSALES</span><span style="font-size:.85rem">👥 <?= $r['num_comensales'] ?></span></div>
                            <?php if($r['observaciones']): ?><div style="grid-column:1/-1"><span style="font-size:.65rem;color:var(--text-muted);letter-spacing:1px;display:block">NOTA</span><span style="font-size:.8rem;font-style:italic"><?= htmlspecialchars($r['observaciones']) ?></span></div><?php endif; ?>
                        </div>
                        <?php if ($r['estado'] !== 'cancelada'): ?>
                            <div style="display:flex;gap:8px">
                                <?php if ($r['estado'] === 'pendiente'): ?>
                                    <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=reservas_camarero" style="flex:1">
                                        <input type="hidden" name="reserva_id" value="<?= $r['id'] ?>">
                                        <button type="submit" name="confirmar_reserva" class="btn-western btn-sm w-100">✓ <?= $_SESSION['lang'] === 'en' ? 'Confirm' : 'Confirmar' ?></button>
                                    </form>
                                <?php endif; ?>
                                <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=reservas_camarero" style="<?= $r['estado']==='pendiente'?'':'flex:1' ?>">
                                    <input type="hidden" name="reserva_id" value="<?= $r['id'] ?>">
                                    <button type="submit" name="cancelar_reserva" class="btn-danger btn-western btn-sm <?= $r['estado']!=='pendiente'?'w-100':'' ?>"
                                            onclick="return confirm('<?= $_SESSION['lang'] === 'en' ? 'Cancel this reservation?' : '¿Cancelar esta reserva?' ?>')">
                                        ✕ <?= $_SESSION['lang'] === 'en' ? 'Cancel' : 'Cancelar' ?>
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($datos['reservas'])): ?>
                    <div style="grid-column:1/-1;text-align:center;padding:var(--space-2xl);color:var(--text-muted)">
                        <div style="font-size:3rem;margin-bottom:var(--space-md)">📋</div>
                        <?= $_SESSION['lang'] === 'en' ? 'No reservations for today.' : 'No hay reservas para hoy.' ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </main>
</div>

<!-- ════ MODAL: ABRIR SERVICIO ════ -->
<div class="modal-overlay" id="modalAbrirServicio">
    <div class="modal" style="max-width:480px">
        <div class="modal-header">
            <span class="modal-title">🍽️ <?= $_SESSION['lang'] === 'en' ? 'Open New Service' : 'Abrir Nuevo Servicio' ?></span>
            <button class="modal-close" data-close-modal>✕</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=servicios">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Shift' : '✦ Turno' ?></label>
                        <select name="turno_id" class="form-control" required>
                            <option value="">—</option>
                            <?php foreach ($datos['turnos'] ?? [] as $t): ?>
                                <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nombre']) ?> (<?= $t['hora_inicio'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Table' : '✦ Mesa' ?></label>
                        <select name="mesa_id" class="form-control" required>
                            <option value="">—</option>
                            <?php foreach ($datos['mesas'] ?? [] as $m): ?>
                                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['numero']) ?> — <?= htmlspecialchars($m['zona_nombre']) ?> (<?= $m['capacidad'] ?> pers.)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Number of Diners' : '✦ Número de Comensales' ?></label>
                    <input type="number" name="comensales" class="form-control" required min="1" max="30" value="2">
                </div>
                <input type="hidden" name="reserva_id" value="">
                <button type="submit" name="abrir_servicio" class="btn-western w-100">
                    ▶ <?= $_SESSION['lang'] === 'en' ? 'Open Service' : 'Abrir Servicio' ?>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ════ MODAL: ADD ITEM ════ -->
<div class="modal-overlay" id="modalAddItem">
    <div class="modal" style="max-width:360px">
        <div class="modal-header">
            <span class="modal-title" id="addItemTitle">Añadir ítem</span>
            <button class="modal-close" data-close-modal>✕</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="<?= APP_URL ?>/index.php?ruta=dashboard&sv=servicio_activo&sid=<?= $sid ?? 0 ?>">
                <input type="hidden" name="servicio_id" value="<?= $sid ?? 0 ?>">
                <input type="hidden" name="item_id" id="addItemId">
                <div style="text-align:center;margin-bottom:var(--space-lg)">
                    <div style="font-family:var(--font-heading);font-size:1.3rem;color:var(--gold)" id="addItemPrice">0.00 €</div>
                </div>
                <div class="form-group">
                    <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Quantity' : '✦ Cantidad' ?></label>
                    <div class="item-qty-control" style="justify-content:center">
                        <button type="button" class="qty-btn" onclick="changeQty(-1)">−</button>
                        <input type="number" name="cantidad" id="addItemQty" class="form-control" value="1" min="1" max="20" style="width:80px;text-align:center">
                        <button type="button" class="qty-btn" onclick="changeQty(1)">+</button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label"><?= $_SESSION['lang'] === 'en' ? '✦ Notes' : '✦ Notas' ?></label>
                    <input type="text" name="notas" class="form-control" placeholder="<?= $_SESSION['lang'] === 'en' ? 'Allergies, preferences...' : 'Alergias, preferencias...' ?>">
                </div>
                <button type="submit" name="agregar_pedido" class="btn-western w-100">
                    + <?= $_SESSION['lang'] === 'en' ? 'Add to Order' : 'Añadir al Pedido' ?>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="<?= APP_URL ?>/js/main.js"></script>
<script>
    function openAddItem(id, nombre, precio) {
        document.getElementById('addItemId').value = id;
        document.getElementById('addItemTitle').textContent = nombre;
        document.getElementById('addItemPrice').textContent = precio.toFixed(2) + ' €';
        document.getElementById('addItemQty').value = 1;
        openModal('modalAddItem');
    }
    function changeQty(delta) {
        const inp = document.getElementById('addItemQty');
        inp.value = Math.max(1, Math.min(20, parseInt(inp.value || 1) + delta));
    }
    function filterCat(cat, btn) {
        document.querySelectorAll('.categoria-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.item-card').forEach(el => {
            el.style.display = (cat === 'all' || el.dataset.cat === cat) ? '' : 'none';
        });
    }
</script>
</body>
</html>