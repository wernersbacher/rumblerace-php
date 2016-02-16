<?php

function buyNewCar($model) {
    $money = getPlayerMoney();
    $price = queryNewCarCost($model);
    if ($money >= $price) {
        return queryCarBuy($model, $price);
    } else {
        return "no_money";
    }
}

$output = outputTut("cd_shiny", $l);

if (isset($post['send'])) { //Abgeschicktes Formular
    $model = $post["model"];
    
    $purchase = buyNewCar($model);
    
    $output .= "<span class='dealInfoText $purchase'>";
    $output .= put($purchase, $l);
    $output .= "</span>";
}

//Liste aller neuen Fahrzeuge ausgeben
$cars = queryNewCars(getPlayerLiga());
$output .= "<div id='cardealer'>";

foreach ($cars as $car) {//$car["title"]
$liga = $car["liga"];

$output .= "<div class='dealer'>
                    <div class='imgFlex'>
                        <img src='img/car/".$car["name"] .".jpg' />
                    </div>

                    <div class='infoFlex'>
                        <div class='dealTitle'>
                            " . $car["title"] ."
                        </div>

                        <div class='dealInfo'>
                            <span>" . $car["ps"] ." ".put("hp", $l)."</span> 
                            <span>" . $car["perf"] ." Perf.</span> 
                        </div>

                    </div>

                    <div class='dealLiga'><img src='img/liga/" . $liga . ".png' alt='League " . $liga . "' title='League " . $liga . "' /></div>

                    <div class='dealBuy'>
                        <span class='dealPrice'>" . dollar ( $car["preis"])."</span>
                        <form method='POST' style='display:inline-block;' action='?page=garage&sub=cardealer'>
                            <input type='hidden' name='model' value='" . $car["name"] ."'>
                            <input class='tableTopButton' name='send' type='submit' value='".put("buy_now", $l)."'>
                        </form>
                    </div>
               </div>";
}
$output .= "</div>";
