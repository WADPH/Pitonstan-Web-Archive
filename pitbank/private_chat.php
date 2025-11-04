<?php
// Bootstrap: session, i18n, DB
session_start();
require_once __DIR__."/i18n.php";
require_once __DIR__."/db_connection.php";

// Resolve current user from cookies
$username = 'Guest';
if (!empty($_COOKIE['user_login'])) { $username = $_COOKIE['user_login']; }
elseif (!empty($_COOKIE['whoisthis'])) { $username = $_COOKIE['whoisthis']; }

// Load current user row safely
$userinfo = ['id'=>0,'username'=>$username];
if ($stmt = $mysql->prepare("SELECT id, username FROM users WHERE username = ? LIMIT 1")) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($uid, $uname);
    if ($stmt->fetch()) {
        $userinfo['id'] = (int)$uid;
        $userinfo['username'] = $uname;
    }
    $stmt->close();
}

// If no "with" param: render user picker
if (empty($_GET['with'])) {
    // Get all users except self
    $users = [];
    if ($stmt = $mysql->prepare("SELECT id, username FROM users WHERE id <> ? ORDER BY username")) {
        $stmt->bind_param("i", $userinfo['id']);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) { $users[] = $row; }
        $stmt->close();
    }
    ?>
    <!DOCTYPE html>
    <html lang="<?= htmlspecialchars($lang) ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= __t('dm_page_title') ?></title>
        <link rel="icon" type="image/x-icon" href="img/PitBankIco.ico">
        <style>
            :root{--primary:#8B0000;--secondary:#2D2D2D;--accent:#C0C0C0;--bg:#F8F8F8;--card:#FFFFFF;--text:#2D2D2D;--muted:#666;--border:#EAEAEA}
            html[data-theme="dark"]{--primary:#B33A3A;--secondary:#E6E6E6;--accent:#4A4A4A;--bg:#0F1115;--card:#151821;--text:#E6E6E6;--muted:#A0A0A0;--border:#222634}
            *{box-sizing:border-box}
            body{margin:0;min-height:100vh;background:var(--bg);color:var(--text);display:flex;flex-direction:column}
            .main{flex:1;max-width:900px;margin:24px auto;padding:0 16px;width:100%}
            .card{background:var(--card);border:1px solid var(--border);border-radius:16px;box-shadow:0 10px 28px rgba(0,0,0,.06);padding:18px}
            h2{margin:0 0 12px 0;color:var(--secondary);font-size:1.3rem}
            .list{display:grid;grid-template-columns:1fr;gap:10px;margin-top:10px}
            .link{display:block;padding:12px 14px;border-radius:12px;border:1px solid var(--border);text-decoration:none;color:var(--text);background:var(--card)}
            .link:hover{border-color:var(--primary)}
        </style>
    </head>
    <body>
    <?php require_once __DIR__."/header.php"; ?>
    <main class="main">
        <div class="card">
            <h2><?= __t('dm_choose_user') ?></h2>
            <div class="list">
                <?php foreach ($users as $u): ?>
                    <a class="link" href="private_chat.php?with=<?= (int)$u['id'] ?>"><?= htmlspecialchars($u['username']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <?php require_once __DIR__."/footer.php"; ?>
    </body>
    </html>
    <?php
    $mysql->close();
    exit;
}

// Chat with a specific user
$receiver_id = (int)$_GET['with'];

// Load opponent name safely
$receiver = ['username'=>'—'];
if ($stmt = $mysql->prepare("SELECT username FROM users WHERE id = ? LIMIT 1")) {
    $stmt->bind_param("i", $receiver_id);
    $stmt->execute();
    $stmt->bind_result($rname);
    if ($stmt->fetch()) { $receiver['username'] = $rname; }
    $stmt->close();
}

// Handle send
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    // Store raw text; escape on render
    $message = trim($_POST['message']);
    if ($message !== '') {
        if ($stmt = $mysql->prepare("INSERT INTO private_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)")) {
            $stmt->bind_param("iis", $userinfo['id'], $receiver_id, $message);
            $stmt->execute();
            $stmt->close();
        }
        // Trim to last 250 messages
        $res = $mysql->query("SELECT COUNT(*) AS total FROM private_messages");
        $row = $res ? $res->fetch_assoc() : ['total'=>0];
        $total = (int)($row['total'] ?? 0);
        $limit = 250;
        if ($total > $limit) {
            $mysql->query("DELETE FROM private_messages ORDER BY timestamp ASC LIMIT ".intval($total - $limit));
        }
        header("Location: private_chat.php?with=".$receiver_id);
        exit;
    }
}

// Load thread
$messages = [];
if ($stmt = $mysql->prepare("
    SELECT pm.sender_id, pm.message, pm.timestamp, u.username AS sender_name
    FROM private_messages pm
    JOIN users u ON pm.sender_id = u.id
    WHERE (pm.sender_id = ? AND pm.receiver_id = ?)
       OR (pm.sender_id = ? AND pm.receiver_id = ?)
    ORDER BY pm.timestamp ASC
    LIMIT 250
")) {
    $stmt->bind_param("iiii", $userinfo['id'], $receiver_id, $receiver_id, $userinfo['id']);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) { $messages[] = $row; }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __t('dm_page_title') ?></title>
    <link rel="icon" type="image/x-icon" href="img/PitBankIco.ico">
    <style>
        :root{--primary:#8B0000;--secondary:#2D2D2D;--accent:#C0C0C0;--bg:#F8F8F8;--card:#FFFFFF;--text:#2D2D2D;--muted:#666;--border:#EAEAEA}
        html[data-theme="dark"]{--primary:#B33A3A;--secondary:#E6E6E6;--accent:#4A4A4A;--bg:#0F1115;--card:#151821;--text:#E6E6E6;--muted:#A0A0A0;--border:#222634}
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;background:var(--bg);color:var(--text);display:flex;flex-direction:column}
        .main{flex:1;max-width:900px;margin:24px auto;padding:0 16px;width:100%}
        .card{background:var(--card);border:1px solid var(--border);border-radius:16px;box-shadow:0 10px 28px rgba(0,0,0,.06);padding:18px}
        .toolbar{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px;flex-wrap:wrap}
        .back{display:inline-block;text-decoration:none;color:var(--primary);font-weight:800}
        .title{font-weight:900;color:var(--secondary)}
        .box{height:380px;overflow-y:auto;border:1px solid var(--border);border-radius:12px;padding:12px;background:linear-gradient(180deg, rgba(0,0,0,0.02), transparent)}
        .msg{margin:8px 0;padding:10px 14px;border-radius:18px;max-width:75%;word-wrap:break-word;border:1px solid var(--border);background:var(--card)}
        .mine{margin-left:auto;background:#e3f2fd}
        .theirs{margin-right:auto;background:#f1f1f1}
        html[data-theme="dark"] .mine{background:#1f2a44}
        html[data-theme="dark"] .theirs{background:#1b1f2a}
        .meta{font-size:.8rem;color:var(--muted);margin-top:4px;text-align:right}
        .row{display:flex;gap:10px;margin-top:10px;flex-wrap:wrap}
        .ta{flex:1;min-width:220px;height:80px;padding:12px;border:1px solid var(--border);border-radius:12px;background:transparent;color:var(--text);resize:none}
        .ta:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(139,0,0,.12)}
        .btn{height:42px;padding:0 18px;border-radius:12px;border:1px solid var(--border);font-weight:800;cursor:pointer}
        .btn-primary{background:var(--primary);color:#fff;border-color:transparent}
        .btn-ghost{background:transparent;color:var(--text)}
        @media (max-width:560px){.btn,.btn-primary,.btn-ghost{width:100%}}
    </style>
</head>
<body>

<?php require_once __DIR__."/header.php"; ?>

<main class="main">
    <div class="card">
        <div class="toolbar">
            <a class="back" href="private_chat.php">← <?= __t('dm_back_to_list') ?></a>
            <div class="title"><?= __t('dm_with') ?> <?= htmlspecialchars($receiver['username']) ?></div>
            <form method="GET">
                <input type="hidden" name="with" value="<?= (int)$receiver_id ?>">
                <button type="submit" class="btn btn-ghost"><?= __t('dm_refresh') ?></button>
            </form>
        </div>

        <div class="box" id="chatBox" aria-live="polite">
            <?php foreach ($messages as $m): ?>
                <div class="msg <?= ($m['sender_id'] == $userinfo['id']) ? 'mine':'theirs' ?>">
                    <?php if ($m['sender_id'] != $userinfo['id']): ?>
                        <div style="font-weight:900;color:var(--primary)"><?= htmlspecialchars($m['sender_name']) ?></div>
                    <?php endif; ?>
                    <div><?= htmlspecialchars($m['message']) ?></div>
                    <div class="meta"><?= htmlspecialchars(date('H:i', strtotime($m['timestamp']))) ?></div>
                </div>
            <?php endforeach; ?>
        </div>

        <form method="POST" class="row" autocomplete="off">
            <textarea class="ta" name="message" placeholder="<?= __t('dm_placeholder') ?>" required></textarea>
            <button type="submit" class="btn btn-primary"><?= __t('dm_send') ?></button>
        </form>
    </div>
</main>

<?php require_once __DIR__."/footer.php"; ?>
<script>
// Auto-scroll to bottom on load
(function(){const box=document.getElementById('chatBox');if(box){box.scrollTop=box.scrollHeight;}})();
</script>
</body>
</html>
<?php $mysql->close(); ?>
