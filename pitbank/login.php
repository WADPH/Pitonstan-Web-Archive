<?php
// i18n + theme-ready login page
require_once __DIR__ . '/i18n.php';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PitBank Login</title>
    <link rel="icon" type="image/x-icon" href="img/PitBankIco.ico">
    <style>
        /* Design tokens */
        :root{
            --primary:#8B0000;--secondary:#2D2D2D;--accent:#C0C0C0;
            --bg:#F8F8F8;--card:#FFFFFF;--text:#2D2D2D;--muted:#666;--border:#EAEAEA;
        }
        html[data-theme="dark"]{
            --primary:#B33A3A;--secondary:#E6E6E6;--accent:#4A4A4A;
            --bg:#0F1115;--card:#151821;--text:#E6E6E6;--muted:#A0A0A0;--border:#222634;
        }

        /* Base reset */
        *{margin:0;padding:0;box-sizing:border-box;font-family:'Roboto',system-ui,-apple-system,Segoe UI,Arial,sans-serif}
        body{min-height:100vh;display:flex;flex-direction:column;background:var(--bg);color:var(--text)}

        /* Simple top bar with logo, language, theme toggle */
        .topbar{background:var(--card);border-bottom:1px solid var(--border);box-shadow:0 2px 10px rgba(0,0,0,.04)}
        .topbar-inner{max-width:1200px;margin:0 auto;height:70px;display:flex;align-items:center;gap:16px;padding:0 20px}
        .logo{height:46px;display:flex;align-items:center}
        .logo img{height:100%;width:auto;object-fit:contain;max-width:140px}
        .spacer{flex:1}
        .lang-switch{display:flex;gap:6px}
        .lang-btn{display:inline-block;padding:8px 10px;border-radius:8px;border:1px solid var(--border);text-decoration:none;color:var(--text);font-weight:700;font-size:12px}
        .lang-btn.active,.lang-btn:hover{border-color:var(--primary);color:var(--primary)}
        .theme-toggle{border:1px solid var(--border);background:transparent;color:var(--text);padding:8px 10px;border-radius:10px;cursor:pointer;display:inline-flex;align-items:center;gap:8px}

        /* Auth layout */
        .auth-main{flex:1;display:flex;align-items:center;padding:40px 20px}
        .auth-container{max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:36px;width:100%}
        .auth-form{background:var(--card);padding:32px;border-radius:14px;border:1px solid var(--border);box-shadow:0 8px 24px rgba(0,0,0,.05)}
        .auth-form h2{color:var(--secondary);margin-bottom:20px;font-size:22px;font-weight:700}
        .form-group{margin-bottom:16px}
        .form-group label{display:block;color:var(--muted);margin-bottom:8px;font-size:14px}
        .form-control{width:100%;padding:12px 14px;border:1px solid var(--border);border-radius:10px;font-size:15px;background:transparent;color:var(--text);transition:border-color .2s}
        .form-control:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(139,0,0,.12)}
        .btn{width:100%;padding:14px;border:none;border-radius:10px;font-size:15px;font-weight:800;cursor:pointer;transition:filter .2s}
        .btn-primary{background:var(--primary);color:#fff}
        .btn-primary:hover{filter:brightness(.95)}
        .alert{margin:6px 0 10px 0;color:#b81e1e;font-size:13px}

        @media (max-width: 900px){
            .auth-container{grid-template-columns:1fr}
        }
    </style>
</head>
<body>
<header class="topbar">
    <div class="topbar-inner">
        <a class="logo" href="index.php" aria-label="PitBank">
            <img src="img/logo.png" alt="PitBank">
        </a>
        <div class="spacer"></div>

        <!-- Language switch -->
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
</header>

<main class="auth-main">
    <div class="auth-container">
        <!-- Sign In -->
        <div class="auth-form">
            <h2><?= __t('sign_in') ?></h2>
            <?php
            // read error cookies safely
            $errEmail = htmlspecialchars($_COOKIE['emailexists_err'] ?? '');
            $errPass  = htmlspecialchars($_COOKIE['passwrong_err']   ?? '');
            ?>
            <?php if($errEmail): ?><div class="alert"><?= __t('error') ?>: <?= $errEmail ?></div><?php endif; ?>
            <?php if($errPass):  ?><div class="alert"><?= __t('error') ?>: <?= $errPass  ?></div><?php endif; ?>

            <form action="log_check.php" method="post" novalidate>
                <div class="form-group">
                    <label for="login_email"><?= __t('email') ?></label>
                    <input id="login_email" name="login_email" type="email" class="form-control"
                           placeholder="<?= __t('your_email_ph') ?>"
                           value="<?= htmlspecialchars($_COOKIE['login_email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="login_pass"><?= __t('password') ?></label>
                    <input id="login_pass" name="login_pass" type="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary"><?= __t('sign_in') ?></button>
            </form>
        </div>

        <!-- Registration -->
        <div class="auth-form">
            <h2><?= __t('registration') ?></h2>
            <?php
            // read registration error cookies safely
            $errExists     = htmlspecialchars($_COOKIE['exists_err']      ?? '');
            $errPassLen    = htmlspecialchars($_COOKIE['passlength_err']  ?? '');
            $errPassCheck  = htmlspecialchars($_COOKIE['passcheck_err']   ?? '');
            ?>
            <?php if($errExists):    ?><div class="alert"><?= __t('error') ?>: <?= $errExists ?></div><?php endif; ?>
            <?php if($errPassLen):   ?><div class="alert"><?= __t('error') ?>: <?= $errPassLen ?></div><?php endif; ?>
            <?php if($errPassCheck): ?><div class="alert"><?= __t('error') ?>: <?= $errPassCheck ?></div><?php endif; ?>

            <form action="reg_check.php" method="post" novalidate>
                <div class="form-group">
                    <label for="user_login"><?= __t('name') ?></label>
                    <input id="user_login" type="text" name="user_login" class="form-control"
                           placeholder="<?= __t('name_ph') ?>"
                           value="<?= htmlspecialchars($_COOKIE['user_login'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="user_email"><?= __t('email') ?></label>
                    <input id="user_email" type="email" name="user_email" class="form-control"
                           placeholder="<?= __t('email_ph') ?>"
                           value="<?= htmlspecialchars($_COOKIE['user_email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="user_pass"><?= __t('password') ?></label>
                    <input id="user_pass" type="password" name="user_pass" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="user_pass_check"><?= __t('repeat_password') ?></label>
                    <input id="user_pass_check" type="password" name="user_pass_check" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary"><?= __t('create_account') ?></button>
            </form>
        </div>
    </div>
</main>

<?php
require_once "footer.php";

/* cleanup error cookies after render */
$toClear = ['passcheck_err','passlength_err','exists_err','emailexists_err','passwrong_err','login_email'];
foreach($toClear as $c){
    if(isset($_COOKIE[$c])){
        setcookie($c, "", time()-86400, "/");
        unset($_COOKIE[$c]);
    }
}
?>

<script>
// Theme persistence for login page
(function(){
    const root=document.documentElement;
    const saved=localStorage.getItem('theme');
    const prefersDark=window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)')?.matches;
    const initial=saved || (prefersDark?'dark':'light');
    root.setAttribute('data-theme', initial);

    const toggle=document.getElementById('themeToggle');
    const iconSun=document.getElementById('iconSun');
    const iconMoon=document.getElementById('iconMoon');
    function updateIcons(){
        const isDark=root.getAttribute('data-theme')==='dark';
        iconSun.style.display=isDark?'none':'inline';
        iconMoon.style.display=isDark?'inline':'none';
    }
    updateIcons();
    if(toggle){
        toggle.addEventListener('click',function(){
            const next=root.getAttribute('data-theme')==='dark'?'light':'dark';
            root.setAttribute('data-theme',next);
            localStorage.setItem('theme',next);
            updateIcons();
        });
    }
})();
</script>
</body>
</html>
