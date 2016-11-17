<?php

function getUpgradePointCost($add = 0) {
    return 1000*pow(1.2,getAllUpgradePoints()+getPlayerUpPoints()+$add);
}

//UPGRADES

function carSellPrice($c) {
    return $c*0.22;
}

function getMaxCars() {
    global $upgrades;
    return 2+$upgrades["garage_space"];
}

function getNodeByName($tree, $name) {
    
    foreach ($tree as $node) {
        if($node["name"] == $name)
            return $node;
    }
    return false;
}

function getNodeById($tree, $id) {
    
    foreach ($tree as $node) {
        if($node["this_id"] == $id)
            return $node;
    }
    return false;
}

// UPGRADES ENDE