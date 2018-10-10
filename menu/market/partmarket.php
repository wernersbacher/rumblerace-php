<?php

//Checken ob user genug geld hat!

$output = outputTut("partm_info", $l);

$output .= "<div id='market'>";

if ($mode == "buy" && isset($get["id"])) {
    $str_id = $get["id"];
    $str_data = queryMarketPartData($str_id);

    $output .= "<div class='textCenter'>";
    if ($str_data) {
        $part = $str_data["part"];
        $liga = $str_data["liga"];
        $price = $str_data["sell"];
        $kat = $str_data["kat"];
        $user_id = $str_data["user_id"];

        $acc = $str_data["acc"];
        $speed = $str_data["speed"];
        $hand = $str_data["hand"];
        $dura = $str_data["dura"];
        if ($price > 0) {

            if ($user_id == $_SESSION["user_id"]) {
                //Löschen des eigenen Elements

                $output .= "<br/><b>" . put($part, $l) . " (" . outputDetails($acc, $speed, $hand, $dura) . ") (" . put("liga", $l) . " $liga)</b><br/>";
                $output .= put("part_back_ques", $l) . "<br/>";
                $output .= put("part_back_cost", $l) . ": ";
                $output .= "<b>(" . dollar($price / 10) . ")</b><br/>";
            } else {
                //Kaufen des Elements

                $output .= put("you_wish", $l);
                $output .= "<br/><b>" . put($part, $l) . "<br/>(" . outputAttributesList($acc, $speed, $hand, $dura) . ") <br/>(" . put("liga", $l) . " $liga)</b><br/>";
                $output .= put("for_cost", $l);
                $output .= "<br/><b>" . dollar($price) . "</b><br/>";
                $output .= put("to_buy", $l);
            }

            $output .= "<form method='post' action='?page=market&sub=partmarket'>";
            $output .= "<input type='hidden' name='id' value='$str_id'> ";
            $output .= "<input type='submit' class='tableTopButton redButton' name='canceled' value='" . put("no", $l) . "'> ";
            $output .= "<input type='submit' class='tableTopButton' name='confirmed' value='" . put("yes", $l) . "'>";
            $output .= "</form>";
        } else
            $output .= put("part_sold", $l);
    } else
        $output .= put("part_sold", $l);


    $output .= "</div>";
} else {

    function marketPartBuy($str_id) {

        $money = getPlayerMoney();
        $price = queryMarketPartCost($str_id);
        if ($money >= $price) {
            return queryMarketPartBuy($str_id);
        } else {
            return "no_money";
        }
    }

    if (isset($post['confirmed'])) { //Bestäigung anzeigen
        $str_id = $post["id"];

        $buy = marketPartBuy($str_id);

        $output .= "<span class='dealInfoText $buy'>";
        $output .= put($buy, $l);
        $output .= "</span>";
    }

    //Filterfunktion
    $output .= "<form method='get' action='main.php'>";
    $output .= "<input type='hidden' name='page' value='market'>";

    //Filter Teil
    $filterParts = queryTuningPartsAll();
    $output .= "<select class='filter' name='filterParts'>";
    $output .= "<option value='none'>SHOW ALL</option>";

    foreach ($filterParts as $filter) {
        if (isset($get["filterParts"]) AND $get["filterParts"] == $filter)
            $output .= "<option value='$filter' selected>" . put($filter, $l) . "</option>";
        else
            $output .= "<option value='$filter'>" . put($filter, $l) . "</option>";
    }

    $output .= "</select>";

    //Filter Liga
    $output .= "<select class='filter' name='filterLiga'>";
    $output .= "<option value='none'>SHOW ALL</option>";

    for ($i = 1; $i < 9; $i++) {
        if (isset($get["filterLiga"]) AND $get["filterLiga"] == $i)
            $output .= "<option value='$i' selected>" . put("liga", $l) . " $i</option>";
        else
            $output .= "<option value='$i'>" . put("liga", $l) . " $i</option>";
    }
    $output .= "</select>";


    $output .= '<input class="sellButton tableTopButton" name="filter" type="submit" value="Refresh">';
    $output .= "</form>";



    //Filtereingabe überprüfen
    if (isset($get["filterParts"]) AND in_array($get["filterParts"], $filterParts)) {
        $partFilter = $get["filterParts"];
    } else
        $partFilter = '%';
    if (isset($get["filterLiga"]) AND $get["filterLiga"] > 0 AND $get["filterLiga"] < 9) {
        $ligaFilter = $get["filterLiga"];
    } else
        $ligaFilter = '%';



    //Blätterseite abfragen
    $menge = ceil(queryMarketParts(0, true, $partFilter, $ligaFilter));
    if (isset($get["s"]) AND $get["s"] <= $menge)
        $s = $get["s"];
    else
        $s = 1;

    $pages = getPages($menge, $s, "?page=market&sub=partmarket");
    $output .= $pages;

    //Markt ausgeben
    $output .= "<table style='font-size:13px;' class='tableRed selling noclick'>
                <tr>
                  <th>" . put("seller", $l) . "</th>
                  <th>" . put("part", $l) . "</th>
                  <th>" . put("power", $l) . "</th>
                  <th>" . put("price", $l) . "</th>
                </tr>";

    //Aktuelle Seite auslesen
    $partMarket = queryMarketParts($s, false, $partFilter, $ligaFilter);
    if ($partMarket)
        foreach ($partMarket as $item) {
            $acc = $item["acc"];
            $speed = $item["speed"];
            $hand = $item["hand"];
            $dura = $item["dura"];

            $link = "?page=market&sub=partmarket&mode=buy&id=" . $item["id"];

            $output .= "<tr>";
            $output .= "<td class='partSeller'><a href='$link'>" . $item["username"] . "</a></td>
                <td class='partTitle'><a href='$link'>" . put($item["part"], $l) . " (" . $item["liga"] . ")</a></td>
                <td class='partPerf'><a href='$link'>" . outputDetails($acc, $speed, $hand, $dura) . "</a></td>
                <td><a href='$link'>" . dollar($item["sell"]) . "</a></td>";
            $output .= "</tr>";
        } else {
        $output .= "<tr><td colspan='4'>" . put("market_empty", $l) . "</td></tr>";
    }

    $output .= "</table>";
    $output .= $pages;
    
}

$output .= "</div>";
