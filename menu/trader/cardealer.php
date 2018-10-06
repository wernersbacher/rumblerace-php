<?php

function buyNewCar($model) {
    $money = getPlayerMoney();
    $price = queryNewCarCost($model);
    $cars = queryPlayerCars(); // Autos auslesen
    $nowCars = __count($cars);
    $maxCars = getMaxCars();
    if ($money >= $price && $nowCars < $maxCars) {
        return queryCarBuy($model, $price);
    } else if ($nowCars >= $maxCars) {
        return "garage_full";
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
$money = getPlayerMoney();

$carsPlayer = queryPlayerCars(); // Autos auslesen
$nowCars = __count($carsPlayer);
$maxCars = getMaxCars();
$left = $maxCars - $nowCars;

$output .= "<div class='settings'>
            " . put("garage_full_1", $l) . " <b>$left</b> " . put("garage_full_2", $l) . " ($nowCars/$maxCars) <br/>
            </div>";

$output .= "<div id='cardealer'>";

foreach ($cars as $car) {//$car["title"]
    $liga = $car["liga"];
    $preis = $car["preis"];
    if ($preis > $money OR $left < 1)
        $dis = "disabled";
    else
        $dis = "";

    $output .= "<div class='dealer'>
                    <div class='imgFlex'>
                        <img src='img/car/" . $car["name"] . ".jpg' />
                            
                    </div>

                    <div class='infoFlex'>
                        <div class='dealTitle'>
                            " . $car["title"] . "
                                <div class='driverLiga'><img src='img/liga/" . $liga . ".png' alt='League " . $liga . "' title='League " . $liga . "' /></div>
                        </div>

                        <div class='dealInfo'>
                            <span>A: ". $car["acc"] . "</span> 
                            <span>S: ". $car["speed"] . "</span> 
                            <span>H: ". $car["hand"] . "</span> 
                            <span>D: ". $car["dura"] . "</span> 
                        </div>

                    </div>

                    <!--<div class='dealLiga'><img src='img/liga/" . $liga . ".png' alt='League " . $liga . "' title='League " . $liga . "' /></div>-->

                    <div class='dealBuy'>
                        <span class='dealPrice'>" . dollar($preis) . "</span>
                        <form method='POST' style='display:inline-block;' action='?page=trader&sub=cardealer'>
                            <input type='hidden' name='model' value='" . $car["name"] . "'>
                            <input class='tableTopButton' name='send' type='submit' value='" . put("buy_now", $l) . "'$dis>
                        </form>
                    </div>
               </div>";
}
$output .= "</div>";
