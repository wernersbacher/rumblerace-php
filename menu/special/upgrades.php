<?php

$output = outputTut("coming_soon", $l);

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
    $node = getNodeByName($tree, $up_name);
    if ($node) {
        //upgrade!
        $preNode = getNodeById($tree, $node["pre_id"]);
        if (($preNode == false || $preNode["userUps"] >= $node["needed"]) && $node["thisCost"] <= $free && $node["thisMax"] > $node["userUps"]) {
            //kaufen
            var_dump("kaufen jetzt");
            $status = upgradeByName($up_name, $node["thisCost"]);   
        } else {
            $status = "upgrade_error";
        }
    }
}


//Ausgabe der Punkteübersicht
$total = $used + $free;
$output .= "<div class='settings'>
    " . put("used_points", $l) . " <b>$used</b><br/>
    " . put("unused_points", $l) . " <b>$free</b>
        
        <div class='buttonTopRight'>
            " . put("buy_another_point", $l) . " <b>" . dollar(getUpgradePointCost($bought)) . "</b>
            <form method='POST' style='display:inline-block;' action='?page=special&sub=upgrades'>
                <input type='hidden' name='action' value='buyUpgradePoint'>
                <input class='tableTopButton' name='send' type='submit' value='" . put("buy", $l) . "'>
            </form>
        </div>
    </div>
    ";

//Ausgabe der Skills

/*
 * TODO
 * upgrade in der db durchführen (siehe oben)
 */

function generateNode($node) {
    global $tree;
    $name = $node["name"];
    $id = $node["this_id"];
    $userUps = $node["userUps"];
    $chain = $node["chain"];
    $chain = $node["chain"];
    $pre_id = intval($node["pre_id"]);
    $needed = intval($node["needed"]);

    //check if owned
    if ($userUps > 0)
        $userUps = "<div class='count'>$userUps</div>";
    else
        $userUps = "";

    //check if unlocked
    if (array_key_exists($pre_id, $tree) AND intval($tree[$pre_id]["userUps"]) < $needed)
        return;

    //check if new line
    if ($pre_id < 1 && $chain != 10)
        $br = "<br/>";
    else
        $br = "";

    $out = "$br<div data-chain='$chain' class='node chain_$chain' title='$name'>
            $userUps
            <form method='post' action='?page=special&sub=upgrades'>";
    $out .= "<input type='hidden' name='up' value='$name'>";
    $out .= "<input type='image' name='image' src='img/techtree/$name.png' width='40' height='40'>";
    $out .= "</form></div>";

    return $out;
}

$tree = orderUpgrades($tree);

$output .= "<div id='nodes' class='settings'>";

foreach ($tree as $node) {
    $output.= generateNode($node);
}

//var_dump(getNodeByName($tree, "garage_space2"));



$output .= "</div>";
