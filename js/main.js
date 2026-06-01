/**
 * EL ÚLTIMO CORRAL — MAIN JS
 * Western Restaurant Management System
 */

'use strict';

// ═══════════════════════════════════════════════════════════════
// THEME & LANG MANAGEMENT
// ═══════════════════════════════════════════════════════════════

const WesternApp = (() => {

  function init() {
    applyStoredTheme();
    applyStoredLang();
    initThemeToggle();
    initLangToggle();
    initSidebar();
    initModals();
    initAlerts();
    initAnimations();
    initParticles();
    initCatastro();
    initMenuFilter();
    initTooltips();
    initFormValidation();
    initMesaSelector();
  }

  // ── Theme ──────────────────────────────────────────────────
  function applyStoredTheme() {
    const theme = document.cookie.match(/theme=([^;]+)/)?.[1]
                || localStorage.getItem('western_theme')
                || 'dark';
    document.documentElement.setAttribute('data-theme', theme);
  }

  function initThemeToggle() {
    const btn = document.getElementById('themeToggle');
    if (!btn) return;
    btn.addEventListener('click', () => {
      const current = document.documentElement.getAttribute('data-theme');
      const next    = current === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', next);
      localStorage.setItem('western_theme', next);
      document.cookie = `theme=${next};path=/;max-age=31536000`;
      // Navigate to apply server-side
      const url = new URL(window.location.href);
      url.searchParams.set('theme', next);
      window.location.replace(url.toString());
    });
  }

  // ── Language ───────────────────────────────────────────────
  function applyStoredLang() {
    const lang = localStorage.getItem('western_lang') || 'es';
    document.querySelectorAll('.lang-btn').forEach(b => {
      b.classList.toggle('active', b.dataset.lang === lang);
    });
  }

  function initLangToggle() {
    document.querySelectorAll('.lang-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const lang = btn.dataset.lang;
        localStorage.setItem('western_lang', lang);
        const url = new URL(window.location.href);
        url.searchParams.set('lang', lang);
        window.location.replace(url.toString());
      });
    });
  }

  // ── Sidebar (mobile) ───────────────────────────────────────
  function initSidebar() {
    const toggle  = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (!toggle || !sidebar) return;

    const open  = () => { sidebar.classList.add('open'); overlay?.classList.add('open'); };
    const close = () => { sidebar.classList.remove('open'); overlay?.classList.remove('open'); };

    toggle.addEventListener('click', () => sidebar.classList.contains('open') ? close() : open());
    overlay?.addEventListener('click', close);

    // Active link
    const current = window.location.search;
    document.querySelectorAll('.sidebar-link').forEach(link => {
      if (link.href && link.href.includes(current)) link.classList.add('active');
    });
  }

  // ── Modals ─────────────────────────────────────────────────
  function initModals() {
    // Open
    document.querySelectorAll('[data-modal]').forEach(btn => {
      btn.addEventListener('click', () => openModal(btn.dataset.modal));
    });
    // Close buttons
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
      btn.addEventListener('click', () => closeModal(btn.closest('.modal-overlay')?.id));
    });
    // Close on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
      overlay.addEventListener('click', e => {
        if (e.target === overlay) closeModal(overlay.id);
      });
    });
    // Close on ESC
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.open').forEach(m => closeModal(m.id));
      }
    });
  }

  function openModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeModal(id) {
    if (!id) return;
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('open');
    document.body.style.overflow = '';
  }

  window.openModal  = openModal;
  window.closeModal = closeModal;

  // ── Alerts / Auto-dismiss ──────────────────────────────────
  function initAlerts() {
    document.querySelectorAll('.alert[data-auto-dismiss]').forEach(alert => {
      const delay = parseInt(alert.dataset.autoDismiss) || 5000;
      setTimeout(() => {
        alert.style.transition = 'opacity .4s ease, transform .4s ease';
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(() => alert.remove(), 400);
      }, delay);
    });

    // Close buttons
    document.querySelectorAll('.alert-close').forEach(btn => {
      btn.addEventListener('click', () => {
        const alert = btn.closest('.alert');
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 300);
      });
    });
  }

  // ── Intersection Observer Animations ──────────────────────
  function initAnimations() {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.animationPlayState = 'running';
          entry.target.classList.add('in-view');
        }
      });
    }, { threshold: 0.1 });

    document.querySelectorAll('[data-animate]').forEach(el => {
      el.style.animationPlayState = 'paused';
      observer.observe(el);
    });
  }

  // ── Particle Effect (hero) ─────────────────────────────────
  function initParticles() {
    const container = document.getElementById('heroParticles');
    if (!container) return;

    const symbols = ['✦', '★', '✵', '⋆', '✶'];
    const count   = 20;

    for (let i = 0; i < count; i++) {
      const p = document.createElement('span');
      p.textContent = symbols[Math.floor(Math.random() * symbols.length)];
      p.style.cssText = `
        position:absolute;
        font-size:${Math.random() * 12 + 6}px;
        color:rgba(201,168,76,${Math.random() * .3 + .05});
        left:${Math.random() * 100}%;
        top:${Math.random() * 100}%;
        --dx:${(Math.random() - .5) * 100}px;
        animation:dustParticle ${Math.random() * 15 + 10}s linear infinite;
        animation-delay:${Math.random() * 10}s;
        pointer-events:none;
        user-select:none;
      `;
      container.appendChild(p);
    }
  }

    // ── Catastro Integration ───────────────────────────────────
    function initCatastro() {
        const provincia = document.getElementById('provincia');
        const municipio = document.getElementById('municipio');
        const via       = document.getElementById('via');
        if (!provincia || !municipio || !via) return;

        const url = 'controllers/api/catastro.php';

        // Provincias → devuelven [{nombre, codigo}]
        fetch(url, { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'}, body:'accion=provincias' })
            .then(r => r.json())
            .then(data => {
                provincia.innerHTML = '<option value="">— Selecciona Provincia —</option>';
                data.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value          = p.codigo;        // valor del select (interno)
                    opt.textContent    = p.nombre;        // texto visible
                    opt.dataset.nombre = p.nombre;        // guardamos el nombre para municipios
                    provincia.appendChild(opt);
                });
                provincia.disabled = false;
            })
            .catch(() => { provincia.innerHTML = '<option value="">Error al cargar</option>'; provincia.disabled = false; });

        // Municipios → usa codigo_prov (número)
        provincia.addEventListener('change', () => {
            municipio.innerHTML = '<option value="">Cargando municipios...</option>';
            municipio.disabled = true;
            via.innerHTML = '<option value="">— Vía —</option>';
            via.disabled = true;
            if (!provincia.value) return;
            // Obtener el nombre del option seleccionado
            const nombreProv = provincia.options[provincia.selectedIndex]?.dataset.nombre ?? '';
            fetch(url, { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
                body:`accion=municipios&provincia=${encodeURIComponent(nombreProv)}` })
                .then(r => r.json())
                .then(data => {
                    municipio.innerHTML = '<option value="">— Selecciona Municipio —</option>';
                    if (Array.isArray(data)) {
                        data.forEach(m => { const o = document.createElement('option'); o.value = o.textContent = m; municipio.appendChild(o); });
                    }
                    municipio.disabled = false;
                });
        });

        // Vías → usa codigo_prov + nombre municipio
        municipio.addEventListener('change', () => {
            via.innerHTML = '<option value="">Cargando vías...</option>';
            via.disabled = true;
            if (!municipio.value) return;
            const nombreProv = provincia.options[provincia.selectedIndex]?.dataset.nombre ?? '';
            fetch(url, { method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
                body:`accion=vias&provincia=${encodeURIComponent(nombreProv)}&municipio=${encodeURIComponent(municipio.value)}` })
                .then(r => r.json())
                .then(data => {
                    via.innerHTML = '<option value="">— Selecciona Vía —</option>';
                    if (Array.isArray(data)) {
                        data.forEach(v => { const o = document.createElement('option'); o.value = o.textContent = v; via.appendChild(o); });
                    }
                    via.disabled = false;
                });
        });
    }

  // ── Menu Digital Filter ────────────────────────────────────
  function initMenuFilter() {
    const tabs  = document.querySelectorAll('.menu-tab');
    const items = document.querySelectorAll('.menu-item-card');
    if (!tabs.length) return;

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        const cat = tab.dataset.cat;

        items.forEach(item => {
          const match = cat === 'all' || item.dataset.cat === cat;
          item.style.display = match ? 'flex' : 'none';
          if (match) {
            item.style.animation = 'none';
            item.offsetHeight; // reflow
            item.style.animation = 'fadeIn .4s ease forwards';
          }
        });
      });
    });
  }

  // ── Tooltips ───────────────────────────────────────────────
  function initTooltips() {
    let tooltip = null;

    document.querySelectorAll('[data-tip]').forEach(el => {
      el.addEventListener('mouseenter', (e) => {
        tooltip = document.createElement('div');
        tooltip.className = 'western-tooltip';
        tooltip.textContent = el.dataset.tip;
        tooltip.style.cssText = `
          position:fixed;z-index:9999;
          background:var(--bg-surface);border:1px solid var(--border-bright);
          color:var(--text-primary);border-radius:4px;
          padding:6px 12px;font-size:.75rem;font-family:var(--font-heading);
          letter-spacing:1px;pointer-events:none;white-space:nowrap;
          box-shadow:0 4px 20px rgba(0,0,0,.4);
        `;
        document.body.appendChild(tooltip);
        positionTooltip(e);
      });

      el.addEventListener('mousemove', positionTooltip);

      el.addEventListener('mouseleave', () => {
        tooltip?.remove();
        tooltip = null;
      });
    });

    function positionTooltip(e) {
      if (!tooltip) return;
      tooltip.style.left = e.clientX + 14 + 'px';
      tooltip.style.top  = e.clientY - 28 + 'px';
    }
  }

  // ── Form Validation ────────────────────────────────────────
  function initFormValidation() {
    document.querySelectorAll('form[data-validate]').forEach(form => {
      form.addEventListener('submit', e => {
        let valid = true;
        form.querySelectorAll('[required]').forEach(input => {
          if (!input.value.trim()) {
            valid = false;
            input.classList.add('error');
            showFieldError(input, 'Este campo es obligatorio');
          } else {
            input.classList.remove('error');
            clearFieldError(input);
          }
        });

        // Password match
        const pass  = form.querySelector('[name="password"]');
        const cpass = form.querySelector('[name="cpassword"]');
        if (pass && cpass && pass.value !== cpass.value) {
          valid = false;
          cpass.classList.add('error');
          showFieldError(cpass, 'Las contraseñas no coinciden');
        }

        if (!valid) {
          e.preventDefault();
          form.querySelector('.error')?.focus();
        }
      });

      // Real-time validation
      form.querySelectorAll('[required]').forEach(input => {
        input.addEventListener('blur', () => {
          if (!input.value.trim()) {
            input.classList.add('error');
            showFieldError(input, 'Este campo es obligatorio');
          } else {
            input.classList.remove('error');
            clearFieldError(input);
          }
        });
      });
    });
  }

  function showFieldError(input, msg) {
    clearFieldError(input);
    const err = document.createElement('p');
    err.className = 'field-error';
    err.textContent = msg;
    err.style.cssText = 'color:#ef5350;font-size:.75rem;margin-top:4px;font-family:var(--font-heading);letter-spacing:1px;';
    input.parentNode.appendChild(err);
    input.style.borderColor = '#ef5350';
  }

  function clearFieldError(input) {
    input.parentNode.querySelector('.field-error')?.remove();
    input.style.borderColor = '';
  }

  // ── Mesa / Turno Selector (cliente reserva) ───────────────
  function initMesaSelector() {
    const turnoSel = document.getElementById('turno_id');
    const fechaSel = document.getElementById('fecha_reserva');
    const mesaGrid = document.getElementById('mesaGrid');
    const mesaInput= document.getElementById('mesa_id_hidden');
    if (!turnoSel || !fechaSel || !mesaGrid) return;

    function loadMesas() {
      const turno = turnoSel.value;
      const fecha = fechaSel.value;
      if (!turno || !fecha) return;

      mesaGrid.innerHTML = '<p style="color:var(--text-muted);font-style:italic">Buscando mesas disponibles...</p>';

      fetch(`controllers/api/mesas_libres.php?turno_id=${turno}&fecha=${fecha}`)
        .then(r => r.json())
        .then(mesas => {
          if (!mesas.length) {
            mesaGrid.innerHTML = '<p style="color:var(--gold)">No hay mesas disponibles en este turno. 🤠</p>';
            return;
          }
          mesaGrid.innerHTML = '';
          mesas.forEach(m => {
            const div = document.createElement('div');
            div.className = 'mesa-card libre';
            div.dataset.id = m.id;
            div.innerHTML = `
              <div class="mesa-estado-dot"></div>
              <span class="mesa-numero">${m.numero}</span>
              <span class="mesa-capacidad">👥 ${m.capacidad} pers.</span>
              <span style="font-size:.65rem;color:var(--text-muted);letter-spacing:1px">${m.zona_nombre}</span>
            `;
            div.addEventListener('click', () => {
              mesaGrid.querySelectorAll('.mesa-card').forEach(c => c.classList.remove('selected'));
              div.classList.add('selected', 'reservada');
              mesaInput.value = m.id;
              div.style.borderColor = 'var(--gold)';
              div.style.boxShadow = '0 0 20px rgba(201,168,76,.3)';
            });
            mesaGrid.appendChild(div);
          });
        })
        .catch(() => {
          mesaGrid.innerHTML = '<p style="color:#ef5350">Error al cargar mesas.</p>';
        });
    }

    turnoSel.addEventListener('change', loadMesas);
    fechaSel.addEventListener('change', loadMesas);
  }

  // ── Confirm dialogs ────────────────────────────────────────
  window.confirmAction = (msg, form) => {
    if (confirm(msg)) form.submit();
  };

  // ── Number formatting ──────────────────────────────────────
  window.formatEuro = (n) => new Intl.NumberFormat('es-ES', {
    style: 'currency', currency: 'EUR'
  }).format(n);

  // ── Smooth scroll ──────────────────────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const target = document.querySelector(a.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // ── Copy to clipboard ──────────────────────────────────────
  window.copyText = (text, btn) => {
    navigator.clipboard.writeText(text).then(() => {
      const orig = btn.textContent;
      btn.textContent = '✓ Copiado';
      setTimeout(() => btn.textContent = orig, 2000);
    });
  };

  return { init };
})();

// Init on DOM ready
document.addEventListener('DOMContentLoaded', WesternApp.init);

// ═══════════════════════════════════════════════════════════════
// NAVBAR SCROLL EFFECT
// ═══════════════════════════════════════════════════════════════
window.addEventListener('scroll', () => {
  const navbar = document.querySelector('.navbar');
  if (!navbar) return;
  if (window.scrollY > 50) {
    navbar.style.boxShadow = '0 4px 30px rgba(0,0,0,.4)';
  } else {
    navbar.style.boxShadow = 'none';
  }
}, { passive: true });

// ═══════════════════════════════════════════════════════════════
// CHART HELPERS (for stats)
// ═══════════════════════════════════════════════════════════════
window.renderWesternChart = (canvasId, type, labels, data, label) => {
  const ctx = document.getElementById(canvasId)?.getContext('2d');
  if (!ctx) return;

  const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
  const textColor = isDark ? 'rgba(245,234,216,.7)' : 'rgba(44,24,16,.7)';
  const gridColor = isDark ? 'rgba(201,168,76,.08)' : 'rgba(139,69,19,.08)';

  new Chart(ctx, {
    type,
    data: {
      labels,
      datasets: [{
        label,
        data,
        backgroundColor: type === 'bar'
          ? 'rgba(139,69,19,.6)'
          : data.map((_, i) => `hsla(${30 + i * 15},70%,45%,.7)`),
        borderColor: '#c9a84c',
        borderWidth: 2,
        borderRadius: type === 'bar' ? 4 : 0,
        fill: type === 'line',
        tension: 0.4,
        pointBackgroundColor: '#c9a84c',
        pointRadius: 5,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: { duration: 800, easing: 'easeInOutQuart' },
      plugins: {
        legend: {
          labels: { color: textColor, font: { family: "'Cinzel',serif", size: 12 } }
        },
        tooltip: {
          backgroundColor: isDark ? '#1f0d02' : '#fff8f0',
          titleColor: '#c9a84c',
          bodyColor: textColor,
          borderColor: '#c9a84c',
          borderWidth: 1,
          titleFont: { family: "'Cinzel',serif" },
        }
      },
      scales: type !== 'pie' && type !== 'doughnut' ? {
        x: {
          ticks: { color: textColor, font: { family: "'Cinzel',serif", size: 10 } },
          grid: { color: gridColor }
        },
        y: {
          ticks: { color: textColor, font: { family: "'Cinzel',serif", size: 10 } },
          grid: { color: gridColor },
          beginAtZero: true
        }
      } : {}
    }
  });
};
