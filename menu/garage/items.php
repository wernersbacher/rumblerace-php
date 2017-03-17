<?php

$output = outputTut("items_info", $l);


$userItems = getUserItems();
$htmlItems = array();

foreach ($userItems as $userItem) {
    $id = $userItem["item_id"];
    $item = $_rewards[$id];

    $sellable = $item->sellable;
    $art = $item->art; // car, money, exp, part, other
    $rar = $item->rar;
    $liga = $item->liga;

    //Falls bisher kein Item dieser Art gefunden wurde, wird diese Art für den Output vorbereitet
    if (!array_key_exists($art, $htmlItems))
        $htmlItems[$art] = "";

    //Das Item, welches hinzugefügt wird
    $addItem = "<div class='item'>$id</div>";

    //Das neue Item richtig zuordnen
    $htmlItems[$art] .= $addItem;
}



//Das hier auch noch dynamisch ausgeben!! Alle Typen die man hat in ein Array pushen, das dann durchgehen!

foreach ($htmlItems as $key => $out) {
    $output .= "<table class='tableRed tableClick'>
                <tr>
                  <th colspan='1'>" . put($art."_items", $l) . "</th>
                </tr>
                <tr>
                    <td class='right-border-grey' colspan='1'>
                        <div class='itemFlex'>
                        $out
                        </div>   
                    </td>
                </tr>
            </table>
    ";
}


