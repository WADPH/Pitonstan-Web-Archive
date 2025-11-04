<?php
require_once __DIR__ . '/i18n.php';
?>
<header class="header">
    <nav class="nav-container">
        <div class="logo">
            <a href="index.php">
                <img src="img/logo.png" alt="PitBank" class="logo-img">
            </a>
        </div>

        <input type="checkbox" id="menu-toggle" class="menu-toggle">
        <label for="menu-toggle" class="menu-button">☰</label>

        <div class="nav-menu">
            <a href="accounts.php" class="nav-link"><?= __t('nav_my_account') ?></a>
            <a href="about.php" class="nav-link"><?= __t('nav_about') ?></a>

            <div class="dropdown">
                <button class="nav-link dropdown-toggle"><?= __t('nav_more') ?> ▼</button>
                <div class="dropdown-content">
                    <a href="chat.php" class="nav-link"><?= __t('nav_live_chat') ?></a>
                    <a href="private_chat.php" class="nav-link"><?= __t('nav_private_chat') ?></a>
                    <a href="signout.php" class="nav-link"><?= __t('nav_sign_out') ?></a>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Language switcher -->
            <div class="lang-switch">
                <a href="?lang=en" class="lang-btn<?= $lang==='en'?' active':'';?>"><?= __t('lang_en') ?></a>
                <a href="?lang=ru" class="lang-btn<?= $lang==='ru'?' active':'';?>"><?= __t('lang_ru') ?></a>
                <a href="?lang=az" class="lang-btn<?= $lang==='az'?' active':'';?>"><?= __t('lang_az') ?></a>
            </div>

            <!-- Theme toggle -->
            <button id="themeToggle" class="theme-toggle" aria-label="Toggle theme" title="<?= __t('theme_toggle') ?>">
                <svg id="iconSun" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M6.76 4.84l-1.8-1.79-1.41 1.41 1.79 1.8 1.42-1.42zm10.48 0l1.79-1.8-1.41-1.41-1.8 1.79 1.42 1.42zM12 4h0V1h0v3zm0 19h0v-3h0v3zM4 12H1v0h3v0zm22 0h-3v0h3v0zM6.76 19.16l-1.42 1.42-1.79-1.8 1.41-1.41 1.8 1.79zM20.45 18.37l1.79 1.8 1.41-1.41-1.8-1.79-1.4 1.4zM12 7a5 5 0 100 10 5 5 0 000-10z"/></svg>
                <svg id="iconMoon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="display:none"><path d="M21.75 15.5A9.75 9.75 0 1111.5 2a8 8 0 0010.25 13.5z"/></svg>
            </button>
        </div>
    </nav>
</header>

<style>
    :root {
        --primary: #8B0000;
        --secondary: #2D2D2D;
        --accent: #C0C0C0;
        --bg: #F8F8F8;
        --card: #FFFFFF;
        --text: #2D2D2D;
        --muted: #666666;
        --border: #EEEEEE;
    }
    html[data-theme="dark"] {
        --primary: #B33A3A;
        --secondary: #E6E6E6;
        --accent: #4A4A4A;
        --bg: #0F1115;
        --card: #151821;
        --text: #E6E6E6;
        --muted: #A0A0A0;
        --border: #222634;
    }

    .header {
        background: var(--card);
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        padding: 0 2rem;
        position: relative;
        border-bottom: 1px solid var(--border);
    }
    .nav-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        height: 70px;
    }
    .logo { height: 50px; width: auto; margin-right: 40px; display: flex; align-items: center; }
    .logo-img { height: 100%; width: auto; max-width: 125px; object-fit: contain; }

    .menu-toggle { display: none; }
    .menu-button { display: none; font-size: 24px; cursor: pointer; padding: 10px; margin-left: auto; color: var(--text); }

    .nav-menu { display: flex; gap: 14px; margin-left: auto; align-items: center; }
    .nav-link {
        color: var(--text);
        text-decoration: none;
        padding: 10px 16px;
        border-radius: 8px;
        transition: background .2s ease, color .2s ease;
        font-size: 15px;
    }
    .nav-link:hover { background: rgba(139,0,0,0.10); color: var(--primary); }

    .dropdown { position: relative; display: inline-block; }
    .dropdown-toggle {
        background: none; border: 1px solid transparent; cursor: pointer;
        font: inherit; font-size: 15px; color: var(--text);
        padding: 10px 16px; border-radius: 8px;
        transition: background .2s ease, color .2s ease;
    }
    .dropdown-toggle:hover { background: rgba(139,0,0,0.10); color: var(--primary); }
    .dropdown-content {
        display: none; position: absolute; right: 0; background-color: var(--card);
        min-width: 200px; box-shadow: 0 8px 16px rgba(0,0,0,0.12);
        z-index: 10; border-radius: 10px; overflow: hidden; border: 1px solid var(--border);
    }
    .dropdown:hover .dropdown-content { display: block; }
    .dropdown-content .nav-link {
        width: 100%; border-radius: 0; display: block; padding: 12px 16px; border-bottom: 1px solid var(--border);
    }
    .dropdown-content .nav-link:last-child { border-bottom: none; }

    .divider { width: 1px; height: 24px; background: var(--border); margin: 0 8px; }

    .lang-switch { display: flex; gap: 6px; }
    .lang-btn {
        display: inline-block; padding: 8px 10px; border-radius: 8px;
        border: 1px solid var(--border); text-decoration: none; color: var(--text);
        font-weight: 600; font-size: 12px;
    }
    .lang-btn.active, .lang-btn:hover { border-color: var(--primary); color: var(--primary); }

    .theme-toggle {
        border: 1px solid var(--border);
        background: transparent; color: var(--text);
        padding: 8px 10px; border-radius: 10px; cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
    }

    @media (max-width: 768px) {
        .logo { height: 40px; margin-right: 20px; }
        .logo-img { max-width: 100px; }
        .menu-button { display: block; }
        .nav-menu {
            display: none; position: absolute; top: 70px; left: 0; right: 0;
            background: var(--card); flex-direction: column; gap: 0; padding: 10px 0;
            box-shadow: 0 5px 10px rgba(0,0,0,0.10); border-bottom: 1px solid var(--border);
        }
        .menu-toggle:checked ~ .nav-menu { display: flex; }
        .nav-link, .dropdown-toggle { padding: 12px 16px; border-bottom: 1px solid var(--border); border-radius: 0; width: 100%; text-align: left; }
        .dropdown { width: 100%; }
        .dropdown-content { position: static; box-shadow: none; display: none; width: 100%; border: none; }
        .dropdown:hover .dropdown-content { display: none; }
        .menu-toggle:checked ~ .nav-menu .dropdown-content { display: block; }
        .divider { display: none; }
        .lang-switch { padding: 8px 16px; }
    }
</style>

<script>
// Theme persistence using localStorage and data-theme on <html>
(function() {
    const root = document.documentElement;
    const saved = localStorage.getItem('theme');
    const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    const initial = saved || (prefersDark ? 'dark' : 'light');
    root.setAttribute('data-theme', initial);
    const toggle = document.getElementById('themeToggle');
    const iconSun = document.getElementById('iconSun');
    const iconMoon = document.getElementById('iconMoon');

    function updateIcons() {
        const isDark = root.getAttribute('data-theme') === 'dark';
        iconSun.style.display = isDark ? 'none' : 'inline';
        iconMoon.style.display = isDark ? 'inline' : 'none';
    }
    updateIcons();

    if (toggle) {
        toggle.addEventListener('click', function() {
            const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
            updateIcons();
        });
    }
})();
</script>

<?php
// Auth redirect guard
if (empty($_COOKIE['user_login']) && empty($_COOKIE['whoisthis'])) {
    header("location: login.php");
    exit;
}
?>
