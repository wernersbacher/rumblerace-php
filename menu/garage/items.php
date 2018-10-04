<?php

$output = outputTut("items_info", $l);

//Wird aufgerufen, falls ein Item aktiviert wird
//var_Dump(userItemCount($post["item_id"]),$post["item_id"]);
if (isset($get["do"]) && $get["do"] == "act" && isset($post["item_id"]) && userItemCount($post["item_id"]) > 0) {

    $activate = activateItem($post["item_id"]);
    $output .= "<span class='dealInfoText $activate'>";
    $output .= put($activate, $l);
    $output .= "</span>";
}

$userItems = getUserItems();
$htmlItems = array();
$htmlTooltips = "";

//Generieren der HTML Outputs der Übersicht

foreach ($userItems as $userItem) {
    $id = $userItem["item_id"];
    $count = $userItem["count"];
    $item = $_rewards[$id];

    if ($count < 1)
        continue;

    $sellable = $item->sellable;
    $art = $item->art; // car, money, exp, part, other
    $rar = $item->rar;
    $liga = $item->liga;

    //Falls bisher kein Item dieser Art gefunden wurde, wird diese Art für den Output vorbereitet
    if (!array_key_exists($art, $htmlItems))
        $htmlItems[$art] = "";

    //Das Item, welches hinzugefügt wird
    $addItem = "<div class='item $rar tooltips' id='$id' data-tooltip-content='#tip_$id'>
                    <img src='img/items/$id.png' class='itemImg' /> 
                    <div class='itemBar'><span class='itemLiga'>Cl. 1</span> <span class='itemCount'>$count</span></div>
                </div>";


    $callable = is_callable($item->activate);

    //Das Tooltip des Item, welches hinzugefügt wird
    $htmlTooltips .= "<div class='tipDiv' data-id='$id' id='tip_$id'>
                        <div class='tipHead'>" . put($id . "_title", $l) . "</div>
                        <div class='itemDesc'>\"" . put($id . "_desc", $l) . "\"</div>
                        
                        <div class='itemInfo'>
                            <table>
                                <tr><td>" . put("itSellable", $l) . "</td><td>" . outputBool($sellable) . "</td></tr>
                                <tr><td>" . put("itArt", $l) . "</td><td>" . put("art_" . $art, $l) . "</td></tr>
                                <tr><td>" . put("itRar", $l) . "</td><td>" . outputRar($rar) . "</td></tr>
                                <tr><td>" . put("itLiga", $l) . "</td><td>$liga</td></tr>
                                <tr><td>" . put("itCount", $l) . "</td><td>$count</td></tr>
                            </table>
                            
                            <div class='itemButtons'><form method='post' action='?page=$page&sub=$sub&do=act'>
                                <input type='hidden' name='item_id' value='$id' />
                                <input class='tableTopButton' type='submit' value='" . put("activate", $l) . "' />
                            </form></div>
                        </div>

                    </div>";

    //Das neue Item richtig zuordnen
    $htmlItems[$art] .= $addItem;
}


//Ausgabe der Items

foreach ($htmlItems as $key => $out) {
    $output .= "<table class='tableRed tableClick'>
                <tr>
                  <th colspan='1'>" . put($art . "_items", $l) . "</th>
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

if(empty($htmlItems)) {
    $output .= put("no_items", $l);
}