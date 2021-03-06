<?php

$ausgabe = "";

function buySpritTeil($teil_id) {
    $money = getPlayerMoney();
    $price = querySpritPartCost($teil_id);
    if ($money >= $price) {
        return querySpritTeilBuy($teil_id, $price);
    } else {
        return "no_money";
    }
}

$bought = "";
if (isset($post["teil_id"])) { //Beim Kauf eines Objektes (Sprit)
    $teil_id = $post["teil_id"];

    //Umleitung, um korrekten Sprit anzuzeugen
    $purch = buySpritTeil($teil_id);
    if($purch == "teil_bought")
        $tutorial->tickOff("TUT_STATE_SPRIT");
    
    header("Location: main.php?page=sprit&status=".$purch);
    
} else if(isset($get["status"])) {
    $purch = $get["status"];
    $bought .= "<span class='dealInfoText $purch'>";
    $bought .= put($purch, $l);
    $bought .= "</span>";
}

$output = outputTut("produce_gas", $l);

//Ausgeben der Produktionen
$fabrik = queryFabrikTeile();
$money = getPlayerMoney();

foreach ($fabrik as $teil) {
    $liga = $teil["tier"];
    $count = $teil["count"];
    $title = $teil["title"];
    $preis = calcCost($teil["cost"], $count);
    if ($count < 1)
        $count = 0;
    if ($preis > $money)
        $dis = "disabled";
    else
        $dis = "";

    $ausgabe .= "<div class='prod'>
                                        
                    <div class='imgFlex smallFlex'>
                        <img style='height:100%' src='img/fuel/$title.jpg' />
                    </div>

                    

                    <div class='infoFlex'>
                        <div class='dealTitle'>
                            " . put($title, $l) . "
                                <div class='driverLiga'>
                            ".levelImg($liga)."</div>
                        </div>

                        <div class='dealInfo'>
                            <span>" . gas($teil["lit"]) . "/min</span>
                        </div>
                        
                        <div class='count_big'>x$count</div>

                    </div>


                    <div class='dealBuy'>
                        <span class='dealPrice'>" . dollar($preis) . "</span>
                        <form method='POST' style='display:inline-block;' action='?page=$page&sub=$sub'>
                            <input type='hidden' name='teil_id' value='" . $teil["id"] . "'>
                            <input class='tableTopButton saveScroll' name='send' type='submit' value='" . put("buy_now", $l) . "' $dis>
                        </form>
                    </div>
               </div>";
}




$output .= "$bought
            <div id='produce'>
                
                <h3>
                " . put("sprit_prod_sum", $l) . ": " . gas(calcSpritMin()) . "/min<br/>
                    " . put("sprit_max_storage", $l) . ": ".gas(getMaxSprit())."
                </h3>
                

                $ausgabe
                
            </div>";
