<?php

function getUpgradePointCost($add = 0) {
    return 1000 * pow(1.2, getAllUpgradePoints() + $add);
}

function getNodeByName($tree, $name) {

    foreach ($tree as $node) {
        if ($node["name"] == $name)
            return $node;
    }
    return false;
}

function getNodeById($tree, $id) { 

    foreach ($tree as $node) {
        if ($node["this_id"] == $id)
            return $node;
    }
    return false;
}

function carSellPrice($c) {
    return $c * 0.22;
}

//UPGRADES

function upgrade($base_name) { //calculate the effect for the upgrades
    global $upgrades;
    //Filter for basename
    $filtered = array();
    foreach ($upgrades as $key => $value) {
        if (strpos($key, $base_name) === 0) {
            $filtered[$key] = $value;
        }
    }
    $effect_sum = 0;
    foreach ($filtered as $up) {
        //hier muss quasi alles mit $base_name_ gecheckt werden, bzw der effect gerechnet werden
        $effect_sum += $up["ups"]*$up["effect"];
    }
    return $effect_sum;
}

function getMaxCars() {
    $max = 2;
    $max += upgrade("garage_space");
    return $max;
}

function getTuningDur($base) {
    $factor = upgrade("mechanics");
    return $base * (1 - ($factor / 100));
}

// UPGRADES ENDE