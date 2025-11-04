<?php
// i18n + session + DB
session_start();
require_once __DIR__ . "/i18n.php";
require_once __DIR__ . "/db_connection.php";
require_once __DIR__ . "/header.php";

// Resolve username
$username = 'Guest';
if (!empty($_COOKIE['user_login'])) { $username = $_COOKIE['user_login']; }
elseif (!empty($_COOKIE['whoisthis'])) { $username = $_COOKIE['whoisthis']; }

// Get user row safely
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

// Handle POST: send message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    // Trim and sanitize for storage; HTML will be escaped on render
    $message = trim($_POST['message']);
    if ($message !== '') {
        if ($stmt = $mysql->prepare("INSERT INTO chat (user_id, username, message) VALUES (?, ?, ?)")) {
            $stmt->bind_param("iss", $userinfo['id'], $userinfo['username'], $message);
            $stmt->execute();
            $stmt->close();
        }

        // Keep only last 150 messages
        $res = $mysql->query("SELECT COUNT(*) AS total FROM chat");
        $row = $res ? $res->fetch_assoc() : ['total'=>0];
        $total = (int)($row['total'] ?? 0);
        $limit = 150;
        if ($total > $limit) {
            // Delete oldest extra rows
            $toDelete = $total - $limit;
            // MySQL supports ORDER BY ... LIMIT in DELETE
            $mysql->query("DELETE FROM chat ORDER BY timestamp ASC LIMIT ".intval($toDelete));
        }

        // PRG pattern
        header("Location: chat.php");
        exit();
    }
}

// Fetch last 50 (ascending for chronological display)
$result = $mysql->query("
    SELECT chat.username, chat.message, chat.timestamp
    FROM chat
    ORDER BY chat.timestamp ASC
    LIMIT 50
");
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __t('chat_page_title') ?></title>
    <link rel="icon" type="image/x-icon" href="img/PitBankIco.ico">
    <style>
        /* Tokens */
        :root{
            --primary:#8B0000;--secondary:#2D2D2D;--accent:#C0C0C0;
            --bg:#F8F8F8;--card:#FFFFFF;--text:#2D2D2D;--muted:#666;--border:#EAEAEA;
        }
        html[data-theme="dark"]{
            --primary:#B33A3A;--secondary:#E6E6E6;--accent:#4A4A4A;
            --bg:#0F1115;--card:#151821;--text:#E6E6E6;--muted:#A0A0A0;--border:#222634;
        }

        /* Base */
        *{box-sizing:border-box}
        body{margin:0;display:flex;flex-direction:column;min-height:100vh;background:var(--bg);color:var(--text)}

        /* Layout */
        .main{flex:1;max-width:900px;margin:24px auto;padding:0 16px;width:100%}
        .chat-card{background:var(--card);border:1px solid var(--border);border-radius:16px;box-shadow:0 10px 28px rgba(0,0,0,.06);padding:18px}

        .chat-title{font-size:1.4rem;font-weight:900;color:var(--secondary);margin-bottom:12px}

        .chat-messages{height:480px;overflow-y:auto;border:1px solid var(--border);border-radius:12px;padding:12px;background:linear-gradient(180deg, rgba(0,0,0,0.02), transparent)}
        .msg{margin-bottom:10px;padding:10px;border-radius:12px;background:var(--card);border:1px solid var(--border);box-shadow:0 1px 3px rgba(0,0,0,.05)}
        .meta{display:flex;align-items:center;gap:8px;margin-bottom:6px}
        .user{font-weight:900;color:var(--primary)}
        .time{font-size:.8rem;color:var(--muted)}
        .text{white-space:pre-wrap;word-wrap:break-word}

        .input-row{display:flex;gap:10px;margin-top:12px;flex-wrap:wrap}
        .ta{flex:1;min-width:220px;height:80px;padding:12px;border:1px solid var(--border);border-radius:12px;background:transparent;color:var(--text);resize:none}
        .ta:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(139,0,0,.12)}
        .btn{height:42px;padding:0 18px;border-radius:12px;border:1px solid var(--border);font-weight:800;cursor:pointer}
        .btn-primary{background:var(--primary);color:#fff;border-color:transparent}
        .btn-ghost{background:transparent;color:var(--text)}
        .toolbar{display:flex;gap:10px;justify-content:flex-end;margin-top:8px;flex-wrap:wrap}

        @media (max-width: 560px){
            .chat-messages{height:380px}
            .btn,.btn-primary,.btn-ghost{width:100%}
            .toolbar{justify-content:stretch}
        }
    </style>
</head>
<body>

<main class="main">
    <div class="chat-card">
        <div class="chat-title"><?= __t('chat_title') ?></div>

        <div class="chat-messages" id="chat-messages" aria-live="polite">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="msg">
                        <div class="meta">
                            <span class="user"><?= htmlspecialchars($row['username']) ?></span>
                            <span class="time"><?= htmlspecialchars(date('H:i', strtotime($row['timestamp']))) ?></span>
                        </div>
                        <div class="text"><?= htmlspecialchars($row['message']) ?></div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="msg">
                    <div class="meta"><span class="user">System</span></div>
                    <div class="text">No messages yet.</div>
                </div>
            <?php endif; ?>
        </div>

        <form method="POST" class="input-row" autocomplete="off">
            <textarea name="message" class="ta" placeholder="<?= __t('chat_placeholder') ?>" required></textarea>
            <button type="submit" class="btn btn-primary"><?= __t('chat_send') ?></button>
        </form>

        <div class="toolbar">
            <form method="GET">
                <button type="submit" class="btn btn-ghost"><?= __t('chat_refresh') ?></button>
            </form>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . "/footer.php";
$mysql->close();
?>

<script>
// Auto-scroll to latest message on load
(function(){
    const box = document.getElementById('chat-messages');
    if (box) box.scrollTop = box.scrollHeight;
})();
</script>

</body>
</html>
