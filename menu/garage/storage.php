<?php

$output = outputTut("st_info", $l);

$output .= "<div id='storage'>";

if ($mode == "sell" && isset($post['sell'])) { //Teil verkaufen
    $str_id = $post["storage_id"];
    $str_data = queryMarketPartData($str_id);

    if ($str_data) {
        $part = $str_data["part"];
        $liga = $str_data["liga"];

        $acc = $str_data["acc"];
        $speed = $str_data["speed"];
        $hand = $str_data["hand"];
        $dura = $str_data["dura"];


        $output .= "<div class='textCenter'>";
        $output .= "<b>$part (" . outputDetails($acc, $speed, $hand, $dura) . ") ($liga)</b><br/>";
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
} else {

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
                  <th colspan='5'>" . put($kat, $l) . "</th>
                </tr>";
        if ($storage)
            foreach ($storage as $item) {
                if ($item["kat"] == $kat && $item["garage_id"] == 0) {
                    $partVals = [];
                    $part_sum = 0;
                    $part_max = 0;
                    $htmlAttributes = "";
                    //Loop through all attributes and gen sum
                    foreach ($_config["parts"]["valueArr"] as $attribut) {
                        $part_sum += $partVals["curr"][$attribut] = $item[$attribut]; //part value currently
                        $part_max += $partVals["max"][$attribut] = $item["m_" . $attribut]; //max value and sum up
                        $partVals["min"][$attribut] = $_config["calc"]["partLowest"] * $partVals["max"][$attribut]; //min value
                        if ($partVals["max"][$attribut] > 0) {
                            $rarity = ($partVals["curr"][$attribut] - $partVals["min"][$attribut]) / ($partVals["max"][$attribut] - $partVals["min"][$attribut]);
                            $color = colorRarity(100 * $rarity)["color"];
                        } else
                            $color = "#696969";

                        $htmlAttributes .= "<div class='stat_image_wrapper_tuner'><img src='img/stats/" . $attribut . "1.png' alt='$attribut'/></div> 
                            <span title='min: " . $partVals["min"][$attribut] . ", max: " . $partVals["max"][$attribut] . "' style='color:$color; background-color: " . $color . "45' class='part_val tune_$attribut'>" . $partVals["curr"][$attribut] . "</span><br/>";
                    }
                    $part_min = $part_max / 2;
                    $sum_rarity = 100 * ($part_sum - $part_min) / ($part_max - $part_min);
                    $sum_color = colorRarity($sum_rarity);
                    

                    $rows .= "<tr>
                <td class='partTitle'>
                <div class='partTitleFormat'>" . put($item["part"], $l) . "</div>
                    <span class='tune_rarity' style='color:".$sum_color["color"]."'>".put($sum_color["name"], $l)."</span>
                    </td>
                <td class='partPerf'>
                    $htmlAttributes
                 
                <td>" . put("liga", $l) . " " . $item["liga"] . "</td>
                <td>
                    <form method='POST' action='?page=$page&sub=storage&mode=sell'>
                        <input type='hidden' name='storage_id' value='" . $item["id"] . "'>
                        <input type='hidden' name='part' value='" . put($item["part"], $l) . "'>
                        <input type='hidden' name='liga' value='" . put("liga", $l) . " " . $item["liga"] . "'>
                        <input class='sellButton tableTopButton' name='sell' type='submit' value='" . put("sell_it", $l) . "'>
                    </form>
                </td>
                <td>
                    <form method='POST' data-dialog='Do you want to delete this part?' action='?page=tuner&sub=storage&mode=trash'>
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
}

$output .= "</div>";
