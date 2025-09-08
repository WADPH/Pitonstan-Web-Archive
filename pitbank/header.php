<header class="header">
    <nav class="nav-container">
        <div class="logo">
            <a href="index.php">
                <img src="img/logo.png" alt="PitBank" class="logo-img">
            </a>
        </div>

        <input type="checkbox" id="menu-toggle" class="menu-toggle">
        <label for="menu-toggle" class="menu-button">☰</label>

        <div class="nav-menu">
            <a href="accounts.php" class="nav-link">My Account</a>
            <a href="about.php" class="nav-link">About</a>

            <div class="dropdown">
                <button class="nav-link dropdown-toggle">More ▼</button>
                <div class="dropdown-content">
                    <a href="chat.php" class="nav-link">Live Chat</a>
                    <a href="private_chat.php" class="nav-link">Private Chat</a>
                    <a href="signout.php" class="nav-link">Sign Out</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<style>
    :root {
        --primary: #8B0000;
        --secondary: #2D2D2D;
        --accent: #C0C0C0;
    }

    .header {
        background: #dddddd;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        padding: 0 2rem;
        position: relative;
    }

    .nav-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        height: 70px;
    }

    .logo {
        height: 50px;
        width: auto;
        margin-right: 40px;
        display: flex;
        align-items: center;
    }

    .logo-img {
        height: 100%;
        width: auto;
        max-width: 125px;
        object-fit: contain;
    }

    /* Стили для гамбургер-меню */
    .menu-toggle {
        display: none;
    }

    .menu-button {
        display: none;
        font-size: 24px;
        cursor: pointer;
        padding: 10px;
        margin-left: auto;
    }

    .nav-menu {
        display: flex;
        gap: 20px;
        margin-left: auto;
        align-items: center;
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

    /* Стили для выпадающего меню (десктоп) */
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-toggle {
        background: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
        font-size: 15px;
        color: var(--secondary);
        padding: 10px 20px;
        border-radius: 6px;
    }

    .dropdown-toggle:hover {
        background: rgba(139,0,0,0.05);
        color: var(--primary);
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: white;
        min-width: 160px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        z-index: 1;
        border-radius: 6px;
        overflow: hidden;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown-content .nav-link {
        width: 100%;
        box-sizing: border-box;
        border-radius: 0;
        display: block;
        padding: 12px 20px;
        border-bottom: 1px solid #f0f0f0;
    }

    /* Мобильные стили */
    @media (max-width: 768px) {
        .logo {
            height: 40px;
            margin-right: 20px;
        }

        .logo-img {
            max-width: 100px;
        }

        .menu-button {
            display: block;
        }

        .nav-menu {
            display: none;
            position: absolute;
            top: 70px;
            left: 0;
            right: 0;
            background: white;
            flex-direction: column;
            gap: 0;
            padding: 10px 0;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }

        .menu-toggle:checked ~ .nav-menu {
            display: flex;
        }

        .nav-link, .dropdown-toggle {
            padding: 12px 20px;
            border-bottom: 1px solid #eee;
            border-radius: 0;
            width: 100%;
            text-align: left;
        }

        .nav-link:hover, .dropdown-toggle:hover {
            background: rgba(139,0,0,0.1);
        }

        .dropdown {
            width: 100%;
        }

        .dropdown-content {
            position: static;
            box-shadow: none;
            display: none;
            width: 100%;
        }

        .dropdown:hover .dropdown-content {
            display: none;
        }

        .menu-toggle:checked ~ .nav-menu .dropdown-content {
            display: block;
        }
    }
</style>

<?php
//Checking if user authorized, if not — redirecting to the login page
if (empty($_COOKIE['user_login'])
    && empty($_COOKIE['whoisthis'])) {
   // echo '<script>window.location.href = "login.php";</script>';
    header("location: login.php");
    exit;
};
?>
