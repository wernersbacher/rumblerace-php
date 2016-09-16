<?php

require_once('_mysql.php');
require_once('gen.php');
require_once('_function.php');

function isLoggedIn() {
    $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
    if ($cookie) {
        list ($user_id, $token, $mac) = explode(':', $cookie);
        if (!hash_equals(hash_hmac('sha256', $user_id . ':' . $token, SECRET_KEY), $mac)) {
            return false;
        }
        $usertoken = getTokenByUserID($user_id);
        if (hash_equals($usertoken, $token)) {
            return $user_id;
        } else
            return false;
    }
}

session_start();

$site = basename($_SERVER["SCRIPT_FILENAME"], '.php');

if (isset($_SESSION['user_id'])) {
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
    
    
    

