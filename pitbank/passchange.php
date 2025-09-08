<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PitBank Change Pass</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .change-pass-form {
            background: #dddddd;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #EEE;
            width: 100%;
            max-width: 500px;
        }

        .change-pass-form h2 {
            color: var(--secondary);
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 500;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #666;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #DDD;
            border-radius: 6px;
            font-size: 15px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(139,0,0,0.1);
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 10px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #6A0000;
        }

        .footer {
            background: var(--secondary);
            color: white;
            text-align: center;
            padding: 25px;
            margin-top: auto;
        }
    </style>
</head>
<body>
<?php require_once "header.php"; ?>

<main class="main-content">
    <div class="change-pass-form">
        <h2>Change Password</h2>
        <form action="update_password.php" method="post">
            <div class="form-group">
                <label>Current Password</label>
                <?php echo $_COOKIE["passwrong_err"] ?>
                <input type="password" name="current_pass" class="form-control" required>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_pass" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Repeat New Password</label>
                <?php echo $_COOKIE["passcheck_err"] ?>
                <input type="password" name="repeat_pass" class="form-control" required>
                <?php echo $_COOKIE["success"] ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>
</main>

<?php require_once "footer.php";

//removing login errors/succes
setcookie("success", "", time() - 86400, "/");
unset($_COOKIE['success']);
setcookie("passcheck_err", "", time() - 86400, "/");
unset($_COOKIE['passcheck_err']);
setcookie("passwrong_err", "", time() - 86400, "/");
unset($_COOKIE['passwrong_err']);

?>
</body>
</html>