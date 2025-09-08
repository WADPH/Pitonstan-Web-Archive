<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PitBank About</title>
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

        .about-container {
            display: flex;
            gap: 40px;
            align-items: center;
        }

        .about-section {
            background: #dddddd;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #EEE;
            flex: 1;
        }

        .about-image {
            flex: 1;
            max-width: 400px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .about-image img {
            width: 100%;
            height: auto;
            display: block;
        }

        .about-title {
            color: var(--secondary);
            font-size: 2.5rem;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 10px;
        }

        .footer {
            background: var(--secondary);
            color: white;
            text-align: center;
            padding: 25px;
            margin-top: auto;
        }

        @media (max-width: 768px) {
            .about-container {
                flex-direction: column;
            }

            .about-image {
                max-width: 100%;
                order: 2;
                margin-top: 30px;
            }

            .about-section {
                order: 1;
            }
        }
    </style>
</head>

<body>
<?php require_once "header.php"; ?>

<main class="main-content">
    <div class="about-container">
        <div class="about-section">
            <h1 class="about-title">About our Bank</h1>
            <p>We are PitIndustrial Corp. which owns Pitonstan State Bank - PitBank <br><br><br>

                We offer our customers the most favourable terms nationwide. From microloans to loans of more than 50,000ῥ.<br><br>

                You can use the services of our bank ONLINE from the comfort of your own home. You only need an account, which is created in two clicks.<br><br>

                PitBank services: View your account, Deposit to your account, Transfer money and much more...</p>
        </div>

        <div class="about-image">
            <!-- Вставьте ваше изображение здесь -->
            <img src="img/logo2.png" alt="PitBank Headquarters">
        </div>
    </div>
</main>

<?php require_once "footer.php"; ?>
</body>
</html>