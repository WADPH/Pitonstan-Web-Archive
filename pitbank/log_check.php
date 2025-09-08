<?php
//Removing cookies error while login
setcookie("emailexists_err", "", time() - 86400, "/");
setcookie("passwrong_err", "", time() - 86400, "/");

//Getting login values from POST
$login_email = $_POST['login_email'];
$login_pass = $_POST['login_pass'];

//Cookies for email value (save email in form)
setcookie("login_email", $login_email, time() + 86400, "/");


//Hashed pass
$hashpass = md5($login_pass);

require_once "db_connection.php";
//$mysql = new mysqli("localhost", "root", "root", "pitbank");
//$mysql->query("SET NAMES utf8");

$email_exists = $mysql->query("SELECT `email` FROM users WHERE `email` = '$login_email'");
$pass_exists = $mysql->query("SELECT `pass` FROM users WHERE `email` = '$login_email'");
$passtest = $pass_exists->fetch_assoc();

if ($email_exists->num_rows == 0) {
    $emailexists_err = "<p style='color: #8B0000'>User with this email doesnt exist!</p>";
    setcookie('emailexists_err', $emailexists_err, time() + 86400);
    header("Location: login.php");
    exit();
}

else if ($hashpass != $passtest['pass']) {
    $passwrong_err = "<p style='color: #8B0000'>Wrong password for user</p>";
    setcookie('passwrong_err', $passwrong_err, time() + 86400);
    header("Location: login.php");
    exit();
}
else{
    //Getting name of Who is logged and sending to index.php via cookies
    $whoisthis_arr = $mysql->query("SELECT `username` FROM users WHERE `email` = '$login_email'");
    $whoisthis = $whoisthis_arr->fetch_assoc()['username'];


    setcookie("whoisthis", "$whoisthis", time() + 86400, "/");
    header("Location: index.php");

}






//Closing DB
$mysql->close();