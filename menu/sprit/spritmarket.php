<?php

$output = outputTut("sprit_market", $l);

$output .= "<div id='market'>";

if ($mode == "buy" && isset($get["id"])) {
    $str_id = $get["id"];
    $str_data = queryMarketSpritData($str_id);

    $output .= "<div class='textCenter'>";

    if ($str_data) {
        $amount = $str_data["amount"];
        $price = $str_data["price"];
        $user_id = $str_data["user_id"];

        if ($user_id == $_SESSION["user_id"]) {
            //Löschen des eigenen Elements

            $output .= put("sprit_back", $l) . "<br/><br/>";

            $output .= "<form method='post' action='?page=$page&sub=$sub'>";
            $output .= "<input type='hidden' name='id' value='$str_id'> ";
            $output .= "<input type='submit' class='tableTopButton redButton' name='canceled' value='" . put("no", $l) . "'> ";
            $output .= "<input type='submit' class='tableTopButton' name='confirmed' value='" . put("yes", $l) . "'>";
            $output .= "</form>";
        } else {
            //Kaufen des Elements

            $output .= put("type_in_sprit", $l) . " Max " . gas($amount) . "<br/>";
            $output .= "1 litre costs <b>" . dollar($price) . "</b>";

            $output .= "<form id='calcSprit' method='post' action='?page=$page&sub=$sub'>";
            $output .= "<input type='hidden' name='id' value='$str_id'> ";
            $output .= "<input type='hidden' value='$price' class='sp_price sp'>";
            $output .= "<input type='number' min='0.01' max='$amount' step='0.01' name='amount' id='buy_amount' placeholder='100' class='sp_amount sp tableTopInput'> ";
            $output .= "<div id='sprit_buy_max' class='tableTopButton'>max</div> <br/>";
            $output .= "<span id='calcSpritResult'>--</span><br/>";
            $output .= "<input type='submit' class='tableTopButton' name='confirmed' value='" . put("buy", $l) . "'>";
            $output .= "</form>";
        }
    } else {
        $output .= put("sprit_not_found", $l);
    }
} else { // Ausgabe des marktes

    function marketSpritBuy($str_id, $amount) {
        $str_id = intval($str_id);
        $money = getPlayerMoney();
        $price = queryMarketSpritCost($str_id, $amount);
        if ($money >= $price) {
            return queryMarketSpritBuy($str_id, $amount);
        } else {
            return "no_money";
        }
    }

    if (isset($post['confirmed'])) {
        $str_id = $post["id"];
        $amount = 0;
        if (isset($post["amount"]))
            $amount = floatval($post["amount"]);

        $buy = marketSpritBuy($str_id, $amount);

        $output .= "<span class='dealInfoText $buy'>";
        $output .= put($buy, $l);
        $output .= "</span>";
    }


    // Button zum verkaufen hinzufügen
    $output .= "<a href='?page=sprit&sub=sell'><button class='tableTopButton'>".put("s_sell",$l)."</button></a>";


    //Blätterseite abfragen
    $menge = ceil(queryMarketSprit(0, true));
    if (isset($get["s"]) AND $get["s"] <= $menge)
        $s = $get["s"];
    else
        $s = 1;

    //Seiten berechnen und ausgeben
    $pages = getPages($menge, $s, "?page=$page&sub=$sub");
    $output .= $pages;

    //Markt ausgeben
    $output .= "<table style='font-size:13px;' class='tableRed selling noclick'>
                <tr>
                  <th>" . put("seller", $l) . "</th>
                  <th>" . put("count", $l) . "</th>
                  <th>" . put("price", $l) . "</th>
                </tr>";


    //Aktuelle Seite auslesen
    $spritMarket = queryMarketSprit($s, false);
    if ($spritMarket)
        foreach ($spritMarket as $item) {

            $link = "?page=$page&sub=$sub&mode=buy&id=" . $item["sm_id"];

            $output .= "<tr>";
            $output .= "<td class='partSeller'><a href='$link'>" . $item["username"] . "</a></td>
                <td class='partTitle'><a href='$link'>" . gas($item["amount"]) . "</a></td>
                <td><a href='$link'>" . dollar($item["price"]) . "</a></td>";
            $output .= "</tr>";
        } else {
        $output .= "<tr><td colspan='4'>" . put("market_empty", $l) . "</td></tr>";
    }

    $output .= "</table>";
    $output .= $pages;
}

$output .= "</div>";
