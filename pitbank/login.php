<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PitBank Login</title>
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #FAFAFA;
        }

        /* Общая шапка */
        .logo {
            height: 50px; /* Фиксированная высота для лого */
            width: auto; /* Ширина подстраивается */
            margin-right: 40px;
            display: flex;
            align-items: center;
        }

        .logo-img {
            height: 150%; /* Занимает всю высоту родителя */
            width: auto; /* Сохраняет пропорции */
            max-width: 125px; /* Максимальная ширина */
            object-fit: contain; /* Сохраняет пропорции */
        }

        .header {
            background: #dddddd;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 0 2rem;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            height: 70px;
            display: flex;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }

        /* Основной контент */
        .auth-main {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 40px 20px;
        }

        .auth-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            width: 100%;
        }

        .auth-form {
            background: #dddddd;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #EEE;
        }

        .auth-form h2 {
            color: var(--secondary);
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 500;
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
        }

        @media (max-width: 768px) {
            .auth-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .auth-form {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
<header class="header">
    <nav class="nav-container">
        <div class="logo"><img src="img/logo.png" alt="PitBank" class="logo-img">
        </div>
    </nav>
</header>

<main class="auth-main">
    <div class="auth-container">
        <!-- Форма входа -->
        <div class="auth-form">
            <h2>Sign In</h2>
            <form action="log_check.php" method="post">
                <div class="form-group">
                    <?php echo $_COOKIE["emailexists_err"] ?>
                    <label>Email</label>
                    <input name="login_email" value="<?php echo $_COOKIE["login_email"] ?>" type="email" class="form-control" placeholder="Your email" required>
                </div>
                <div class="form-group">
                    <?php echo $_COOKIE["passwrong_err"] ?>
                    <label>Password</label>
                    <input name="login_pass" type="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>
        </div>

        <!-- Форма регистрации -->
        <div class="auth-form">
            <h2>Registration</h2>
            <form action="reg_check.php" method="post">
                <div class="form-group">
                    <?php echo $_COOKIE["exists_err"] ?>
                    <label>Name</label>
                    <input type="text" name="user_login" value="<?php echo $_COOKIE['user_login']?>" placeholder="Example: Samir" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="user_email" value="<?php echo $_COOKIE['user_email']?>" placeholder="Example: samir@mail.pit" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="user_pass" class="form-control" required>
                    <?php echo $_COOKIE["passlength_err"] ?>
                </div>
                <div class="form-group">
                    <label>Repeat Password</label>
                    <input type="password" name="user_pass_check" class="form-control" required>
                    <?php echo $_COOKIE["passcheck_err"] ?>
                </div>
                <button type="submit" class="btn btn-primary">Create Account</button>
            </form>
        </div>
    </div>
</main>

<?php
require_once "footer.php";

//removing register errors
setcookie("passcheck_err", "", time() - 86400, "/");
unset($_COOKIE['passcheck_err']);
setcookie("passlength_err", "", time() - 86400, "/");
unset($_COOKIE['passlength_err']);
setcookie("exists_err", "", time() - 86400, "/");
unset($_COOKIE['exists_err']);

//removing login errors
setcookie("emailexists_err", "", time() - 86400, "/");
unset($_COOKIE['emailexists_err']);
setcookie("passwrong_err", "", time() - 86400, "/");
unset($_COOKIE['passwrong_err']);

?>
</body>
</html>