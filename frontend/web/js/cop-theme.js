document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('copThemeToggle');

    if (!themeToggle) {
        return;
    }

    const body = document.body;
    const icon = themeToggle.querySelector('i');

    function applyTheme(theme) {
        const isLight = theme === 'light';

        body.classList.toggle('cop-light-mode', isLight);

        if (icon) {
            icon.className = isLight ? 'fas fa-sun' : 'fas fa-moon';
        }

        localStorage.setItem('copTheme', isLight ? 'light' : 'dark');
    }

    const savedTheme = localStorage.getItem('copTheme');

    if (savedTheme === 'light') {
        applyTheme('light');
    } else {
        applyTheme('dark');
    }

    themeToggle.addEventListener('click', function () {
        const isCurrentlyLight = body.classList.contains('cop-light-mode');

        applyTheme(isCurrentlyLight ? 'dark' : 'light');
    });
});