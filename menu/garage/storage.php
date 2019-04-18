<?php

$output = outputTut("st_info", $l);

$tutorial->tickOff("TUT_STATE_STORAGE");

$output .= "<div id='storage'>";

if ($mode == "sell" && isset($post['sell'])) { //Teil verkaufen
    $str_id = $post["storage_id"];
    $str_data = queryMarketPartData($str_id);

    if ($str_data) {
        $part = $str_data["part"];
        $tier = $str_data["tier"];

        $acc = $str_data["acc"];
        $speed = $str_data["speed"];
        $hand = $str_data["hand"];
        $dura = $str_data["dura"];


        $output .= "<div class='textCenter textFancy'>";
        $output .= "<b>".put($part,$l)." (" . outputDetails($acc, $speed, $hand, $dura) . ") ($tier)</b><br/>";
        $output .= put("market_sell", $l) . ":";

        $output .= "<form method='POST' action='?page=$page&sub=storage'>
                        <input type='hidden' name='storage_id' value='" . $str_id . "'>
                        <input type='number' min='0.01' step='0.01' name='price' placeholder='100' class='tableTopInput'>
                        <input class='sellButton tableTopButton' name='confirmed' type='submit' value='verkaufen'>
                    </form>";

        $output .= "<br/>" . put("market_with", $l);
        $output .= "</div>";
    } else {
        $output .= put("part_sold", $l);
    }
}

if (isset($post['confirmed'])) {
    //Teil auf den Markt schmeißen

    $str_id = $post["storage_id"];
    $num = $post["price"];
    $num = preg_replace('~[^0-9|^.|(?=2.)]~', '', $num);
    if ($num >= 0.1 && $num < 100000000000)
        $sell = queryPartSell($str_id, $num);
    else
        $sell = "sell_check_input";

    $output .= "<span class='dealInfoText $sell'>";
    $output .= put($sell, $l);
    $output .= "</span>";
} else if ($mode == "trash" && isset($post['storage_id'])) {
    //remove item and get exp
    $item_id = $post['storage_id'];
    if (removeItem($item_id)) {
        $output .= "<span class='dealInfoText green'>";
        $output .= put("part_trashed", $l);
        $output .= "</span>";
    }
}


$katNames = queryTuningKats();
$storage = queryStorage();

//Jede Teileklasse durchgehen
foreach ($katNames as $kat) {
    $counter = 0;
    $rows = "";
    $output .= "<table id='$kat' class='tableRed tableClick'>
                <tr>
                  <th colspan='3'>" . put($kat, $l) . "</th>
                </tr>";
    if ($storage)
        foreach ($storage as $item) {
            if ($item["kat"] == $kat && $item["garage_id"] == 0) {


                $attr = outputItemAttributes($item);
                $html = $attr["html"];
                $part_rarity = $attr["part_rarity"];
                $new = $item["new"];


                $rows .= "<tr>
                <td class='partTitle'>
                <div class='partTitleFormat'>" . put($item["part"], $l) . "
                    
                        <span class='new_part_hint' " . boolToHide($new) . ">" . put("new", $l) . "</span>
                    </div>
                    
                    <span class='tune_rarity' style='color:" . $part_rarity["color"] . "'>
                        " . put($part_rarity["name"], $l) . "
                    </span>
                    <div>" . formatLevelColor($item["tier"]) . "</div>
                </td>
                <td class='partPerf'>
                    $html
                 
                <td>
                    <form class='inline-form' method='POST' action='?page=$page&sub=storage&mode=sell'>
                        <input type='hidden' name='storage_id' value='" . $item["id"] . "'>
                        <input type='hidden' name='part' value='" . put($item["part"], $l) . "'>
                        <input type='hidden' name='liga' value='" . put("liga", $l) . " " . $item["tier"] . "'>
                        <input class='sellButton tableTopButton' name='sell' type='submit' value='" . put("sell_it", $l) . "'>
                    </form>
                    <form class='inline-form' method='POST' data-dialog='Do you want to delete this part?' action='?page=tuner&sub=storage&mode=trash'>
                        <input type='hidden' name='storage_id' value='" . $item["id"] . "'>
                        <input class='sellButton tableTopButton redButton dialog' name='trash' type='submit' value='X'>
                    </form>
                </td>
              </tr>";
            }
        }
    if ($rows == "") {
        $rows = "<tr>
                  <td class='right-border-grey' colspan='4'>" . put("no_parts_storage", $l) . "</td>
                </tr>";
    }
    $output .= $rows;



    $output .= "</table>";
}


$output .= "</div>";


//mark new parts as "read"
markPartsAsRead();
