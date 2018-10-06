<?php

$output = outputTut("st_info", $l);

$output .= "<div id='storage'>";

if ($mode == "sell" && isset($post['sell'])) { //Teil verkaufen
    $str_id = $post["storage_id"];
    $part = $post["part"];
    $value = $post["value"];
    $liga = $post["liga"];

    $output .= "<div class='textCenter'>";
    $output .= "<b>$part ($value) ($liga)</b><br/>";
    $output .= put("market_sell", $l) . ":";

    $output .= "<form method='POST' action='?page=$page&sub=storage'>
                        <input type='hidden' name='storage_id' value='" . $str_id . "'>
                        <input type='number' min='0.01' step='0.01' name='price' placeholder='100' class='tableTopInput'>
                        <input class='sellButton tableTopButton' name='confirmed' type='submit' value='verkaufen'>
                    </form>";

    $output .= "<br/>" . put("market_with", $l);
    $output .= "</div>";
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
                    $acc = $item["acc"];
                    $speed = $item["speed"];
                    $hand = $item["hand"];
                    $dura = $item["dura"];

                    $rows .= "<tr>
                <td class='partTitle'>" . put($item["part"], $l) . "</td>
                <td class='partPerf' title='Accel | Speed | Handling | Dura'>
                <!--" . outputDetails($acc, $speed, $hand, $dura, true) . "-->
                    <div class='stat_image_wrapper_tuner'><img src='img/stats/acc1.png' alt='Acc'/></div> <span class='tune_acc'>$acc</span><br/>
                    <div class='stat_image_wrapper_tuner'><img src='img/stats/speed1.png' alt='speed'/></div> <span class='tune_speed'>$speed</span><br/> 
                    <div class='stat_image_wrapper_tuner'><img src='img/stats/handling1.png' alt='hand'/></div> <span class='tune_speed'>$hand</span><br/>
                    <div class='stat_image_wrapper_tuner'><img src='img/stats/strength1.png' alt='str'/></div> <span class='tune_speed'>$dura</span> 
                 

</td>
                <td>" . put("liga", $l) . " " . $item["liga"] . "</td>
                <td>
                    <form method='POST' action='?page=$page&sub=storage&mode=sell'>
                        <input type='hidden' name='storage_id' value='" . $item["id"] . "'>
                        <input type='hidden' name='part' value='" . put($item["part"], $l) . "'>
                        <input type='hidden' name='value' value='A:$acc S:$speed H:$hand D:$dura'>
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
