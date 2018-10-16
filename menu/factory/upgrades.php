<?php

$output = "";
$nodes = "";

//Upgradepoints auslesen
$used = getAllUpgradePoints();
$free = getPlayerUpPoints();
$cost = getUpgradePointCost();
$bought = 0; //Fals eine gekauft wird, muss dies auch an die funktion zur Berechnungn der Point KOsten mitgeteilt werden

$tree = getUpgradeTree(); // Erstes auslesen fürs kaufen

if (isset($post["action"]) && $post["action"] == "buyUpgradePoint") { //Kaufen eines Up Points
    //buy an upgrade point for the price
    if (getPlayerMoney() >= $cost) {
        $status = buyUpgradePoint($cost);
        $free++; //Da gekauft wurde, auch die höhere Zahl anzeigen
        $bought = 1;
    } else
        $status = "no_money";


    $output .= "<span class='dealInfoText $status'>";
    $output .= put($status, $l);
    $output .= "</span>";
} else if (isset($post["up"])) { //Upgrade eines Skills
    $up_name = $post["up"];
    $status = "upgrade_error";
    $node = getNodeByName($tree, $up_name);
    if (checkNodeBuyable($node)) {
        //kaufen
        $status = upgradeById($node["this_id"], $node["thisCost"]);

        header("Location: main.php?page=factory&sub=upgrades&status=" . $status);
        $free -= $node["thisCost"];
        $used += $node["thisCost"];
        $tree = getUpgradeTree();
    } else {
        $status = "upgrade_error";
    }

    $output .= "<span class='dealInfoText $status'>";
    $output .= put($status, $l);
    $output .= "</span>";
} else if (isset($get["status"])) { //Wenn weitergeleitet wurde. Nötig, um die Spritzahl direkt zu refreshen
    $status = $get["status"];
    $output .= "<span class='dealInfoText $status'>";
    $output .= put($status, $l);
    $output .= "</span>";
}

function checkNodeBuyable($node) {
    global $tree, $free;

    if ($node) {
        //upgrade!
        $preNode = getNodeById($tree, $node["pre_id"]);
        if (($preNode == false || $preNode["userUps"] >= $node["needed"]) && $node["thisCost"] <= $free && $node["thisMax"] > $node["userUps"])
            return true;
    }
    return false;
}

//Ausgabe der Punkteübersicht
$total = $used + $free;
$up_cost = getUpgradePointCost($bought);
if ($up_cost > getPlayerMoney())
    $dis = "disabled";
else
    $dis = "";

$output .= "<div class='settings upgrades_points'> <div>
    " . put("used_points", $l) . " <b>$used</b><br/>
    " . put("unused_points", $l) . " <b>$free</b>
    </div>
        <div>
            " . put("buy_another_point", $l) . " <b>" . dollar($up_cost) . "</b>
            <form method='POST' style='display:inline-block;' action='?page=factory&sub=upgrades'>
                <input type='hidden' name='action' value='buyUpgradePoint'>
                <input class='tableTopButton' name='send' type='submit' value='" . put("buy", $l) . "' $dis>
            </form>
        </div>
    </div>
    ";

//Ausgabe der Skills

/*
 * TODO
 * nodes nur anlickbar wenn auch kaufbar (funktion machen, und im post check auch nutzen)
 */

function generateNode($node) {
    global $tree, $l;
    $name = $node["name"];
    $userUps = $node["userUps"];
    $effect = $node["effect"];
    $cost = $node["thisCost"];
    $unit = $node["unit"];
    $max = $node["thisMax"];
    $chain = $node["chain"];
    $pre_id = intval($node["pre_id"]);
    $needed = intval($node["needed"]);

    //check if owned
    if ($userUps > 0)
        $userUps = "<div class='count'>$userUps/$max</div>";
    else
        $userUps = "";

    //check if unlocked
    if (array_key_exists($pre_id, $tree) AND intval($tree[$pre_id]["userUps"]) < $needed)
        return;

    //check if new line
    if ($pre_id < 1 && $chain != 10)
        $br = "</div><div>";
    else
        $br = "";

    //Generate hover with details
    $hover = "<div class='tooltip'><h2 class='tooltip_h2'>" . put($name . "-title", $l) . "</h2>
        " . put("it-costs", $l) . ": $cost <br/>
        " . put("effect", $l) . ": $effect" . put($unit, $l) . "<br/><br/>
        <span>\"" . put($name, $l) . "\"</span>
        </div>";

    //Ausgabe

    if (checkNodeBuyable($node)) {
        //anderer look, wenns kaufbar ist
        $out = "$br<div data-chain='$chain' class='node chain_$chain'>
            $userUps
            $hover
            <form method='post' action='?page=factory&sub=upgrades'>";
        $out .= "<input type='hidden' name='up' value='$name'>";
        $out .= "<input type='image' name='image' src='img/techtree/$name.png' width='40' height='40'>";
        $out .= "</form></div>";
    } else {
        //nicht kaufbar
        /*
          $out = "$br<div data-chain='$chain' class='node nodeDisabled chain_$chain' title='$name'>$userUps";
          $out .= "<span class='formlike'><img src='img/techtree/$name.png' width='40' height='40' /><span>";
          $out .= "</div>"; */


        $out = "$br<div data-chain='$chain' class='node nodeDisabled chain_$chain'>
          $userUps
          $hover
          <form disabled>";
        $out .= "<input style='cursor:default' type='image' name='image' src='img/techtree/$name.png' width='40' height='40' disabled>";
        $out .= "</form></div>";
    }


    return $out;
}

$tree = orderUpgrades($tree);

$output .= "<div id='nodes' class='settings'>";

foreach ($tree as $node) {

    $nodes .= generateNode($node);
}
$output .= "<div>"; //div for 1st chain
$output .= $nodes;
$output .= "</div>";

//var_dump(getNodeByName($tree, "garage_space2"));



$output .= "</div>";
