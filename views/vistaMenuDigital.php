<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'es' ?>" data-theme="<?= $_SESSION['theme'] ?? 'dark' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_SESSION['lang'] === 'en' ? 'Digital Menu' : 'Menú Digital' ?> — El Último Corral</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Cinzel+Decorative:wght@700&family=Lora:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/vistaMenuDigital.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🍽️</text></svg>">
</head>
<body>
<?php require_once './views/partials/navbar.php'; ?>

<!-- ─── HERO ───────────────────────────────────────────────── -->
<div class="menu-hero" style="padding-top:96px">
  <span style="font-family:var(--font-heading);font-size:.75rem;letter-spacing:5px;text-transform:uppercase;color:var(--gold);margin-bottom:var(--space-md);display:block">
    ✦ <?= $_SESSION['lang'] === 'en' ? 'El Último Corral' : 'El Último Corral' ?> ✦
  </span>
    <h1 style="font-family:var(--font-display);font-size:clamp(2.5rem,7vw,5rem);color:var(--cream);margin-bottom:var(--space-md);text-shadow:2px 4px 12px rgba(0,0,0,.6)">
        <?= $_SESSION['lang'] === 'en' ? 'Our Menu' : 'Nuestro Menú' ?>
    </h1>
    <p style="font-style:italic;color:var(--text-secondary);max-width:500px;font-size:1.05rem">
        <?= $_SESSION['lang'] === 'en'
                ? '"Where Andalusian tradition meets the Wild West spirit"'
                : '"Donde la tradición andaluza se encuentra con el espíritu del Lejano Oeste"'
        ?>
    </p>
    <div class="western-divider" style="max-width:300px;margin:var(--space-md) auto 0"><span>✦</span></div>
</div>

<!-- ─── STICKY TABS ───────────────────────────────────────── -->
<div class="menu-tabs-wrapper">
    <div style="display:flex;align-items:center;gap:var(--space-md);flex-wrap:nowrap">
        <div class="menu-tabs-scroll" id="menuTabs" style="flex:1;min-width:0">
            <button class="menu-tab active" data-cat="all" onclick="filterMenu('all',this)">
                🤠 <?= $_SESSION['lang'] === 'en' ? 'All' : 'Todo' ?>
            </button>
            <?php foreach ($categorias as $cat): ?>
                <button class="menu-tab" data-cat="<?= $cat['id'] ?>" onclick="filterMenu('<?= $cat['id'] ?>',this)">
                    <?= $cat['icono'] ?> <?= $_SESSION['lang'] === 'en' ? htmlspecialchars($cat['nombre_en']) : htmlspecialchars($cat['nombre_es']) ?>
                </button>
            <?php endforeach; ?>
        </div>
        <div class="menu-search-bar">
            <input type="text" class="search-input" id="menuSearch"
                   placeholder="<?= $_SESSION['lang'] === 'en' ? '🔍 Search dish...' : '🔍 Buscar plato...' ?>"
                   oninput="searchMenu(this.value)">
        </div>
    </div>
</div>

<!-- ─── MENU SECTIONS ─────────────────────────────────────── -->
<div id="menuContent">
    <?php
    // Group items by category
    $itemsPorCat = [];
    foreach ($items as $item) {
        $itemsPorCat[$item['categoria_id']][] = $item;
    }
    ?>

    <?php foreach ($categorias as $cat):
        if (empty($itemsPorCat[$cat['id']])) continue;
        ?>
        <section class="menu-section" id="cat-<?= $cat['id'] ?>" data-section-cat="<?= $cat['id'] ?>">
            <div class="menu-section-header">
                <div class="menu-section-icon"><?= $cat['icono'] ?></div>
                <div>
                    <div class="menu-section-title">
                        <?= $_SESSION['lang'] === 'en' ? htmlspecialchars($cat['nombre_en']) : htmlspecialchars($cat['nombre_es']) ?>
                    </div>
                    <div style="font-size:.75rem;color:var(--text-muted);letter-spacing:1px;margin-top:2px">
                        <?= count($itemsPorCat[$cat['id']]) ?> <?= $_SESSION['lang'] === 'en' ? 'items' : 'artículos' ?>
                    </div>
                </div>
            </div>

            <div class="menu-items-grid">
                <?php foreach ($itemsPorCat[$cat['id']] as $item):
                    $nombre = $_SESSION['lang'] === 'en' ? $item['nombre_en'] : $item['nombre_es'];
                    $desc   = $_SESSION['lang'] === 'en' ? $item['descripcion_en'] : $item['descripcion_es'];
                    $emojis = ['comida'=>'🍽️','bebida'=>'🥤','postre'=>'🍮','pan'=>'🥖'];
                    $emoji  = $emojis[$item['tipo']] ?? '🍽️';
                    ?>
                    <div class="menu-item <?= $item['destacado'] ? 'destacado' : '' ?>"
                         data-cat="<?= $item['categoria_id'] ?>"
                         data-name="<?= strtolower($nombre) ?>"
                         data-tipo="<?= $item['tipo'] ?>">

                        <?php if ($item['destacado']): ?>
                            <div class="destacado-badge">★ <?= $_SESSION['lang'] === 'en' ? 'Chef\'s Pick' : 'Recomendado' ?></div>
                        <?php endif; ?>

                        <div class="item-emoji"><?= $emoji ?></div>

                        <div class="item-body">
                            <div class="item-name"><?= htmlspecialchars($nombre) ?></div>
                            <?php if ($desc): ?>
                                <p class="item-desc"><?= htmlspecialchars($desc) ?></p>
                            <?php endif; ?>
                            <div class="item-meta">
                                <span class="item-price"><?= number_format($item['precio'], 2) ?> €</span>
                                <?php if (!empty($item['alergenos'])): ?>
                                    <span class="item-alergenos" data-tip="<?= $_SESSION['lang'] === 'en' ? 'Allergens: ' : 'Alérgenos: ' ?><?= htmlspecialchars($item['alergenos']) ?>">
              ⚠️ <?= htmlspecialchars($item['alergenos']) ?>
            </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endforeach; ?>

    <!-- No results -->
    <div id="noResults" style="display:none;text-align:center;padding:var(--space-3xl)">
        <div style="font-size:4rem;margin-bottom:var(--space-md)">🌵</div>
        <h3 style="color:var(--text-muted)"><?= $_SESSION['lang'] === 'en' ? 'No dishes found.' : 'No se encontraron platos.' ?></h3>
        <button onclick="clearSearch()" class="btn-ghost btn-western btn-sm" style="margin-top:var(--space-md)">
            <?= $_SESSION['lang'] === 'en' ? 'Show all' : 'Mostrar todo' ?>
        </button>
    </div>
</div>

<!-- ─── ALLERGEN LEGEND ───────────────────────────────────── -->
<div class="alergeno-legend">
    <div class="western-divider" style="width:100%">
        <span><?= $_SESSION['lang'] === 'en' ? 'Allergen Information' : 'Información de Alérgenos' ?></span>
    </div>
    <?php
    $alergenos = [
            ['⚠️','Gluten'],['🥛','Lactosa'],['🥚','Huevo'],['🐟','Pescado'],
            ['🦐','Crustáceos'],['🥜','Frutos Secos'],['🌰','Cacahuetes'],['🌾','Soja'],
    ];
    foreach ($alergenos as $a):
        ?>
        <div class="alergeno-item">
            <span><?= $a[0] ?></span>
            <span><?= $a[1] ?></span>
        </div>
    <?php endforeach; ?>
    <p style="width:100%;font-size:.78rem;color:var(--text-muted);font-style:italic;margin:0">
        <?= $_SESSION['lang'] === 'en'
                ? 'If you have any allergies or intolerances, please inform our staff before ordering. All dishes may contain traces of allergens.'
                : 'Si tienes alguna alergia o intolerancia, informa a nuestro personal antes de pedir. Todos los platos pueden contener trazas de alérgenos.'
        ?>
    </p>
</div>

<!-- ─── RESERVE CTA (floating) ───────────────────────────── -->
<?php if (!isset($_SESSION['idU'])): ?>
    <div class="float-reserve">
        <form method="POST" action="index.php">
            <button name="ir_registro" class="btn-western btn-lg" style="box-shadow:0 8px 30px rgba(0,0,0,.5)">
                🤠 <?= $_SESSION['lang'] === 'en' ? 'Reserve Table' : 'Reservar Mesa' ?>
            </button>
        </form>
    </div>
<?php else: ?>
    <div class="float-reserve">
        <a href="index.php?ruta=dashboard&sv=nueva_reserva" class="btn-western btn-lg" style="box-shadow:0 8px 30px rgba(0,0,0,.5)">
            🤠 <?= $_SESSION['lang'] === 'en' ? 'Reserve Table' : 'Reservar Mesa' ?>
        </a>
    </div>
<?php endif; ?>

<!-- ─── BACK TO TOP ───────────────────────────────────────── -->
<div style="padding:var(--space-xl);text-align:center">
    <a href="index.php" style="font-family:var(--font-heading);font-size:.75rem;letter-spacing:2px;color:var(--text-muted);text-transform:uppercase">
        ← <?= $_SESSION['lang'] === 'en' ? 'Back to Home' : 'Volver al Inicio' ?>
    </a>
</div>

<script src="js/main.js"></script>
<script>
    // ── Menu filter by category tab ──────────────────────────
    function filterMenu(cat, btn) {
        document.querySelectorAll('.menu-tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('menuSearch').value = '';

        const sections = document.querySelectorAll('[data-section-cat]');
        const items    = document.querySelectorAll('.menu-item');
        let anyVisible = false;

        if (cat === 'all') {
            sections.forEach(s => s.style.display = '');
            items.forEach(i => { i.style.display = ''; anyVisible = true; });
        } else {
            sections.forEach(s => {
                s.style.display = s.dataset.sectionCat === cat ? '' : 'none';
            });
            items.forEach(i => {
                const show = i.dataset.cat === cat;
                i.style.display = show ? '' : 'none';
                if (show) anyVisible = true;
            });
        }

        document.getElementById('noResults').style.display = anyVisible ? 'none' : 'block';

        // Scroll to section
        if (cat !== 'all') {
            const sec = document.getElementById('cat-' + cat);
            if (sec) sec.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // ── Search ────────────────────────────────────────────────
    function searchMenu(query) {
        const q = query.toLowerCase().trim();
        const sections = document.querySelectorAll('[data-section-cat]');
        const items    = document.querySelectorAll('.menu-item');
        let anyVisible = false;

        // Reset tabs
        document.querySelectorAll('.menu-tab').forEach(t => t.classList.remove('active'));
        document.querySelector('[data-cat="all"]').classList.add('active');

        if (!q) {
            sections.forEach(s => s.style.display = '');
            items.forEach(i => { i.style.display = ''; anyVisible = true; });
        } else {
            items.forEach(item => {
                const name = item.dataset.name || '';
                const show = name.includes(q);
                item.style.display = show ? '' : 'none';
                if (show) anyVisible = true;
            });

            // Hide empty sections
            sections.forEach(sec => {
                const visItems = [...sec.querySelectorAll('.menu-item')].filter(i => i.style.display !== 'none');
                sec.style.display = visItems.length ? '' : 'none';
            });
        }

        document.getElementById('noResults').style.display = anyVisible ? 'none' : 'block';
    }

    function clearSearch() {
        document.getElementById('menuSearch').value = '';
        searchMenu('');
    }

    // ── Tab scroll indicator ─────────────────────────────────
    const tabsScroll = document.getElementById('menuTabs');
    if (tabsScroll) {
        // Highlight tab based on scroll position
        const sections = document.querySelectorAll('[data-section-cat]');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const catId = entry.target.dataset.sectionCat;
                    document.querySelectorAll('.menu-tab').forEach(t => {
                        t.classList.toggle('active', t.dataset.cat === catId);
                    });
                }
            });
        }, { rootMargin: '-40% 0px -55% 0px' });

        sections.forEach(s => observer.observe(s));
    }

    // ── Animate items on load ─────────────────────────────────
    document.querySelectorAll('.menu-item').forEach((item, i) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        setTimeout(() => {
            item.style.transition = 'opacity .4s ease, transform .4s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, 50 + i * 30);
    });
    // ── Drag to scroll en las tabs ────────────────────────────
    const tabsEl = document.getElementById('menuTabs');
    if (tabsEl) {
        let isDown = false, startX, scrollLeft;

        tabsEl.addEventListener('mousedown', e => {
            isDown = true;
            tabsEl.style.cursor = 'grabbing';
            startX = e.pageX - tabsEl.offsetLeft;
            scrollLeft = tabsEl.scrollLeft;
        });
        tabsEl.addEventListener('mouseleave', () => {
            isDown = false;
            tabsEl.style.cursor = 'grab';
        });
        tabsEl.addEventListener('mouseup', () => {
            isDown = false;
            tabsEl.style.cursor = 'grab';
        });
        tabsEl.addEventListener('mousemove', e => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - tabsEl.offsetLeft;
            const walk = (x - startX) * 1.5;
            tabsEl.scrollLeft = scrollLeft - walk;
        });

        tabsEl.style.cursor = 'grab';
    }
</script>
</body>
</html>