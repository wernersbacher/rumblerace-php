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
    
    /*
     * #TUTORIAL
     */
    if($purchase == "car_bought")
        $tutorial->tickOff("TUT_STATE_BUYCAR");

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
    $tier = $car["tier"];
    $preis = $car["preis"];
    if ($preis > $money OR $left < 1)
        $dis = "disabled";
    else
        $dis = "";

    $output .= "<div class='dealer'>
                    <div class='imgFlex'>
                        <img class='carImgFlex' src='img/car/" . $car["name"] . ".jpg' />
                    </div>

                    <div class='infoFlex'>
                        <div class='dealTitle'>
                            " . $car["title"] . "
                                <div class='driverLiga'>
                            ".levelImg($tier)."</div>
                        </div>
                        
                        <div class='dealInfo'>
                            <span><div class='stat_image_wrapper'><img src='img/stats/acc1.png' alt='Acc'/></div> " . $car["acc"]. "</span> 
                            <span><div class='stat_image_wrapper'><img src='img/stats/speed1.png' alt='speed'/></div> " . $car["speed"]. "</span> 
                            <span><div class='stat_image_wrapper'><img src='img/stats/hand1.png' alt='handling'/></div> " . $car["hand"]. "</span> 
                            <span><div class='stat_image_wrapper'><img src='img/stats/dura1.png' alt='strength'/></div> " . $car["dura"]. "</span> 
                        </div>

                    </div>

                    <!--<div class='dealLiga'><img src='img/liga/" . $tier . ".png' alt='Level " . $tier . "' title='Level " . $tier . "' /></div>-->

                    <div class='dealBuy'>
                        <span class='dealPrice'>" . dollar($preis) . "</span>
                        <form method='POST' style='display:inline-block;' action='?page=$page&sub=$sub'>
                            <input type='hidden' name='model' value='" . $car["name"] . "'>
                            <input class='tableTopButton' name='send' type='submit' value='" . put("buy_now", $l) . "'$dis>
                        </form>
                    </div>
               </div>";
}
$output .= "</div>";
