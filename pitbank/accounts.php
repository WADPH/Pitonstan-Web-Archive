<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PitBank Profile</title>
    <link rel="icon" type="image/x-icon" href="img/PitBankIco.ico">

    <style>
        :root {
            --primary: #8B0000;
            --secondary: #2D2D2D;
            --accent: #C0C0C0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #F8F8F8;
        }

        .header {
            background: #dddddd;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 0 2rem;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            height: 70px;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            margin-right: 40px;
        }

        .nav-menu {
            display: flex;
            gap: 20px;
            margin-left: auto;
        }

        .nav-link {
            color: var(--secondary);
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            transition: all 0.2s ease;
            font-size: 15px;
        }

        .nav-link:hover {
            background: rgba(139,0,0,0.05);
            color: var(--primary);
        }

        .main-content {
            flex: 1;
            max-width: 1200px;
            width: 100%;
            margin: 30px auto;
            padding: 0 20px;
        }

        .profile-card {
            background: #dddddd;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #EEE;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 40px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--accent);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .profile-info {
            flex: 1;
        }

        .profile-name {
            color: var(--secondary);
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .detail-item {
            margin: 15px 0;
            font-size: 1.1rem;
        }

        .detail-label {
            color: var(--primary);
            font-weight: 500;
            display: inline-block;
            width: 200px;
        }

        .change-password-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 30px;
        }

        .change-password-btn:hover {
            background: #6A0000;
        }


    </style>
</head>
<body>
<?php
require_once "header.php";
require_once "db_connection.php";
//$mysql = new mysqli("localhost", "root", "root", "pitbank");
//$mysql->query("SET NAMES utf8");

$username = "Guest";
if (!empty($_COOKIE['user_login'])) {
    $username = $_COOKIE['user_login'];
} elseif (!empty($_COOKIE['whoisthis'])) {
    $username = $_COOKIE['whoisthis'];
}

$userinfo_arr = $mysql->query("SELECT * FROM users WHERE `username` = '$username'");
$userinfo = $userinfo_arr->fetch_assoc();

$mysql->close();
?>

<main class="main-content">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                <!-- Profile Pic -->
                <img src="img/profile_photo.jpg" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <h1 class="profile-name"><?php echo htmlspecialchars($userinfo['username']); ?></h1>
                <div class="detail-item">
                    <span class="detail-label">Registration Date:</span>
                    <?php echo htmlspecialchars($userinfo['date']); ?>
                </div>
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Full Name:</span>
            <?php echo htmlspecialchars($userinfo['username']); ?>
        </div>

        <div class="detail-item">
            <span class="detail-label">Email:</span>
            <?php echo htmlspecialchars($userinfo['email']); ?>
        </div>

        <div class="detail-item">
            <span class="detail-label">Current Balance:</span>
            <?= $userinfo['money'] ?> ῥ
        </div>

        <div class="detail-item">
            <span class="detail-label">Account ID:</span>
            PB-<?php echo str_pad($userinfo['id'], 8, '0', STR_PAD_LEFT); ?>
        </div>

        <a href="passchange.php">
            <button class="change-password-btn">Change Password</button>
        </a>
    </div>
</main>

<?php require_once "footer.php"; ?>
</body>
</html>