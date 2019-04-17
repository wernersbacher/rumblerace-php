<?php

/* 
 * Some game variables
 */

$_config = array();

$_config["vars"] = array();

$_config["vars"]["startMaxSprit"] = 100;
$_config["vars"]["startSprit"] = 50;
$_config["vars"]["startMaxCars"] = 2;
$_config["vars"]["startMoney"] = 15000;

$_config["liga"]["ligaMulti"] = 2;
$_config["liga"]["ligaStart"] = 150;
$_config["liga"]["maxLiga"] = 20;

$_config["bonus"]["wait"] = 3600;

$_config["driver"]["maxSkill"] = 100; //% value
$_config["driver"]["upgradeBonus"] = 200;
$_config["driver"]["upgradeMulti"] = 0.7;
$_config["driver"]["driverCnt"] = 10;

$_config["racing"]["minGoodness"] = 0.1; //decimal procent
$_config["racing"]["carWeight"] = 0.75; //decimal procent, driver_factor = 1-car_weight


$_config["calc"]["rewardBaseExpo"] = 1.12;
$_config["calc"]["partLowest"] = 0.5;

//DB "Settings"
$_config["parts"]["valueArr"] = ["acc", "speed", "hand", "dura"];