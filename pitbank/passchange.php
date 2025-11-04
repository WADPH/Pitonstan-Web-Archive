<?php
// i18n first for lang and theme
require_once __DIR__ . "/i18n.php";
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>PitBank Change Pass</title>
    <link rel="icon" type="image/x-icon" href="img/PitBankIco.ico">
    <style>
        /* Tokens */
        :root{--primary:#8B0000;--secondary:#2D2D2D;--accent:#C0C0C0;--bg:#F8F8F8;--card:#FFFFFF;--text:#2D2D2D;--muted:#666;--border:#EAEAEA}
        html[data-theme="dark"]{--primary:#B33A3A;--secondary:#E6E6E6;--accent:#4A4A4A;--bg:#0F1115;--card:#151821;--text:#E6E6E6;--muted:#A0A0A0;--border:#222634}

        /* Base */
        *{box-sizing:border-box}
        body{margin:0;display:flex;flex-direction:column;min-height:100vh;background:var(--bg);color:var(--text)}
        .main{flex:1;display:flex;justify-content:center;align-items:center;padding:32px 16px}

        /* Card */
        .card{background:var(--card);border:1px solid var(--border);border-radius:16px;box-shadow:0 10px 28px rgba(0,0,0,.06);padding:24px;width:100%;max-width:520px}
        h2{margin:0 0 14px 0;color:var(--secondary);font-size:1.3rem}
        .hint{color:var(--muted);font-size:.95rem;margin-bottom:10px}

        .group{margin-bottom:14px}
        label{display:block;color:var(--muted);margin-bottom:6px;font-size:.95rem}
        .input{width:100%;padding:12px 14px;border:1px solid var(--border);border-radius:12px;background:transparent;color:var(--text)}
        .input:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(139,0,0,.12)}

        .btn{width:100%;height:44px;border-radius:12px;border:1px solid var(--border);font-weight:800;cursor:pointer}
        .btn-primary{background:var(--primary);color:#fff;border-color:transparent}
        .btn-primary:hover{filter:brightness(.95)}

        .alert{margin-top:6px;font-size:.9rem}
        .err{color:#b81e1e}
        .ok{color:#1f7a1f}
    </style>
</head>
<body>

<?php require_once __DIR__."/header.php"; ?>

<main class="main">
    <div class="card">
        <h2>Change Password</h2>
        <div class="hint">Use at least 8 characters. Avoid common words.</div>

        <?php
        // Read feedback cookies safely
        $errWrong = htmlspecialchars($_COOKIE['passwrong_err'] ?? '');
        $errCheck = htmlspecialchars($_COOKIE['passcheck_err'] ?? '');
        $okMsg    = htmlspecialchars($_COOKIE['success'] ?? '');
        ?>

        <?php if($errWrong): ?><div class="alert err"><?= $errWrong ?></div><?php endif; ?>
        <?php if($errCheck): ?><div class="alert err"><?= $errCheck ?></div><?php endif; ?>
        <?php if($okMsg):   ?><div class="alert ok"><?= $okMsg   ?></div><?php endif; ?>

        <form action="update_password.php" method="post" autocomplete="off" novalidate>
            <div class="group">
                <label for="cur">Current Password</label>
                <input id="cur" type="password" name="current_pass" class="input" required>
            </div>
            <div class="group">
                <label for="newp">New Password</label>
                <input id="newp" type="password" name="new_pass" class="input" required>
            </div>
            <div class="group">
                <label for="rep">Repeat New Password</label>
                <input id="rep" type="password" name="repeat_pass" class="input" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>
</main>

<?php
require_once __DIR__."/footer.php";

/* Cleanup feedback cookies after render */
foreach (['success','passcheck_err','passwrong_err'] as $c){
    if(isset($_COOKIE[$c])){ setcookie($c, "", time()-86400, "/"); unset($_COOKIE[$c]); }
}
?>
</body>
</html>
