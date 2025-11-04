<?php require_once __DIR__.'/i18n.php'; ?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __t('site_title') ?></title>
    <link rel="icon" type="image/x-icon" href="img/PitBankIco.ico">
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
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Roboto', system-ui, -apple-system, Segoe UI, Arial, sans-serif; }
        body { display: flex; flex-direction: column; min-height: 100vh; background: var(--bg); color: var(--text); }

        .main-content { flex: 1; max-width: 1200px; width: 100%; margin: 30px auto; padding: 0 20px; }
        .welcome-container { text-align: center; padding: 40px 20px; max-width: 1200px; margin: 0 auto; }
        .welcome-title { font-size: 2.5rem; color: var(--secondary); margin-bottom: 40px; font-weight: 600; letter-spacing: -0.5px; }
        .username { color: var(--primary); font-weight: 800; }

        .dashboard { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-top: 30px; }
        .card { background: var(--card); border-radius: 14px; padding: 24px; box-shadow: 0 6px 20px rgba(0,0,0,0.06); transition: transform .25s ease, box-shadow .25s ease; min-height: 200px; border: 1px solid var(--border); }
        .card:hover { transform: translateY(-4px); box-shadow: 0 10px 28px rgba(0,0,0,0.08); }
        .card h3 { color: var(--secondary); margin-bottom: 16px; font-size: 1.2rem; border-bottom: 1px dashed var(--border); padding-bottom: 10px; }

        .amount { font-size: 2.2rem; font-weight: 800; color: var(--primary); margin: 18px 0; }
        .card-footer { margin-top: 16px; text-align: right; }
        .card-link { text-decoration: none; color: var(--primary); font-weight: 700; }

        .action-buttons { display: flex; flex-direction: column; gap: 12px; margin-top: 10px; }
        .action-btn {
            padding: 14px; border: 1px solid var(--border); background: transparent; color: var(--text);
            border-radius: 10px; font-weight: 700; cursor: pointer; transition: all .2s ease;
        }
        .action-btn:hover { border-color: var(--primary); color: var(--primary); }
        .transfer { }
        .pay { }

        .transactions-list { list-style: none; margin-top: 10px; }
        .transactions-list li { padding: 12px 0; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; font-size: .95rem; }
        .debit { color: #d9534f; }
        .credit { color: #3cba54; }

        .offer-item { background: linear-gradient(135deg, rgba(139,0,0,.08), rgba(0,0,0,0)); padding: 14px; border-radius: 10px; margin-top: 10px; border: 1px dashed var(--border); }
        .offer-item h4 { color: var(--secondary); margin-bottom: 6px; }
        .offer-item p { color: var(--muted); font-size: .95rem; margin-bottom: 10px; }
        .offer-btn { background: var(--primary); color: white; border: none; padding: 10px 18px; border-radius: 8px; cursor: pointer; font-weight: 700; }

        .rates-container { margin-top: 10px; }
        .rate-item { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border); }
        .rate-item span:first-child { font-weight: 600; color: var(--secondary); }

        @media (max-width: 768px) {
            .welcome-title { font-size: 2rem; }
            .dashboard { grid-template-columns: 1fr; }
            .amount { font-size: 1.8rem; }
        }
    </style>
</head>
<body>

<?php
require_once "header.php";
require_once "db_connection.php";

// Error output if DB fails
if ($mysql->connect_error) {
    echo "<p style='color: var(--primary)'>Err {$mysql->connect_errno}: ".htmlspecialchars($mysql->connect_error)."</p>";
}
?>

<main class="main-content">
    <div class="welcome-container">
        <h1 class="welcome-title">
            <?= __t('welcome') ?>, <span class="username">
            <?php
            // Resolve username from cookies safely
            $username = 'Guest';
            if (!empty($_COOKIE['user_login'])) { $username = $_COOKIE['user_login']; }
            elseif (!empty($_COOKIE['whoisthis'])) { $username = $_COOKIE['whoisthis']; }
            echo htmlspecialchars($username);
            ?>
            </span>!
        </h1>

        <div class="dashboard">
            <!-- Balance Card -->
            <div class="card balance-card">
                <h3><?= __t('account_balance') ?></h3>
                <p class="amount">
                    <?php
                    // Fetch money using prepared statement to avoid SQL injection risk
                    $amount = '0';
                    if ($stmt = $mysql->prepare("SELECT money FROM `users` WHERE `username` = ? LIMIT 1")) {
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $stmt->bind_result($money);
                        if ($stmt->fetch()) { $amount = (string)$money; }
                        $stmt->close();
                    }
                    echo htmlspecialchars($amount);
                    ?> ῥ
                </p>
                <div class="card-footer">
                    <a href="accounts.php" class="card-link"><?= __t('view_details') ?></a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card quick-actions">
                <h3><?= __t('quick_actions') ?></h3>
                <div class="action-buttons">
                    <button class="action-btn transfer"><?= __t('transfer_money') ?></button>
                    <button class="action-btn pay"><?= __t('pay_bills') ?></button>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card recent-transactions">
                <h3><?= __t('recent_transactions') ?></h3>
                <ul class="transactions-list">
                    <li><?= __t('tx_transfer') ?><span class="debit">0 ῥ</span></li>
                    <li><?= __t('tx_salary') ?><span class="credit">0 ῥ</span></li>
                    <li><?= __t('tx_payment') ?> <span class="debit">0 ῥ</span></li>
                </ul>
            </div>

            <!-- Special Offers -->
            <div class="card special-offers">
                <h3><?= __t('special_offers') ?></h3>
                <div class="offer-item">
                    <h4><?= __t('premium_credit_card') ?></h4>
                    <p><?= __t('cashback_line') ?></p>
                    <button class="offer-btn"><?= __t('learn_more') ?></button>
                </div>
            </div>

            <!-- Exchange Rates -->
            <div class="card exchange-rates">
                <h3><?= __t('exchange_rates') ?></h3>
                <div class="rates-container">
                    <div class="rate-item">
                        <span><?= __t('usd') ?>:</span>
                        <span>0.50 ῥ</span>
                    </div>
                    <div class="rate-item">
                        <span><?= __t('eur') ?>:</span>
                        <span>0.46 ῥ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
require_once "footer.php";
$mysql->close();
?>
</body>
</html>
