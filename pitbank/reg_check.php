<?php
//removing errors
setcookie("passcheck_err", "", time() - 86400, "/");
setcookie("passlength_err", "", time() - 86400, "/");
setcookie("exists_err", "", time() - 86400, "/");

//getting register values from POST
$login = $_POST['user_login'];
$email = $_POST['user_email'];
$pass = $_POST['user_pass'];
$pass_check = $_POST['user_pass_check'];

// cookies for saving login and email values
setcookie("user_login", $login, time() + 86400);
setcookie("user_email", $email, time() + 86400);



//Cheking for reg rules
if (strlen($pass) < 8) {
$passlength_err = "<p style='color: #8B0000'>Need password with 8 or more characters!</p>";
setcookie("passlength_err", $passlength_err, time() + 86400, "/");
header("Location: login.php");
exit();
}

elseif ($pass != $pass_check) {
    $passcheck_err = "<p style='color: #8B0000'>Wrong password!</p>";
    setcookie("passcheck_err", $passcheck_err, time() + 86400, "/");
    header("Location: login.php");
    exit();
}


else{

    //Connection to the DB and setting UTF8
    require_once "db_connection.php";
//    $mysql = new mysqli("localhost", "root", "root", "pitbank");
//    $mysql->query("SET NAMES utf8");

    //Vars for checking: Username or email exists or not
    $login_exists = $mysql->query("SELECT `username` FROM users WHERE username = '$login'");
    $email_exists = $mysql->query("SELECT `email` FROM users WHERE email = '$email'");

    //Vars for pass and `date`
    $hashpass = md5($pass);
    $current_date = date('Y-m-d');

    //If is there some error
    if ($mysql->connect_error) {
        echo "<p style='color: #8B0000'>" . "Err" . $mysql->connect_errno . ": " . $mysql->connect_error . "<p>" . "<br>";
    }
    elseif ($login_exists->num_rows == 1 || $email_exists->num_rows == 1) {
        $exists_err = "<p style='color: #8B0000'>User with this name or email already exists!</p>";
        setcookie("exists_err", $exists_err, time() + 86400, "/");
        header("Location: login.php");
        exit();
    }
    else{
        // Inserting values into table

        $mysql->query(
            "INSERT INTO `users` (`username`, `pass`, `email`, `date` )
        VALUES ('$login', '$hashpass', '$email', '$current_date')"
        );
    }


    //Closing DB
    $mysql->close();

}





header("Location: index.php");
exit();


