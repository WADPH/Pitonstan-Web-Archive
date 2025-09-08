<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PitBank Main</title>
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



        .main-content {
            flex: 1;
            max-width: 1200px;
            width: 100%;
            margin: 30px auto;
            padding: 0 20px;
        }


    </style>
</head>
<body>

<?php
//adding header
//ob_start();
require_once "header.php";
//Connection to the DB and setting UTF8
require_once "db_connection.php";
//$mysql = new mysqli("localhost", "root", "root", "pitbank");
//$mysql->query("SET NAMES utf8");

//If is there some error
if ($mysql->connect_error) {
    echo "<p style='color: #8B0000'>" . "Err" . $mysql->connect_errno . ": " . $mysql->connect_error . "<p>" . "<br>";
}


?>

<main class="main-content">
    <div class="welcome-container">
        <h1 class="welcome-title">Welcome, <span class="username">
                <?php
                //Who is logged, Welcome + Logged User Name
                $username = "Guest"; // default value

                  if (!empty($_COOKIE['user_login'])) {
                      $username = $_COOKIE['user_login'];
                  }
                  elseif (!empty($_COOKIE['whoisthis'])) {
                      $username = $_COOKIE['whoisthis'];
                  }

                  echo htmlspecialchars($username);
                  ?>
            </span>!
        </h1>

        <div class="dashboard">
            <!-- Balance Card -->
            <div class="card balance-card">
                <h3>Account Balance</h3>
                <p class="amount"><?php
                    //Getting user info and getting moeny value from the DB
                    $userinfo_arr = $mysql->query("SELECT * FROM `users` WHERE `username` = '$username'");
                    $userinfo = $userinfo_arr->fetch_assoc();

                    //Entering value to the website
                    echo $userinfo['money'];
                    ?> ῥ</p>
                <div class="card-footer">
                    <a href="accounts.php" style="text-decoration: none;  color: darkred"" class="card-link">View Details</a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card quick-actions">
                <h3>Quick Actions</h3>
                <div class="action-buttons">
                    <button class="action-btn transfer">Transfer Money</button>
                    <button class="action-btn pay">Pay Bills</button>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="card recent-transactions">
                <h3>Recent Transactions</h3>
                <ul class="transactions-list">
                    <li>Transfer<span class="debit">0 ῥ</span></li>
                    <li>Salary<span class="credit">0 ῥ</span></li>
                    <li>Payment <span class="debit">0 ῥ</span></li>
                </ul>
            </div>

            <!-- Special Offers -->
            <div class="card special-offers">
                <h3>Special Offers</h3>
                <div class="offer-item">
                    <h4>Premium Credit Card</h4>
                    <p>5% Cashback on all purchases</p>
                    <button class="offer-btn">Learn More</button>
                </div>
            </div>

            <!-- Exchange Rates -->
            <div class="card exchange-rates">
                <h3>Exchange Rates</h3>
                <div class="rates-container">
                    <div class="rate-item">
                        <span>USD:</span>
                        <span>0.50 ῥ</span>
                    </div>
                    <div class="rate-item">
                        <span>EUR:</span>
                        <span>0,46 ῥ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* Main Content Styles */
    .welcome-container {
        text-align: center;
        padding: 40px 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .welcome-title {
        font-size: 2.5rem;
        color: var(--secondary);
        margin-bottom: 40px;
        font-weight: 500;
        letter-spacing: -0.5px;
    }

    .username {
        color: var(--primary);
        font-weight: 700;
    }

    /* Dashboard Grid */
    .dashboard {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-top: 30px;
    }

    /* Card Styles */
    .card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
        min-height: 200px;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card h3 {
        color: var(--secondary);
        margin-bottom: 20px;
        font-size: 1.3rem;
        border-bottom: 2px solid var(--accent);
        padding-bottom: 10px;
    }

    /* Balance Card */
    .amount {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--primary);
        margin: 25px 0;
    }

    /* Quick Actions */
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 15px;
    }

    .action-btn {
        padding: 14px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .transfer {
        background: #e8f4fc;
        color: #1a73e8;
    }
    .pay {
        background: #f6f9e6;
        color: #689f38;
    }
    .deposit {
        background: #fff3e0;
        color: #fb8c00;
    }

    /* Transactions List */
    .transactions-list {
        list-style: none;
        margin-top: 15px;
    }

    .transactions-list li {
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
        display: flex;
        justify-content: space-between;
        font-size: 0.95rem;
    }

    .debit { color: #d32f2f; }
    .credit { color: #388e3c; }

    /* Special Offers */
    .offer-item {
        background: #fff8e1;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
    }

    .offer-item h4 {
        color: var(--secondary);
        margin-bottom: 8px;
    }

    .offer-item p {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 12px;
    }

    .offer-btn {
        background: var(--primary);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 5px;
        cursor: pointer;
    }

    /* Exchange Rates */
    .rates-container {
        margin-top: 15px;
    }

    .rate-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }

    .rate-item span:first-child {
        font-weight: 500;
        color: var(--secondary);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .welcome-title {
            font-size: 2rem;
        }

        .dashboard {
            grid-template-columns: 1fr;
        }

        .amount {
            font-size: 1.8rem;
        }
    }
</style>

<?php
require_once "footer.php";
//Closing DB
$mysql->close();
?>
</body>
</html>
