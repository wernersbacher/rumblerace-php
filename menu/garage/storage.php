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
    $output .= put("market_sell", $l).":";

    $output .= "<form method='POST' action='?page=garage&sub=storage'>
                        <input type='hidden' name='storage_id' value='" . $str_id . "'>
                        <input type='number' min='0.01' step='0.01' name='price' placeholder='100' class='tableTopInput'>
                        <input class='sellButton tableTopButton' name='confirmed' type='submit' value='verkaufen'>
                    </form>";

    $output .= "<br/>".put("market_with", $l);
    $output .= "</div>";
} else {

    if (isset($post['confirmed'])) {
        //Teil auf den Markt schmeißen

        $str_id = $post["storage_id"];
        $num = $post["price"];
        $num = preg_replace('~[^0-9|^.|(?=2.)]~', '', $num);
        if($num >= 0.1 && $num < 100000000000)
            $sell = queryPartSell($str_id, $num);
        else
            $sell = "sell_check_input";

        $output .= "<span class='dealInfoText $sell'>";
        $output .= put($sell, $l);
        $output .= "</span>";
    }


    $katNames = queryTuningKats();
    $storage = queryStorage();

    //Jede Teileklasse durchgehen
    foreach ($katNames as $kat) {
        $counter = 0;
        $rows = "";
        $output .= "<table id='$kat' class='tableRed tableClick'>
                <tr>
                  <th colspan='4'>" . put($kat, $l) . "</th>
                </tr>";
        if ($storage)
            foreach ($storage as $item) {
                if ($item["kat"] == $kat && $item["garage_id"] == 0) {

                    $rows .= "<tr>
                <td class='partTitle'>" . put($item["part"], $l) . "</td>
                <td class='partPerf'>" . $item["value"] . " " . put("unit_" . $kat, $l) . "</td>
                <td>" . put("liga", $l) . " " . $item["liga"] . "</td>
                <td>
                    <form method='POST' action='?page=garage&sub=storage&mode=sell'>
                        <input type='hidden' name='storage_id' value='" . $item["id"] . "'>
                        <input type='hidden' name='part' value='" . put($item["part"], $l) . "'>
                        <input type='hidden' name='value' value='" . $item["value"] . " " . put("unit_" . $kat, $l) . "'>
                        <input type='hidden' name='liga' value='" . put("liga", $l) . " " . $item["liga"] . "<'>
                        <input class='sellButton tableTopButton' name='sell' type='submit' value='verkaufen'>
                    </form>
                </td>
              </tr>";
                }
            }
        if ($rows == "") {
            $rows = "<tr>
                  <td colspan='4'>" . put("no_parts_storage", $l) . "</td>
                </tr>";
        }
        $output .= $rows;



        $output .= "</table>";
    }
}

$output .= "</div>";
