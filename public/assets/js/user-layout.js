'use strict';

document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const sidebar = document.getElementById('userSidebar');
    const toggle = document.getElementById('sidebarToggle');
    const closeButton = document.getElementById('sidebarClose');
    const overlay = document.getElementById('sidebarOverlay');
    const desktopQuery = window.matchMedia('(min-width: 992px)');
    const storageKey = 'deepfake_sidebar_collapsed';
    let lastFocusedElement = null;

    if (!sidebar || !toggle || !overlay) return;

    const getStoredCollapsed = () => {
        try {
            return window.localStorage.getItem(storageKey) === 'true';
        } catch (_) {
            return false;
        }
    };

    const setStoredCollapsed = (value) => {
        try {
            window.localStorage.setItem(storageKey, String(value));
        } catch (_) {
            // Preferensi UI tetap berfungsi selama halaman aktif bila storage dibatasi.
        }
    };

    const closeDrawer = (restoreFocus = false) => {
        body.classList.remove('mobile-sidebar-open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Buka menu navigasi');
        sidebar.setAttribute('aria-hidden', desktopQuery.matches ? 'false' : 'true');
        if (restoreFocus && lastFocusedElement) lastFocusedElement.focus();
    };

    const openDrawer = () => {
        lastFocusedElement = document.activeElement;
        body.classList.add('mobile-sidebar-open');
        toggle.setAttribute('aria-expanded', 'true');
        toggle.setAttribute('aria-label', 'Tutup menu navigasi');
        sidebar.setAttribute('aria-hidden', 'false');
        if (closeButton) closeButton.focus();
    };

    const applyViewportState = () => {
        closeDrawer(false);
        if (desktopQuery.matches) {
            body.classList.toggle('sidebar-collapsed', getStoredCollapsed());
            sidebar.setAttribute('aria-hidden', 'false');
        } else {
            body.classList.remove('sidebar-collapsed');
            sidebar.setAttribute('aria-hidden', 'true');
        }
    };

    toggle.addEventListener('click', () => {
        if (desktopQuery.matches) {
            const collapsed = body.classList.toggle('sidebar-collapsed');
            setStoredCollapsed(collapsed);
            toggle.setAttribute('aria-expanded', String(!collapsed));
            toggle.setAttribute('aria-label', collapsed ? 'Perbesar sidebar' : 'Perkecil sidebar');
            return;
        }
        if (body.classList.contains('mobile-sidebar-open')) closeDrawer(true);
        else openDrawer();
    });

    overlay.addEventListener('click', () => closeDrawer(true));
    if (closeButton) closeButton.addEventListener('click', () => closeDrawer(true));
    sidebar.querySelectorAll('.sidebar-link').forEach((link) => link.addEventListener('click', () => {
        if (!desktopQuery.matches) closeDrawer(false);
    }));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && body.classList.contains('mobile-sidebar-open')) closeDrawer(true);
    });

    if (typeof desktopQuery.addEventListener === 'function') desktopQuery.addEventListener('change', applyViewportState);
    else desktopQuery.addListener(applyViewportState);
    applyViewportState();
});
