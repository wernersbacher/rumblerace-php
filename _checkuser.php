<?php

require_once('_mysql.php');
require_once('gen.php');
require_once('_function.php');



session_start();

$site = basename($_SERVER["SCRIPT_FILENAME"], '.php');

if (isset($_SESSION['user_id'])) {
    setOnline();
    if($site == "login")
        header("Location:main.php");
} else {
    $tryLogin = isLoggedIn();
    if ($tryLogin && !isset($_GET["stop"])) {
        //Quick login
        $user = queryPlayerByID($tryLogin);
        login($tryLogin, $user["username"], $user["lang"]);
    } else {
        if($site !== "login")
        header("Location:login.php");
    }
}
    
    
    

