<?php
//removing all cookies
foreach ($_COOKIE as $name => $value) {
    unset($_COOKIE[$name]);
    setcookie($name, '', time() - 86400, '/');
}

header("Location: login.php");