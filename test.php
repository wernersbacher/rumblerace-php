<?php 
session_start();

include("_mysql.php");
include("_function.php");

var_dump(carSellPrice(queryPlayerCarID("56")["preis"]));