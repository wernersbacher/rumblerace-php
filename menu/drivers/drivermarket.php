<?php

$output = outputTut("driverm_info", $l);

$path = "?page=$page&sub=$sub";

$output .= "<div id='market'>";

if ($mode == "buy" && isset($get["id"])) {
    $drv_id = $get["id"];
    $driver_data = getDriverByID($drv_id);

    $output .= "<div class='textCenter'>";
    if ($driver_data) {
        $name = $driver_data["name"];
        $user_id = $driver_data["user_id"];
        $skill = showSkill($driver_data["skill"]);
        $liga = $driver_data["liga"];
        $anteil = $driver_data["anteil"];
        $country = $driver_data["country"];
        $nameChanged = $driver_data["nameChanged"];
        $sell_price = $driver_data["sell"];

        if ($sell_price > 0) {

            if ($user_id == $_SESSION["user_id"]) {
                //Löschen des eigenen Elements

                $output .= "take back?";

                $output .= "<b>(" . dollar($sell_price / 10) . ")</b><br/>";
            } else {
                //Kaufen des Elements

                $output .= "buy";

                $output .= "<br/><b>" . dollar($sell_price) . "</b><br/>";
            }

            $output .= "<form method='post' action='?page=$page&sub=$sub'>";
            $output .= "<input type='hidden' name='id' value='$drv_id'> ";
            $output .= "<input type='submit' class='tableTopButton redButton' name='canceled' value='" . put("no", $l) . "'> ";
            $output .= "<input type='submit' class='tableTopButton' name='confirmed' value='" . put("yes", $l) . "'>";
            $output .= "</form>";
        } else
            $output .= put("part_sold", $l);
    } else
        $output .= put("part_sold", $l);


    $output .= "</div>";
} else {


    function marketDriverBuy($drv_id) {

        $money = getPlayerMoney();
        $price = queryMarketDriverCost($drv_id);
        if ($money >= $price) {
            return queryMarketDriverBuy($drv_id);
        } else {
            return "no_money";
        }
    }

    if (isset($post['confirmed'])) { //Bestäigung anzeigen
        $drv_id = $post["id"];

        $buy = marketDriverBuy($drv_id);

        $output .= "<span class='dealInfoText $buy'>";
        $output .= put($buy, $l);
        $output .= "</span>";
    }


//Blätterseite abfragen
    $menge = ceil(queryMarketDriver(0, true));
    if (isset($get["s"]) AND $get["s"] <= $menge)
        $s = $get["s"];
    else
        $s = 1;

    $pages = getPages($menge, $s, $path);
    $output .= $pages;

    $output .= "<table style='font-size:13px;' class='tableRed table100 selling noclick'>
                <tr>
                  <th colspan='3'>" . put("market", $l) . "</th>
                </tr>";

//Aktuelle Seite auslesen
    $driverMarket = queryMarketDriver($s, false);
    if ($driverMarket)
        foreach ($driverMarket as $item) {

            $driver_id = $item["driver_id"];
            $name = $item["name"];
            $country = $item["country"];
            $skill = showSkill($item["skill"]);
            $liga = $item["liga"];
            $anteil = $item["anteil"];
            $nameChanged = $item["nameChanged"];

            $link = $path . "&mode=buy&id=" . $item["drv_id"];

            $output .= "<tr>";
            $output .= "
                <td class='partTitle'>
                    <a class='maxSize centerChildVertical2' href='$link'>
                        <div class='partTitleFormat'>" . getNameChanged($name, $nameChanged) . "  <div class='driverLiga'>
                            " . levelImg($liga) . "</div></div>
                    </a>
                    </td>
                <td class='partPerf'><a class='maxSize centerChildVertical2' href='$link'><div>" . getFlag($country) . " Skill: <b>$skill%</b></div></a></td>
                <td><a class='maxSize' href='$link'>" . dollar($item["sell"]) . "<br/>" . $item["username"] . "</a></td>
                
                <!--<td>
                    <a href='$link'><button class='tableTopButton'>" . put("buy", $l) . "</button></a>
                </td>-->
                    ";
            $output .= "</tr>";
        } else {
        $output .= "<tr><td colspan='4'>" . put("market_empty", $l) . "</td></tr>";
    }

    $output .= "</table>";
    $output .= $pages;



    $output .= "</div>";
}

$output .= "</div>";
