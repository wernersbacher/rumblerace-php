<?php

$output = outputTut("sell_sprit", $l);
if (isset($post["price"]) && isset($post["amount"])) {
    //Verkaufen
    $price = preg_replace('~[^0-9|^.|(?=2.)]~', '', $post["price"]);
    $amount = preg_replace('~[^0-9|^.|(?=2.)]~', '', $post["amount"]);
    if ($price > 0 && $amount > 0 && $amount <= getPlayerSprit()) {
        $sell = querySpritSell($price, $amount);
    }
    else if ($amount > getPlayerSprit()) {
         $sell = "sell_not_enough_sprit";
    }
    else {
        $sell = "sell_check_input";
    }

    $output .= "<span class='dealInfoText $sell'>";
    $output .= put($sell, $l);
    $output .= "</span>";
}


$output .= "<div class='settings'>".put("type_in_sprit", $l);

$output .= "<form data-dialog='Did you type in the correct values?' id='calcSprit' method='post' action='?page=$page&sub=$sub'>";
$output .= "<input type='number' required='required' min='0.01' step='0.01' name='price' id='sprit_price' placeholder='' class='sp_price sp tableTopInput'> " . getCurrency() . " ";
$output .= "<div id='sprit_price_min' class='tableTopButton'>min</div> <br/>";
$output .= "<input type='number' required='required' min='0.01' step='0.01' name='amount' id='sprit_amount' placeholder='' class='sp_amount sp tableTopInput'> &#8467; ";
$output .= "<div id='sprit_amount_max' class='tableTopButton'>max</div> <br/>";
$output .= "<span id='calcSpritResult'>--</span><br/>";
$output .= "<input type='submit' class='tableTopButton dialog' name='sell' value='" . put("sell", $l) . "'>";
$output .= "</form>";

$output .="</div>";
