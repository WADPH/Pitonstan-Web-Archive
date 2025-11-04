<?php
// i18n first so <html lang> is correct
require_once __DIR__.'/i18n.php';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __t('about_page_title') ?></title>
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

        *{margin:0;padding:0;box-sizing:border-box;font-family:'Roboto',system-ui,-apple-system,Segoe UI,Arial,sans-serif}
        body{display:flex;flex-direction:column;min-height:100vh;background:var(--bg);color:var(--text)}

        .main-content{flex:1;max-width:1200px;width:100%;margin:30px auto;padding:0 20px}

        /* Hero */
        .about-hero{display:grid;grid-template-columns:1.2fr .8fr;gap:40px;align-items:center}
        .about-card{background:var(--card);padding:32px;border-radius:16px;border:1px solid var(--border);box-shadow:0 10px 28px rgba(0,0,0,.06)}
        .about-title{color:var(--secondary);font-size:2.4rem;margin-bottom:16px;font-weight:800;letter-spacing:-0.5px}
        .about-text{color:var(--muted);font-size:1rem;line-height:1.7}
        .about-text p{margin-bottom:10px}
        .cta-row{display:flex;gap:12px;margin-top:18px}
        .btn{display:inline-block;padding:12px 18px;border-radius:12px;border:1px solid var(--border);text-decoration:none;font-weight:800}
        .btn-primary{background:var(--primary);color:#fff;border-color:transparent}
        .btn-secondary{background:transparent;color:var(--text)}
        .about-image{border-radius:16px;overflow:hidden;border:1px solid var(--border);box-shadow:0 8px 24px rgba(0,0,0,.06)}
        .about-image img{display:block;width:100%;height:auto}

        /* Sections */
        .section{margin-top:30px}
        .section h2{font-size:1.6rem;margin-bottom:14px;color:var(--secondary)}
        .grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px}
        .card{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:18px;box-shadow:0 6px 20px rgba(0,0,0,.05)}
        .card ul{margin-left:18px;color:var(--muted);line-height:1.6}
        .card li{margin-bottom:6px}
        .badge{display:inline-block;padding:6px 10px;border-radius:999px;border:1px solid var(--border);font-size:12px;color:var(--muted);margin-right:8px;margin-top:6px}

        /* Stats */
        .stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px}
        .stat{background:var(--card);border:1px solid var(--border);border-radius:14px;padding:20px;text-align:center}
        .stat .num{font-size:1.8rem;font-weight:900;color:var(--primary)}
        .stat .label{color:var(--muted);margin-top:6px}

        @media (max-width: 900px){
            .about-hero{grid-template-columns:1fr}
        }
    </style>
</head>
<body>

<?php require_once "header.php"; ?>

<main class="main-content">

    <!-- Hero: intro + image -->
    <section class="about-hero">
        <div class="about-card">
            <h1 class="about-title"><?= __t('about_title') ?></h1>
            <div class="about-text">
                <p><?= __t('about_intro_1') ?></p>
                <p><?= __t('about_intro_2') ?></p>
                <p><?= __t('about_intro_3') ?></p>
            </div>
            <div class="cta-row">
                <a href="login.php" class="btn btn-primary"><?= __t('cta_open_account') ?></a>
                <a href="chat.php" class="btn btn-secondary"><?= __t('cta_contact') ?></a>
            </div>
        </div>
        <div class="about-image">
            <!-- Use your own image if needed -->
            <img src="img/logo2.png" alt="<?= __t('hq_photo_alt') ?>">
        </div>
    </section>

    <!-- Services -->
    <section class="section">
        <h2><?= __t('services_title') ?></h2>
        <div class="grid">
            <div class="card">
                <ul>
                    <li><?= __t('service_view') ?></li>
                    <li><?= __t('service_deposit') ?></li>
                    <li><?= __t('service_transfer') ?></li>
                    <li><?= __t('service_more') ?></li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Partnerships -->
    <section class="section">
        <h2><?= __t('partnerships_title') ?></h2>
        <div class="grid">
            <div class="card">
                <strong>Piton Airlines</strong>
                <p class="about-text"><?= __t('partner_piton_airlines') ?></p>
                <span class="badge">Miles</span><span class="badge">Travel</span>
            </div>
            <div class="card">
                <strong>PitonTV</strong>
                <p class="about-text"><?= __t('partner_piton_tv') ?></p>
                <span class="badge">Media</span><span class="badge">Cashback</span>
            </div>
            <div class="card">
                <strong>PitonShop</strong>
                <p class="about-text"><?= __t('partner_piton_shop') ?></p>
                <span class="badge">E-commerce</span><span class="badge">BNPL</span>
            </div>
        </div>
    </section>

    <!-- Certificates & Compliance -->
    <section class="section">
        <h2><?= __t('certs_title') ?></h2>
        <div class="grid">
            <div class="card">
                <p class="about-text"><?= __t('certs_blurb') ?></p>
                <ul>
                    <li><?= __t('cert_iso') ?></li>
                    <li><?= __t('cert_pci') ?></li>
                    <li><?= __t('cert_gdpr') ?></li>
                </ul>
                <!-- Note: add real badges or images if you have -->
                <div style="margin-top:10px">
                    <span class="badge">ISMS</span>
                    <span class="badge">PCI</span>
                    <span class="badge">Privacy</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Careers -->
    <section class="section">
        <h2><?= __t('careers_title') ?></h2>
        <div class="grid">
            <div class="card">
                <p class="about-text"><?= __t('careers_blurb') ?></p>
                <ul>
                    <li><?= __t('role_junior_php') ?></li>
                    <li><?= __t('role_frontend') ?></li>
                    <li><?= __t('role_secops') ?></li>
                </ul>
                <p class="about-text" style="margin-top:8px"><?= __t('careers_benefits') ?></p>
                <div class="cta-row" style="margin-top:10px">
                    <a href="mailto:hr@pitbank.local" class="btn btn-secondary"><?= __t('careers_cta') ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="section">
        <h2><?= __t('stats_title') ?></h2>
        <div class="stats">
            <div class="stat">
                <div class="num">575 000+</div>
                <div class="label"><?= __t('stats_customers') ?></div>
            </div>
            <div class="stat">
                <div class="num">99.95%</div>
                <div class="label"><?= __t('stats_uptime') ?></div>
            </div>
            <div class="stat">
                <div class="num">12</div>
                <div class="label"><?= __t('stats_branches') ?></div>
            </div>
        </div>
    </section>

</main>

<?php require_once "footer.php"; ?>
</body>
</html>
