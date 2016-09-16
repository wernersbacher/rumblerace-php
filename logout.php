<?php
require_once('_mysql.php');
session_start();

delete_session($_SESSION["user_id"]);
unset($_COOKIE['rememberme']);

session_destroy();



header("Location: index.php");