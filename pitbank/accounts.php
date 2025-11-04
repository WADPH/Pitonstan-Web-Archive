<?php
// i18n first for correct <html lang>
require_once __DIR__.'/i18n.php';

// DB connection
require_once __DIR__.'/db_connection.php';

// Resolve username from cookies
$username = 'Guest';
if (!empty($_COOKIE['user_login'])) { $username = $_COOKIE['user_login']; }
elseif (!empty($_COOKIE['whoisthis'])) { $username = $_COOKIE['whoisthis']; }

// Fetch user info with prepared statement (SQL injection safe)
$userinfo = [
    'id' => 0,
    'username' => htmlspecialchars($username, ENT_QUOTES, 'UTF-8'),
    'email' => '',
    'date' => '',
    'money' => '0'
];
if ($stmt = $mysql->prepare("SELECT id, username, email, date, money FROM users WHERE username = ? LIMIT 1")) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($id, $uname, $email, $date, $money);
    if ($stmt->fetch()) {
        $userinfo['id'] = (int)$id;
        $userinfo['username'] = htmlspecialchars((string)$uname, ENT_QUOTES, 'UTF-8');
        $userinfo['email'] = htmlspecialchars((string)$email, ENT_QUOTES, 'UTF-8');
        $userinfo['date'] = htmlspecialchars((string)$date, ENT_QUOTES, 'UTF-8');
        $userinfo['money'] = htmlspecialchars((string)$money, ENT_QUOTES, 'UTF-8');
    }
    $stmt->close();
}
$mysql->close();

// Derived values
$accountId = 'PB-' . str_pad((string)$userinfo['id'], 8, '0', STR_PAD_LEFT);
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __t('profile_page_title') ?></title>
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

        /* Base */
        *{margin:0;padding:0;box-sizing:border-box;font-family:'Roboto',system-ui,-apple-system,Segoe UI,Arial,sans-serif}
        body{display:flex;flex-direction:column;min-height:100vh;background:var(--bg);color:var(--text)}
        .main-content{flex:1;max-width:1200px;width:100%;margin:30px auto;padding:0 20px}

        /* Profile header card */
        .profile-card{background:var(--card);padding:20px;border-radius:16px;box-shadow:0 10px 28px rgba(0,0,0,.06);border:1px solid var(--border)}
        .profile-header{display:flex;align-items:center;gap:18px;flex-wrap:wrap}
        .profile-avatar{width:96px;height:96px;border-radius:50%;background:var(--accent);overflow:hidden;display:flex;align-items:center;justify-content:center;border:1px solid var(--border)}
        .profile-avatar img{width:100%;height:100%;object-fit:cover;object-position:center}
        .profile-info{flex:1;min-width:220px}
        .profile-title{font-size:1.6rem;color:var(--secondary);font-weight:900;letter-spacing:-0.3px}
        .detail-row{display:flex;flex-wrap:wrap;gap:10px;margin-top:8px}
        .detail-chip{display:inline-flex;gap:6px;align-items:center;padding:6px 10px;border:1px solid var(--border);border-radius:999px;background:transparent;color:var(--muted);font-size:.9rem}

        /* Right side of header safely wraps on mobile */
        .profile-aside{display:flex;flex-direction:column;align-items:flex-end;gap:8px;margin-left:auto;min-width:160px}
        .balance{font-size:1.6rem;color:var(--primary);font-weight:900;white-space:nowrap}
        .btn{display:inline-block;padding:10px 14px;border-radius:10px;border:1px solid var(--border);text-decoration:none;font-weight:800;cursor:pointer}
        .btn-primary{background:var(--primary);color:#fff;border-color:transparent}
        .btn-ghost{background:transparent;color:var(--text)}
        @media (max-width: 600px){
            .profile-aside{align-items:stretch;width:100%}
            .balance{font-size:1.3rem}
            .btn{width:100%}
        }

        /* Grid sections */
        .grid{display:grid;grid-template-columns:2fr 1fr;gap:18px;margin-top:18px}
        .card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:18px;box-shadow:0 6px 20px rgba(0,0,0,.05)}
        .card h3{font-size:1.2rem;margin-bottom:10px;color:var(--secondary)}
        .muted{color:var(--muted)}
        .list{list-style:none;padding:0;margin:0}
        .list li{display:flex;justify-content:space-between;gap:10px;padding:10px 0;border-bottom:1px solid var(--border);font-size:.95rem}
        .list li:last-child{border-bottom:none}
        .tag{display:inline-block;padding:4px 8px;border:1px solid var(--border);border-radius:999px;font-size:12px;color:var(--muted)}
        @media (max-width: 980px){ .grid{grid-template-columns:1fr} }

        /* Pretty “coming soon” tiles replace dead links */
        .tiles{display:grid;grid-template-columns:1fr;gap:10px}
        .tile{border:1px dashed var(--border);border-radius:12px;padding:12px}
        .tile-title{font-weight:800;color:var(--secondary)}
        .tile-note{font-size:.9rem;color:var(--muted);margin-top:4px}
        .badge{display:inline-block;padding:4px 8px;border:1px solid var(--border);border-radius:999px;font-size:12px;color:var(--muted);margin-top:6px}
    </style>
</head>
<body>

<?php require_once __DIR__."/header.php"; ?>

<main class="main-content">

    <!-- Profile header -->
    <section class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <!-- Profile picture -->
                <img src="img/profile_photo.jpg" alt="Profile Picture">
            </div>

            <div class="profile-info">
                <div class="profile-title"><?= __t('profile_title') ?> — <?= $userinfo['username']; ?></div>
                <div class="detail-row">
                    <span class="detail-chip"><strong><?= __t('registration_date') ?>:</strong> <?= $userinfo['date'] ?: '—' ?></span>
                    <span class="detail-chip"><strong><?= __t('full_name') ?>:</strong> <?= $userinfo['username']; ?></span>
                    <span class="detail-chip"><strong><?= __t('email') ?>:</strong> <?= $userinfo['email'] ?: '—' ?></span>
                    <span class="detail-chip"><strong><?= __t('account_id') ?>:</strong> <?= $accountId ?></span>
                </div>
            </div>

            <div class="profile-aside">
                <div class="balance"><?= $userinfo['money'] ?> ῥ</div>
                <a href="passchange.php" class="btn btn-primary"><?= __t('change_password') ?></a>
            </div>
        </div>
    </section>

    <!-- Content grid -->
    <section class="grid">
        <!-- Left column: activity + security -->
        <div class="card">
            <h3><?= __t('activity_title') ?></h3>
            <ul class="list">
                <!-- Example static items; replace with DB history if available -->
                <li>
                    <span><?= __t('activity_transfer') ?> · PB-00000001 → PB-00000002</span>
                    <span class="muted">−0 ῥ</span>
                </li>
                <li>
                    <span><?= __t('activity_deposit') ?> · Cash-in branch #1</span>
                    <span class="muted">+0 ῥ</span>
                </li>
                <li>
                    <span><?= __t('activity_withdrawal') ?> · ATM #221</span>
                    <span class="muted">−0 ῥ</span>
                </li>
            </ul>

            <div style="height:12px"></div>
            <h3><?= __t('security_title') ?></h3>
            <ul class="list">
                <li>
                    <span><?= __t('sec_2fa') ?></span>
                    <span><span class="tag"><?= __t('sec_2fa_status_off') ?></span></span>
                </li>
                <li>
                    <span><?= __t('sec_last_login') ?></span>
                    <span class="muted">—</span>
                </li>
                <li>
                    <span><?= __t('sec_active_sessions') ?></span>
                    <span class="muted">1</span>
                </li>
            </ul>
            <!-- Removed "Manage security" per request -->
        </div>

        <!-- Right column: quick actions; replaced with card order + pretty tiles -->
        <div class="card">
            <h3><?= __t('quick_actions') ?></h3>

            <!-- Primary CTA: order new card (no navigation yet) -->
            <button type="button" class="btn btn-primary" style="width:100%;margin-bottom:10px">
                <?= __t('order_new_card') ?>
            </button>

            <!-- Decorative tiles instead of dead links -->
            <div class="tiles">
                <div class="tile">
                    <div class="tile-title"><?= __t('virtual_card') ?></div>
                    <div class="tile-note">—</div>
                    <span class="badge"><?= __t('coming_soon') ?></span>
                </div>
                <div class="tile">
                    <div class="tile-title"><?= __t('spending_analytics') ?></div>
                    <div class="tile-note">—</div>
                    <span class="badge"><?= __t('coming_soon') ?></span>
                </div>
                <div class="tile">
                    <div class="tile-title"><?= __t('mobile_payments') ?></div>
                    <div class="tile-note">—</div>
                    <span class="badge"><?= __t('coming_soon') ?></span>
                </div>
            </div>

            <div style="height:12px"></div>
            <h3><?= __t('limits_title') ?></h3>
            <ul class="list">
                <li>
                    <span><?= __t('limit_monthly') ?></span>
                    <span class="muted">10,000 ῥ</span>
                </li>
                <li>
                    <span><?= __t('kyc_status') ?></span>
                    <span class="tag"><?= __t('kyc_unverified') ?></span>
                </li>
            </ul>
            <!-- Removed "Upgrade KYC" button per request -->
        </div>
    </section>

</main>

<?php require_once __DIR__."/footer.php"; ?>
</body>
</html>
