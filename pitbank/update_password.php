<?php
//Removing cookies error while login
setcookie("passcheck_err", "", time() - 86400, "/");
setcookie("passwrong_err", "", time() - 86400, "/");
setcookie("success", "", time() - 86400, "/");

//Vars from post
$current_pass = $_POST["current_pass"];
$new_pass = $_POST["new_pass"];
$repeat_pass = $_POST["repeat_pass"];

//Converting pass to the hashed version
$current_pass_hash = md5($current_pass);
$new_pass_hash = md5($new_pass);

//Who is logged
$username = "Guest"; // default value

if (!empty($_COOKIE['user_login'])) {
    $username = $_COOKIE['user_login'];
} elseif (!empty($_COOKIE['whoisthis'])) {
    $username = $_COOKIE['whoisthis'];
}

//Connecting to the DB
require_once "db_connection.php";
//$mysql = new mysqli("localhost", "root", "root", "pitbank");
//$mysql->query("SET NAMES utf8");

//Getting info about user from username (cookies)
$userinfo_arr = $mysql->query("SELECT * FROM `users` WHERE `username` = '$username'");
$userinfo = $userinfo_arr->fetch_assoc();

if ($current_pass_hash == $userinfo["pass"]) {
    if ($new_pass == $repeat_pass && strlen($new_pass) >= 8) {
//        Update Pass
        $mysql->query("UPDATE `users` SET `pass` = '$new_pass_hash' WHERE `username` = '$username'");
        $success = "<p style='color: #28ff02'>Password Changed!</p>";
        setcookie("success", "$success", time() + 86400, "/");

        header('location:passchange.php');
        $mysql->close();
        exit();
    }else {
        $passcheck_err = "<p style='color: #8B0000'>Wrong repeat password or less than 8 characters!</p>";
        setcookie("passcheck_err", $passcheck_err, time() + 86400, "/");
        header("Location: passchange.php");
        $mysql->close();
        exit();
    }

}else {
    $passwrong_err = "<p style='color: #8B0000'>Wrong password for user</p>";
    setcookie('passwrong_err', $passwrong_err, time() + 86400);
    header("Location: passchange.php");
    $mysql->close();
    exit();

}


$mysql->close();
?>

