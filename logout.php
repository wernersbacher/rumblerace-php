<?php

require_once('_mysql.php');
session_start();

delete_session($_SESSION["user_id"]);
setcookie("rememberme", "", time() - 3600);
// remove guestw
setcookie("guestpw", "", time() - 3600);

session_destroy();



header("Location: index.php");
