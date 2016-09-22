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

// UPGRADES ENDE