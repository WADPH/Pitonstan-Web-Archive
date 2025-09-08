<?php
//Warning
session_start();

require_once "db_connection.php";

//If auth = true
$username = "Guest";
if (!empty($_COOKIE['user_login'])) {
    $username = $_COOKIE['user_login'];
} elseif (!empty($_COOKIE['whoisthis'])) {
    $username = $_COOKIE['whoisthis'];
}

$userinfo_arr = $mysql->query("SELECT * FROM users WHERE `username` = '$username'");
$userinfo = $userinfo_arr->fetch_assoc();


// If it will shows user select page or chat page
if (empty($_GET['with'])) {
    // Setting in $users all users without himself
    $users = $mysql->query("
        SELECT id, username 
        FROM users 
        WHERE id != ".$userinfo['id']."
        ORDER BY username
    ");

    // HTML
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PitBank - DM</title>
        <link rel="icon" type="image/x-icon" href="img/PitBankIco.ico">
        <style>
            body {
                margin: 0;
                font-family: Arial, sans-serif;
                background: #f5f5f5;
            }
            .container {
                max-width: 800px;
                margin: 30px auto;
                padding: 20px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            .user-list {
                margin-top: 20px;
            }
            .user-link {
                display: block;
                padding: 12px 15px;
                margin: 8px 0;
                background: #f9f9f9;
                border-radius: 6px;
                text-decoration: none;
                color: #333;
                transition: all 0.2s;
                border-left: 4px solid #8B0000;
            }
            .user-link:hover {
                background: #e9e9e9;
                border-left: 4px solid #6A0000;
            }
            h2 {
                color: #8B0000;
                margin-top: 0;
            }
        </style>
    </head>
    <body> ';
    require_once "header.php";
    echo '

        <div class="container">
            <h2>Choose User for private chat</h2>
            <div class="user-list">';

    // Output of all users from $users
    while($user = $users->fetch_assoc()) {
        echo '<a href="private_chat.php?with='.$user['id'].'" class="user-link">';
        echo htmlspecialchars($user['username']);
        echo '</a>';
    }

    // HTML end
    echo '</div></div></body></html>';
    exit();
}

// Getting info about opponent
$receiver_id = (int)$_GET['with'];
$receiver = $mysql->query("SELECT username FROM users WHERE id = $receiver_id")->fetch_assoc();

// Обработка отправки сообщения
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($message)) {
        $stmt = $mysql->prepare("
            INSERT INTO private_messages 
            (sender_id, receiver_id, message) 
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iis", $userinfo['id'], $receiver_id, $message);
        $stmt->execute();

        // After successfull sending
        $messageLimit = 250;

        // How much msg it has
        $countQuery = "SELECT COUNT(*) as total FROM private_messages";
        $countResult = $mysql->query($countQuery);
        $rowlimit = $countResult->fetch_assoc();
        $messageCount = $rowlimit['total'];

        // if more than limit = delete
        if ($messageCount > $messageLimit) {
            $deleteQuery = "DELETE FROM private_messages ORDER BY timestamp ASC LIMIT 1";
            $mysql->query($deleteQuery);
        }

        // Redirecting
        header("Location: private_chat.php?with=".$receiver_id);
        exit();
    }
}

// Getting Chat
$messages = $mysql->query("
SELECT 
    private_messages.*,
    users.username as sender_name
FROM private_messages
JOIN users ON private_messages.sender_id = users.id
WHERE 
    (private_messages.sender_id = {$userinfo['id']} 
    AND private_messages.receiver_id = $receiver_id) 
    OR
    (private_messages.sender_id = $receiver_id 
    AND private_messages.receiver_id = {$userinfo['id']})
ORDER BY private_messages.timestamp ASC
LIMIT 250
");
?>

    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PitBank - Chat with <?= htmlspecialchars($receiver['username']) ?></title>
        <link rel="icon" type="image/x-icon" href="img/PitBankIco.ico">
        <style>
            body {
                margin: 0;
                font-family: Arial, sans-serif;
                background: #f5f5f5;
            }

            .chat-container {
                max-width: 800px;
                margin: 30px auto;
                padding: 20px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            .chat-messages {
                height: 300px;
                overflow-y: auto;
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 20px;
                background: #f9f9f9;
            }
            .message {
                margin: 8px 0;
                padding: 10px 15px;
                border-radius: 18px;
                max-width: 70%;
                word-wrap: break-word;
            }
            .my-message {
                background-color: #e3f2fd;
                margin-left: auto;
                text-align: right;
            }
            .their-message {
                background-color: #f1f1f1;
                margin-right: auto;
            }
            .chat-input {
                display: flex;
                gap: 10px;
                margin-bottom: 15px;
            }
            .chat-input textarea {
                flex: 1;
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 8px;
                resize: none;
                height: 80px;
            }
            .chat-input button {
                background: #8B0000;
                color: white;
                border: none;
                border-radius: 8px;
                padding: 0 25px;
                cursor: pointer;
                height: 40px;
                transition: background 0.3s;
            }
            .chat-input button:hover {
                background: #6A0000;
            }
            .back-link {
                display: inline-block;
                margin-bottom: 15px;
                color: #8B0000;
                text-decoration: none;
                font-weight: bold;
            }
            .back-link:hover {
                text-decoration: underline;
            }
            small {
                color: #777;
                font-size: 0.8em;
                margin-left: 10px;
            }
        </style>
    </head>
    <body>

<?php require_once "header.php"?>

    <div class="chat-container">
        <a href="private_chat.php" class="back-link">← Back to user list</a>
        <h2>Chat with <?= htmlspecialchars($receiver['username']) ?></h2>

        <div class="chat-messages" id="chat-messages">
            <?php while($msg = $messages->fetch_assoc()): ?>
                <div class="message <?= $msg['sender_id'] == $userinfo['id'] ? 'my-message' : 'their-message' ?>">
                    <strong><?= $msg['sender_id'] == $userinfo['id'] ? "" : htmlspecialchars($msg['sender_name']) . ":";?></strong>
                    <span><?= htmlspecialchars($msg['message']) ?></span>
                    <small><?= date('H:i', strtotime($msg['timestamp'])) ?></small>
                </div>
            <?php endwhile; ?>
        </div>

        <style>

            .refresh-form button {
                background: var(--primary);
                color: white;
                border: none;
                border-radius: 8px;
                padding: 0 25px;
                cursor: pointer;
                height: 40px;
                transition: background 0.3s;
            }

            .refresh-form button:hover {
                background: #6A0000;
            }

            .refresh-form {
                text-align: right;
            }
        </style>

        <form method="POST" class="chat-input">
            <textarea name="message" placeholder="Your message..." required></textarea>
            <button type="submit">Send</button>
        </form>

        <!-- Update Button -->
        <form method="GET" class="refresh-form">
            <button type="submit">Refresh Messages</button>
        </form>
    </div>

    <script>
        // Auto-scroll
        window.onload = function() {
            var chatMessages = document.getElementById('chat-messages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        };
    </script>

    <? require_once "footer.php"?>
    </body>
    </html>

<?php
$mysql->close();
?>