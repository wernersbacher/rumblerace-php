<?php

$output = outputTut("coming_soon", $l);

//Upgradepoints auslesen
$used = getAllUpgradePoints();
$free = getPlayerUpPoints();
$cost = getUpgradePointCost();
$bought = 0; //Fals eine gekauft wird, muss dies auch an die funktion zur Berechnungn der Point KOsten mitgeteilt werden

if (isset($post["action"]) && $post["action"] == "buyUpgradePoint") {
    //buy an upgrade point for the price
    
    if (getPlayerMoney() >= $cost) {
        $status = buyUpgradePoint($cost);
        $free++; //Da gekauft wurde, auch die höhere Zahl anzeigen
        $bought=1;
    } else
        $status = "no_money";


    $output .= "<span class='dealInfoText $status'>";
    $output .= put($status, $l);
    $output .= "</span>";
}



//Ausgabe der Punkteübersicht

$total = $used + $free;

$output .= "<div class='settings'>
    ".put("used_points",$l)." <b>$used</b><br/>
    ".put("unused_points",$l)." <b>$free</b>
        
        <div class='buttonTopRight'>
            ".put("buy_another_point",$l)." <b>" . dollar(getUpgradePointCost($bought)) . "</b>
            <form method='POST' style='display:inline-block;' action='?page=special&sub=upgrades'>
                <input type='hidden' name='action' value='buyUpgradePoint'>
                <input class='tableTopButton' name='send' type='submit' value='" . put("buy", $l) . "'>
            </form>
        </div>
    </div>
    ";

//Ausgabe der Skills


