(function () {
    'use strict';

    const BREAKPOINT_MOBILE = 768;
    const NAV_SLOTS = 5;

    let sidebar, mainEl, bottomNav, sheet, overlay;
    let isSheetOpen = false;

    function isMobile() { return window.innerWidth < BREAKPOINT_MOBILE; }

    function readSidebarItems() {
        const items = [];
        sidebar.querySelectorAll('.menu-item').forEach(el => {
            if (el.dataset.mobileHide) return;

            const iconEl  = el.querySelector('.menu-icon i') || el.querySelector('.menu-icon');
            const labelEl = el.querySelector('span:not(.menu-icon)');
            if (!labelEl) return;

            const iconClass = iconEl ? iconEl.className : '';
            const label     = labelEl.textContent.trim();
            const href      = el.tagName === 'A' ? el.getAttribute('href') : null;
            const isActive  = el.classList.contains('active');

            items.push({ iconClass, label, href, isActive });
        });
        return items;
    }

    function readUserData() {
        const userEl = sidebar.querySelector('.sidebar-user');
        if (!userEl) return null;

        const nameEl    = userEl.querySelector('.user-info strong');
        const roleEl    = userEl.querySelector('.user-info span');
        const logoutFn  = userEl.querySelector('form[action*="logout"]') ||
                          userEl.querySelector('.sidebar-logout-form');

        return {
            name:       nameEl   ? nameEl.textContent.trim() : '',
            role:       roleEl   ? roleEl.textContent.trim() : '',
            logoutForm: logoutFn ? logoutFn.cloneNode(true)  : null,
        };
    }

    function createTab(item) {
        const tag = item.href ? 'a' : 'button';
        const tab = document.createElement(tag);
        tab.className = 'bn-tab' + (item.isActive ? ' active' : '');
        if (item.href) tab.href = item.href;

        const icon = document.createElement('span');
        icon.className = 'bn-tab-icon';
        icon.innerHTML = `<i class="${item.iconClass}"></i>`;

        const lbl = document.createElement('span');
        lbl.className = 'bn-tab-label';
        lbl.textContent = item.label;

        tab.appendChild(icon);
        tab.appendChild(lbl);
        return tab;
    }

    function createMoreTab() {
        const btn = document.createElement('button');
        btn.className = 'bn-tab';
        btn.id = 'bn-more-btn';
        btn.setAttribute('aria-label', 'Más opciones');
        btn.setAttribute('aria-expanded', 'false');
        btn.innerHTML = `
            <span class="bn-tab-icon"><i class="fa-solid fa-ellipsis"></i></span>
            <span class="bn-tab-label">Más</span>
        `;
        btn.addEventListener('click', openSheet);
        return btn;
    }

    function buildBottomNav(items) {
        bottomNav = document.createElement('nav');
        bottomNav.id = 'mobile-bottom-nav';
        bottomNav.setAttribute('role', 'navigation');
        bottomNav.setAttribute('aria-label', 'Navegación principal');

        items.slice(0, NAV_SLOTS).forEach(item => bottomNav.appendChild(createTab(item)));
        bottomNav.appendChild(createMoreTab());

        document.body.appendChild(bottomNav);
    }

    function buildSheet(items, userData) {
        sheet = document.createElement('div');
        sheet.id = 'mobile-sheet';
        sheet.setAttribute('role', 'dialog');
        sheet.setAttribute('aria-label', 'Más opciones');
        sheet.setAttribute('aria-modal', 'true');

        const overflowItems = items.slice(NAV_SLOTS);

        let html = `
            <div class="sheet-handle" aria-hidden="true"></div>
            <div class="sheet-header">
                <span class="sheet-title">Más</span>
                <button class="sheet-close-btn" id="sheet-close-btn" aria-label="Cerrar">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="sheet-items">
        `;

        overflowItems.forEach(item => {
            const tag  = item.href ? 'a' : 'div';
            const href = item.href ? `href="${item.href}"` : '';
            html += `
                <${tag} class="sheet-item${item.isActive ? ' active' : ''}" ${href} role="menuitem">
                    <span class="sheet-item-icon"><i class="${item.iconClass}"></i></span>
                    <span>${item.label}</span>
                </${tag}>
            `;
        });

        html += `
            <div class="sheet-divider"></div>
            <button class="sheet-item" id="sheet-theme-btn">
                <span class="sheet-item-icon"><i class="fa-solid fa-circle-half-stroke"></i></span>
                <span>Cambiar tema</span>
            </button>
        `;

        if (userData && userData.logoutForm) {
            html += `
                <div class="sheet-divider"></div>
                <button class="sheet-item" id="sheet-logout-btn">
                    <span class="sheet-item-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>
                    <span>Cerrar sesión</span>
                </button>
            `;
        }

        html += `</div>`;

        if (userData) {
            const initials = userData.name ? userData.name.charAt(0).toUpperCase() : '?';
            html += `
                <div class="sheet-user">
                    <div class="sheet-avatar">${initials}</div>
                    <div class="sheet-user-info">
                        <strong class="sheet-user-name">${userData.name}</strong>
                        <span class="sheet-user-role">${userData.role}</span>
                    </div>
                </div>
            `;
        }

        sheet.innerHTML = html;
        document.body.appendChild(sheet);

        document.getElementById('sheet-close-btn').addEventListener('click', closeSheet);

        const themeBtn = document.getElementById('sheet-theme-btn');
        if (themeBtn) {
            themeBtn.addEventListener('click', () => {
                if (typeof toggleTheme === 'function') toggleTheme();
                closeSheet();
            });
        }

        const logoutBtn = document.getElementById('sheet-logout-btn');
        if (logoutBtn && userData && userData.logoutForm) {
            logoutBtn.addEventListener('click', () => {
                document.body.appendChild(userData.logoutForm);
                userData.logoutForm.submit();
            });
        }

        sheet.querySelectorAll('a.sheet-item').forEach(link => {
            link.addEventListener('click', closeSheet);
        });
    }

    function buildOverlay() {
        overlay = document.createElement('div');
        overlay.id = 'mobile-overlay';
        overlay.addEventListener('click', closeSheet);
        document.body.appendChild(overlay);
    }

    function openSheet() {
        isSheetOpen = true;
        sheet.classList.add('open');
        overlay.classList.add('visible');
        const moreBtn = document.getElementById('bn-more-btn');
        moreBtn.setAttribute('aria-expanded', 'true');
        moreBtn.classList.add('active');
    }

    function closeSheet() {
        isSheetOpen = false;
        sheet.classList.remove('open');
        overlay.classList.remove('visible');
        const moreBtn = document.getElementById('bn-more-btn');
        if (moreBtn) {
            moreBtn.setAttribute('aria-expanded', 'false');
            moreBtn.classList.remove('active');
        }
    }

    function wrapTables() {
        document.querySelectorAll('.data-table').forEach(table => {
            if (!table.closest('.table-responsive-wrap')) {
                const wrap = document.createElement('div');
                wrap.className = 'table-responsive-wrap';
                table.parentNode.insertBefore(wrap, table);
                wrap.appendChild(table);
            }
        });
    }

    function handleResize() {
        if (!isMobile() && isSheetOpen) closeSheet();
    }

    function init() {
        sidebar = document.querySelector('aside.sidebar');
        mainEl  = document.querySelector('main');
        if (!sidebar || !mainEl) return;

        if (!isMobile()) {
            window.addEventListener('resize', () => {
                clearTimeout(window._rnTimer);
                window._rnTimer = setTimeout(handleResize, 150);
            });
            return;
        }

        const items    = readSidebarItems();
        const userData = readUserData();

        buildOverlay();
        buildBottomNav(items);
        buildSheet(items, userData);
        wrapTables();

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && isSheetOpen) closeSheet();
        });

        window.addEventListener('resize', () => {
            clearTimeout(window._rnTimer);
            window._rnTimer = setTimeout(handleResize, 150);
        });
    }

    document.readyState === 'loading'
        ? document.addEventListener('DOMContentLoaded', init)
        : init();
})();
