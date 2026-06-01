<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'es' ?>" data-theme="<?= $_SESSION['theme'] ?? 'dark' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_SESSION['lang'] === 'en' ? 'Register' : 'Registro' ?> — El Último Corral</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&family=Cinzel+Decorative:wght@700&family=Lora:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <link rel="stylesheet" href="styles/vistaRegistro.css">
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
<?php require_once './views/partials/navbar.php'; ?>

<div class="page-wrapper" style="background:radial-gradient(ellipse at 70% 30%,rgba(139,69,19,.15),transparent 60%),var(--bg-primary);padding-top:96px;padding-bottom:var(--space-2xl)">
    <div id="heroParticles" style="position:fixed;inset:0;pointer-events:none;z-index:0"></div>

    <div class="reg-container" style="position:relative;z-index:1">

        <!-- Header -->
        <div style="text-align:center;margin-bottom:var(--space-xl)">
            <div style="font-size:3.5rem;margin-bottom:var(--space-md)">🏕️</div>
            <h1 style="font-size:1.8rem;margin-bottom:8px">
                <?= $_SESSION['lang'] === 'en' ? 'Join the Corral' : 'Únete al Corral' ?>
            </h1>
            <p style="color:var(--text-muted);font-style:italic">
                <?= $_SESSION['lang'] === 'en' ? 'Create your account and start booking' : 'Crea tu cuenta y empieza a reservar' ?>
            </p>
        </div>

        <!-- Step indicator -->
        <div class="step-indicator" id="stepIndicator">
            <div class="step-dot active" id="dot1">1</div>
            <div class="step-line"></div>
            <div class="step-dot" id="dot2">2</div>
            <div class="step-line"></div>
            <div class="step-dot" id="dot3">3</div>
        </div>

        <!-- Alert -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?>" data-auto-dismiss="6000" style="margin-bottom:var(--space-md)">
                <?= htmlspecialchars($_SESSION['mensaje']['texto']) ?>
                <button class="alert-close" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit">✕</button>
            </div>
            <?php unset($_SESSION['mensaje']); endif; ?>

        <!-- Google Register -->
        <a href="controllers/api/google_auth.php" class="card" style="
            display:flex;align-items:center;justify-content:center;gap:12px;
            padding:16px;text-decoration:none;margin-bottom:var(--space-lg);
            border:1px solid var(--border);transition:all var(--transition-med);
        " onmouseover="this.style.borderColor='var(--gold)'"
           onmouseout="this.style.borderColor='var(--border)'">
            <svg width="22" height="22" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <span style="font-family:var(--font-heading);font-size:.85rem;letter-spacing:1.5px;text-transform:uppercase;color:var(--text-primary)">
                <?= $_SESSION['lang'] === 'en' ? 'Sign up with Google' : 'Registrarse con Google' ?>
            </span>
        </a>

        <div class="western-divider"><span><?= $_SESSION['lang'] === 'en' ? 'or fill in your details' : 'o rellena tus datos' ?></span></div>

        <!-- Main Form -->
        <form method="POST" action="index.php" data-validate id="regForm" style="margin-top:var(--space-lg)">

            <!-- STEP 1: Personal data -->
            <div class="form-section" id="step1">
                <div class="section-title">
                    <span>🪪</span>
                    <?= $_SESSION['lang'] === 'en' ? 'Personal Information' : 'Datos Personales' ?>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="nombre">
                            <?= $_SESSION['lang'] === 'en' ? '✦ First Name' : '✦ Nombre' ?>
                        </label>
                        <input type="text" id="nombre" name="nombre" class="form-control" required
                               placeholder="<?= $_SESSION['lang'] === 'en' ? 'Your name' : 'Tu nombre' ?>" autocomplete="given-name">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="apellidos">
                            <?= $_SESSION['lang'] === 'en' ? '✦ Surname(s)' : '✦ Apellidos' ?>
                        </label>
                        <input type="text" id="apellidos" name="apellidos" class="form-control" required
                               placeholder="<?= $_SESSION['lang'] === 'en' ? 'Your surname' : 'Tus apellidos' ?>" autocomplete="family-name">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="dni">
                            <?= $_SESSION['lang'] === 'en' ? '✦ ID / NIE' : '✦ DNI / NIE' ?>
                        </label>
                        <input type="text" id="dni" name="dni" class="form-control" required
                               placeholder="00000000A" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="telefono">
                            <?= $_SESSION['lang'] === 'en' ? '✦ Phone' : '✦ Teléfono' ?>
                        </label>
                        <input type="tel" id="telefono" name="telefono" class="form-control"
                               placeholder="6XX XXX XXX" autocomplete="tel">
                    </div>
                </div>
            </div>

            <!-- STEP 2: Address (Catastro) -->
            <div class="form-section" id="step2">
                <div class="section-title">
                    <span>🗺️</span>
                    <?= $_SESSION['lang'] === 'en' ? 'Address (Catastro)' : 'Dirección (Catastro)' ?>
                </div>

                <!-- Hidden inputs que se envían con el form -->
                <input type="hidden" id="provincia" name="provincia">
                <input type="hidden" id="municipio" name="municipio">
                <input type="hidden" id="via"       name="via">

                <div class="form-group">
                    <label class="form-label">
                        <?= $_SESSION['lang'] === 'en' ? '✦ Province' : '✦ Provincia' ?>
                    </label>
                    <div class="custom-select-wrapper">
                        <div class="custom-select-trigger disabled" id="cs-provincia">
                            <span class="cs-label"><?= $_SESSION['lang'] === 'en' ? 'Loading...' : 'Selecciona una provincia' ?></span>
                            <span class="arrow">▼</span>
                        </div>
                        <div class="custom-select-dropdown" id="dd-provincia">
                            <input class="cs-search" type="text" placeholder="<?= $_SESSION['lang'] === 'en' ? 'Search...' : 'Buscar...' ?>">
                            <div class="cs-options"></div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <?= $_SESSION['lang'] === 'en' ? '✦ Municipality' : '✦ Municipio' ?>
                        </label>
                        <div class="custom-select-wrapper">
                            <div class="custom-select-trigger disabled" id="cs-municipio">
                                <span class="cs-label">— <?= $_SESSION['lang'] === 'en' ? 'Municipality' : 'Municipio' ?> —</span>
                                <span class="arrow">▼</span>
                            </div>
                            <div class="custom-select-dropdown" id="dd-municipio">
                                <input class="cs-search" type="text" placeholder="<?= $_SESSION['lang'] === 'en' ? 'Search...' : 'Buscar...' ?>">
                                <div class="cs-options"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <?= $_SESSION['lang'] === 'en' ? '✦ Street' : '✦ Vía' ?>
                        </label>
                        <div class="custom-select-wrapper">
                            <div class="custom-select-trigger disabled" id="cs-via">
                                <span class="cs-label">— <?= $_SESSION['lang'] === 'en' ? 'Street' : 'Vía' ?> —</span>
                                <span class="arrow">▼</span>
                            </div>
                            <div class="custom-select-dropdown" id="dd-via">
                                <input class="cs-search" type="text" placeholder="<?= $_SESSION['lang'] === 'en' ? 'Search...' : 'Buscar...' ?>">
                                <div class="cs-options"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="numero">
                        <?= $_SESSION['lang'] === 'en' ? '✦ Number / Floor' : '✦ Número / Piso' ?>
                    </label>
                    <input type="text" id="numero" name="numero" class="form-control"
                           placeholder="<?= $_SESSION['lang'] === 'en' ? 'e.g. 14, 2nd floor B' : 'Ej. 14, 2º B' ?>"
                           maxlength="30">
                </div>
                <p class="form-hint">
                    🛰️ <?= $_SESSION['lang'] === 'en'
                            ? 'Data loaded from the Spanish Cadastre. Select in order: Province → Municipality → Street.'
                            : 'Datos cargados desde el Catastro español. Selecciona en orden: Provincia → Municipio → Vía.'
                    ?>
                </p>
            </div>

            <!-- STEP 3: Account -->
            <div class="form-section" id="step3">
                <div class="section-title">
                    <span>🔐</span>
                    <?= $_SESSION['lang'] === 'en' ? 'Account Credentials' : 'Credenciales de Acceso' ?>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">
                        <?= $_SESSION['lang'] === 'en' ? '✦ Email Address' : '✦ Correo Electrónico' ?>
                    </label>
                    <input type="email" id="email" name="email" class="form-control" required
                           placeholder="tu@correo.com" autocomplete="email">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="password">
                            <?= $_SESSION['lang'] === 'en' ? '✦ Password' : '✦ Contraseña' ?>
                        </label>
                        <div style="position:relative">
                            <input type="password" id="password" name="password" class="form-control" required
                                   placeholder="Mín. 8 caracteres" minlength="8" autocomplete="new-password"
                                   oninput="checkStrength(this.value)">
                            <button type="button" onclick="togglePass('password',this)"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted)">👁</button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                            <span class="strength-text" id="strengthText">—</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="cpassword">
                            <?= $_SESSION['lang'] === 'en' ? '✦ Confirm Password' : '✦ Confirmar Contraseña' ?>
                        </label>
                        <div style="position:relative">
                            <input type="password" id="cpassword" name="cpassword" class="form-control" required
                                   placeholder="Repite la contraseña" autocomplete="new-password">
                            <button type="button" onclick="togglePass('cpassword',this)"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted)">👁</button>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div style="margin-top:var(--space-md)">
                    <label class="check-western">
                        <input type="checkbox" id="terms" required>
                        <span>
                            <?= $_SESSION['lang'] === 'en'
                                    ? 'I accept the <a href="docs/terminos_servicio.pdf" download style="color:var(--gold)">Terms of Service</a> and <a href="docs/politica_privacidad.pdf" download style="color:var(--gold)">Privacy Policy</a>'
                                    : 'Acepto los <a href="docs/terminos_servicio.pdf" download style="color:var(--gold)">Términos de Servicio</a> y la <a href="docs/politica_privacidad.pdf" download style="color:var(--gold)">Política de Privacidad</a>'
                            ?>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" name="registrar" class="btn-western btn-lg w-100" style="margin-top:var(--space-md)">
                <?= $_SESSION['lang'] === 'en' ? '✦ Create My Account ✦' : '✦ Crear Mi Cuenta ✦' ?>
            </button>
        </form>

        <p style="text-align:center;margin-top:var(--space-lg);font-size:.87rem;color:var(--text-muted)">
            <?= $_SESSION['lang'] === 'en' ? 'Already have an account?' : '¿Ya tienes cuenta?' ?>
        <form method="POST" style="display:inline">
            <button name="ir_login" style="background:none;border:none;cursor:pointer;color:var(--gold);font-family:var(--font-heading);font-size:.87rem;letter-spacing:1px">
                <?= $_SESSION['lang'] === 'en' ? 'Sign in' : 'Inicia sesión' ?>
            </button>
        </form>
        </p>

    </div>
</div>

<script src="js/main.js"></script>
<script>
    function togglePass(id, btn) {
        const inp = document.getElementById(id);
        inp.type = inp.type === 'password' ? 'text' : 'password';
        btn.textContent = inp.type === 'password' ? '👁' : '🙈';
    }

    function checkStrength(pass) {
        const fill = document.getElementById('strengthFill');
        const text = document.getElementById('strengthText');
        let score = 0;
        if (pass.length >= 8)   score++;
        if (/[A-Z]/.test(pass)) score++;
        if (/[0-9]/.test(pass)) score++;
        if (/[^A-Za-z0-9]/.test(pass)) score++;
        const levels = [
            { w:'25%',  c:'#e74c3c', t:'<?= $_SESSION["lang"]==="en"?"Weak":"Débil" ?>' },
            { w:'50%',  c:'#e67e22', t:'<?= $_SESSION["lang"]==="en"?"Fair":"Regular" ?>' },
            { w:'75%',  c:'#f1c40f', t:'<?= $_SESSION["lang"]==="en"?"Good":"Buena" ?>' },
            { w:'100%', c:'#4caf50', t:'<?= $_SESSION["lang"]==="en"?"Strong":"Fuerte" ?>' },
        ];
        const lvl = levels[Math.min(score - 1, 3)] || levels[0];
        fill.style.width = lvl.w;
        fill.style.background = lvl.c;
        text.textContent = score === 0 ? '—' : lvl.t;
        text.style.color = lvl.c;
    }

    // ── Step indicator ────────────────────────────────────────────
    const steps = ['step1','step2','step3'];
    const dots  = ['dot1','dot2','dot3'];
    document.getElementById('regForm').addEventListener('input', () => {
        steps.forEach((s, i) => {
            const sec    = document.getElementById(s);
            const inputs = sec.querySelectorAll('[required]');
            const filled = [...inputs].every(inp => inp.value.trim() !== '');
            document.getElementById(dots[i]).className = 'step-dot ' + (filled ? 'done' : i === 0 ? 'active' : '');
        });
    });

    // ── Custom Select ─────────────────────────────────────────────
    function buildCS(triggerId, dropdownId, hiddenId, placeholder) {
        const trigger  = document.getElementById(triggerId);
        const dropdown = document.getElementById(dropdownId);
        const hidden   = document.getElementById(hiddenId);
        const search   = dropdown.querySelector('.cs-search');
        const optWrap  = dropdown.querySelector('.cs-options');
        let allItems   = [];

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

        // Cerrar al hacer clic fuera
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
                    document.getElementById('regForm').dispatchEvent(new Event('input'));
                });
                optWrap.appendChild(div);
            });
        }

        return {
            load(items) {
                allItems = items;
                trigger.classList.remove('disabled');
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

    // ── Catastro ──────────────────────────────────────────────────
    (function () {
        const apiUrl = 'controllers/api/catastro.php';
        const lang   = document.documentElement.lang || 'es';

        const ph = {
            prov: lang === 'en' ? '— Province —'     : '— Provincia —',
            mun:  lang === 'en' ? '— Municipality —' : '— Municipio —',
            via:  lang === 'en' ? '— Street —'       : '— Vía —',
        };

        const csProv = buildCS('cs-provincia', 'dd-provincia', 'provincia', ph.prov);
        const csMun  = buildCS('cs-municipio', 'dd-municipio', 'municipio', ph.mun);
        const csVia  = buildCS('cs-via',       'dd-via',       'via',       ph.via);

        function post(body) {
            return fetch(apiUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body
            }).then(r => r.json());
        }

        // 1. Cargar provincias
        post('accion=provincias').then(data => {
            const nombres = data.map(p => typeof p === 'object' ? p.nombre : p);
            csProv.load(nombres);
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
            const prov = document.getElementById('provincia').value;
            post(`accion=vias&provincia=${encodeURIComponent(prov)}&municipio=${encodeURIComponent(e.detail)}`).then(data => {
                const nombres = data.map(v => typeof v === 'object' ? (v.nombre ?? v) : v);
                csVia.load(nombres);
            });
        });
    })();
</script>
</body>
</html>