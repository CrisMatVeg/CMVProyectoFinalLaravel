const STORAGE_KEY = 'pixel-theme';

function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem(STORAGE_KEY, theme);
}

function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme') || 'dark';
    applyTheme(current === 'light' ? 'dark' : 'light');
}

// Aplicar tema guardado antes de que se pinte la página
const saved = localStorage.getItem(STORAGE_KEY) || 'dark';
applyTheme(saved);

window.toggleTheme = toggleTheme;
