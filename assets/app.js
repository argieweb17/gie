import './stimulus_bootstrap.js';
/*
 * NORSU Alumni Tracker — main JS entry point
 * Loaded via importmap (ES module = executes exactly once).
 */
import './styles/app.css';

/* ═══════════════════════════════════════════════════════════════
   SIDEBAR HELPERS
   ═══════════════════════════════════════════════════════════════ */
function closeSidebar() {
    const sb = document.getElementById('sidebar');
    const bd = document.getElementById('sidebarBackdrop');
    if (sb) sb.classList.remove('show');
    if (bd) bd.classList.remove('show');
}

function toggleSidebar() {
    const sb = document.getElementById('sidebar');
    const bd = document.getElementById('sidebarBackdrop');
    if (sb) sb.classList.toggle('show');
    if (bd) bd.classList.toggle('show');
}

/* Toggle sidebar dropdown sub-menu */
window.toggleSidebarDropdown = function(btn) {
    const dropdown = btn.closest('.sidebar-dropdown');
    if (!dropdown) return;
    const menu = dropdown.querySelector('.sidebar-dropdown-menu');
    if (!menu) return;
    const isOpen = dropdown.classList.contains('open');
    if (isOpen) {
        menu.style.display = 'none';
        dropdown.classList.remove('open');
    } else {
        menu.style.display = '';
        dropdown.classList.add('open');
    }
};

/* Update which sidebar link gets the .active highlight */
function updateActiveLink() {
    const currentPath = window.location.pathname;
    document.querySelectorAll('.sidebar .nav-link').forEach(link => {
        const href = link.getAttribute('href');
        if (!href) return;
        const isActive = href === currentPath
            || (href !== '/' && currentPath.startsWith(href));
        link.classList.toggle('active', isActive);
    });
}

/* ═══════════════════════════════════════════════════════════════
   EVENT DELEGATION (click) — survives every Turbo body swap
   ═══════════════════════════════════════════════════════════════ */
document.addEventListener('click', (e) => {
    /* Sidebar toggle button */
    if (e.target.closest('#sidebarToggle')) {
        toggleSidebar();
        return;
    }
    /* Backdrop click → close */
    if (e.target.id === 'sidebarBackdrop') {
        closeSidebar();
        return;
    }
    /* Sidebar nav-link → close sidebar immediately (Turbo handles navigation) */
    if (e.target.closest('.sidebar .nav-link') && !e.target.closest('.sidebar-dropdown-toggle')) {
        closeSidebar();
    }
    /* Guest navbar-collapse → close after link click on mobile */
    if (e.target.closest('#navbarNav a')) {
        const navCollapse = document.getElementById('navbarNav');
        if (navCollapse && navCollapse.classList.contains('show')) {
            const bsCollapse = bootstrap.Collapse.getInstance(navCollapse);
            if (bsCollapse) bsCollapse.hide();
        }
    }
});

/* ═══════════════════════════════════════════════════════════════
   TURBO LIFECYCLE HOOKS
   ═══════════════════════════════════════════════════════════════ */

/*
 * turbo:before-cache — runs RIGHT BEFORE Turbo snapshots the page.
 * Clean up transient UI state so the cached copy is pristine.
 * This prevents the "open sidebar / stale content" snapshot bug.
 */
document.addEventListener('turbo:before-cache', () => {
    closeSidebar();
    /* Collapse any open Bootstrap navbar (guest view) */
    document.querySelectorAll('.navbar-collapse.show').forEach(el => {
        el.classList.remove('show');
    });
    /* Dispose and clean up Bootstrap modals */
    document.querySelectorAll('.modal').forEach(el => {
        const inst = bootstrap.Modal.getInstance(el);
        if (inst) inst.dispose();
        el.classList.remove('show');
        el.removeAttribute('style');
        el.removeAttribute('aria-modal');
        el.removeAttribute('role');
        el.setAttribute('aria-hidden', 'true');
    });
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('overflow');
    document.body.style.removeProperty('padding-right');
    /* Strip auth-animate so cached snapshot won't replay entrance animations */
    document.querySelectorAll('.auth-animate').forEach(el => el.classList.remove('auth-animate'));
    /* Strip sb-animate so subject page animations don't replay from cache */
    document.querySelectorAll('.sb-animate').forEach(el => el.classList.remove('sb-animate'));
    /* Reset main-content opacity so cached snapshot is clean */
    const mc = document.querySelector('.main-content');
    if (mc) { mc.style.opacity = '0'; mc.style.animation = 'none'; }
});

/*
 * turbo:before-visit — runs when the user clicks a Turbo-enabled link.
 * Fade out content for smooth transition.
 */
document.addEventListener('turbo:before-visit', () => {
    closeSidebar();
    const mc = document.querySelector('.main-content');
    if (mc) {
        
        mc.style.opacity = '0';
    }
});

/*
 * turbo:load — runs after EVERY successful page render
 *   (initial load + every Turbo visit + back/forward cache restore).
 * This is the single hook that guarantees fresh state.
 */
document.addEventListener('turbo:load', () => {
    /* 1. Update sidebar active highlighting */
    updateActiveLink();

    /* 2. Ensure sidebar is closed (safety net for cached snapshots) */
    closeSidebar();

    /* 3. Fade in main content smoothly */
    const mc = document.querySelector('.main-content');
    if (mc) {
        mc.style.transition = '';
        mc.style.animation = '';
        mc.style.opacity = '';
    }

    /* 4. Add auth-animate to trigger entrance animations on fresh render */
    document.querySelectorAll('.auth-page').forEach(el => el.classList.add('auth-animate'));

    /* 5. Re-initialise any Bootstrap tooltips / popovers on new DOM */
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        if (typeof bootstrap !== 'undefined') new bootstrap.Tooltip(el);
    });
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
        if (typeof bootstrap !== 'undefined') new bootstrap.Popover(el);
    });

    /* 6. Move modals to <body> so they escape .main-content stacking context */
    document.querySelectorAll('.main-content .modal').forEach(el => {
        document.body.appendChild(el);
    });
});

/*
 * turbo:before-render — runs just before Turbo swaps in the new <body>.
 * Dispose Bootstrap components attached to the OLD body so they don't leak.
 */
document.addEventListener('turbo:before-render', () => {
    /* Dispose Bootstrap modals on old body */
    document.querySelectorAll('.modal').forEach(el => {
        const inst = bootstrap.Modal.getInstance(el);
        if (inst) inst.dispose();
    });
    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('overflow');
    document.body.style.removeProperty('padding-right');
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        const tip = bootstrap.Tooltip.getInstance(el);
        if (tip) tip.dispose();
    });
    document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
        const pop = bootstrap.Popover.getInstance(el);
        if (pop) pop.dispose();
    });
});
