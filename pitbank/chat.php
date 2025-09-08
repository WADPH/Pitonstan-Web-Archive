<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PitBank Chat</title>
    <link rel="icon" type="image/x-icon" href="img/PitBankIco.ico">

</head>
<body>
<?php
//Warning
session_start();

require_once "db_connection.php";
require_once "header.php";

$username = "Guest";
if (!empty($_COOKIE['user_login'])) {
    $username = $_COOKIE['user_login'];
} elseif (!empty($_COOKIE['whoisthis'])) {
    $username = $_COOKIE['whoisthis'];
}

$userinfo_arr = $mysql->query("SELECT * FROM users WHERE `username` = '$username'");
$userinfo = $userinfo_arr->fetch_assoc();



// Sending msg
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = htmlspecialchars(trim($_POST['message']));
    if(!empty($message)) {

        $stmt = $mysql->prepare("INSERT INTO chat (user_id, username, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $userinfo['id'], $userinfo['username'], $message);
        $stmt->execute();

        // After successfull sending
        $messageLimit = 150;

        // How much msg it has
        $countQuery = "SELECT COUNT(*) as total FROM chat";
        $countResult = $mysql->query($countQuery);
        $rowlimit = $countResult->fetch_assoc();
        $messageCount = $rowlimit['total'];

        // if more than limit = delete
        if ($messageCount > $messageLimit) {
            $deleteQuery = "DELETE FROM chat ORDER BY timestamp ASC LIMIT 1";
            $mysql->query($deleteQuery);
        }

        // Redirecting for avoide double qanqren
        header("Location: chat.php");
        exit();
    }
}

// Getting last 50 msg
$result = $mysql->query("
    SELECT 
        chat.*, 
        users.username 
    FROM chat
    INNER JOIN users 
        ON chat.user_id = users.id
    ORDER BY chat.timestamp ASC
    LIMIT 50
");

?>

    <div class="chat-container">
        <h2>PitBank Live Chat</h2>

        <div class="chat-messages" id="chat-messages">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="message">
                    <strong><?= htmlspecialchars($row['username']) ?>:</strong>
                    <span><?= htmlspecialchars($row['message']) ?></span>
                    <small><?= date('H:i', strtotime($row['timestamp'])) ?></small>
                </div>
            <?php endwhile; ?>
        </div>
<!--Auto scroll-->
        <script>
            window.onload = function() {
                var chatMessages = document.getElementById('chat-messages');
                chatMessages.scrollTop = chatMessages.scrollHeight;
            };
        </script>

        <form method="POST" class="chat-input">
            <textarea name="message" placeholder="Type your message..." required></textarea>
            <button type="submit">Send</button>
        </form>

        <!-- Update Button -->
        <form method="GET" class="refresh-form">
            <button type="submit">Refresh Messages</button>
        </form>
    </div>

    <style>

        body {
            margin: 0; /* Убирает стандартные отступы браузера */
        }

        .chat-container {
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 20px;
        }

        .chat-messages {
            height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background: #f9f9f9;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .message strong {
            color: var(--primary);
        }

        .message small {
            color: #777;
            font-size: 0.8em;
            margin-left: 10px;
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

        .chat-input button, .refresh-form button {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0 25px;
            cursor: pointer;
            height: 40px;
            transition: background 0.3s;
        }

        .chat-input button:hover, .refresh-form button:hover {
            background: #6A0000;
        }

        .refresh-form {
            text-align: right;
        }
    </style>

<?php require_once "footer.php";
$mysql->close();
?>
</body>
</html>
