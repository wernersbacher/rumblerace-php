<?php

session_start();

include("_mysql.php");
include("_function.php");
//
//$tree = getUpgradeTree();
//var_dump($tree);
//$tree2 = uasort($tree, function($a, $b) {
//    $res = strcmp($a['chain'], $b['chain']);
//
//    // If the rates are the same...
//    if ($res === 0) {
//        // Then compare by id
//        $res = $a['pre_id'] > $b['pre_id'] ? 1 : -1;
//    }
//
//    return $res;
//});
//var_dump(orderUpgrades($tree));

include("_tutorial.php");

$tutorial->setState("TUT_STATE_BUYCAR");