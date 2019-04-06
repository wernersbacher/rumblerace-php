<?php

if (!isset($get["garage_id"]))
    $id = 0;
else
    $id = $get["garage_id"];

$output = outputTut("car_tuning", $l);
$output .= backLink("?page=garage&sub=cars");

if ($mode == "tune" && queryCarIsNotRacing($id)) {

    if (isset($post['tune'])) { //Teile anbringen
        $changeParts = array();
        $allKats = queryTuningKats();

        foreach ($allKats as $kat) {

            $parts = queryTuningParts($kat);

            foreach ($parts as $part) {
                if (array_key_exists($part, $post)) {
                    $str_id = $post[$part];
                    if ($str_id >= 0) {
                        //Teil einbauen
                        $changeParts[$part] = $str_id;
                    }
                }
            }
        }
        $tuning = queryTuningTheCar($changeParts, $id);
        if($tuning == "car_updated")
            $tutorial->tickOff("TUT_STATE_EQUIP");
        
        $output .= "<span class='dealInfoText $tuning'>";
        $output .= put($tuning, $l);
        $output .= "</span>";
    }

    $car = queryPlayerCarID($id);
    $mountedParts = queryPlayerPartsID($id);

    $output .= "
            <div id='cartuning'>
                <div class='carTuningTitle'>" . $car["title"] . "
                    ".levelImg($car["liga"], true)."
                         
                </div>
                <div class='carTuningInfo'>Nice car.</div>
                <div id='car_sketch'>";

    $katNames = queryTuningKats();
    $storage = queryStorage();

    $select = "";
    //Jede Kategorie durchgehen
    foreach ($katNames as $kat) {

        //Direktes Ausgeben desr SKetchangaben
        $output .= "<div id = '$kat' class = 'tuningPart'>
        <div class = 'tuningPartName'>" . put($kat, $l) . "</div>
        </div>";

        //Tabellenkategorien ausgeben
        $select .= "<table class='tuningTable tableRed tableClick'>
                <tr>
                  <th colspan='2'>" . put($kat, $l) . "</th>
                </tr>";


        $allParts = queryTuningParts($kat);
        foreach ($allParts as $part) {
            $partsAvail = false;
            $selectGenerated = "";

            //Ausgeben der aktuell eingebauten Sachen.
            if (array_key_exists($part, $mountedParts)) {
                $acc = $mountedParts[$part]["acc"];
                $speed = $mountedParts[$part]["speed"];
                $hand = $mountedParts[$part]["hand"];
                $dura = $mountedParts[$part]["dura"];
                $selectGenerated .= "<option value='none'>" . outputDetails($acc, $speed, $hand, $dura) . " (" . $mountedParts[$part]["liga"] . ") **</option>";
                $partsAvail = true;
            }
            //Standardausrüstung
            $selectGenerated .= "<option value='0'>----------</option>";

            //ausgeben der Teile im Lager (für jedes ein Select)
            if ($storage) {
                //var_dump($storage);
                foreach ($storage as $item) {
                    if ($item["part"] == $part && $item["liga"] == $car["liga"] && $item["garage_id"] == 0) {
                        $attr = outputItemAttributes($item);
                        $part_rarity = $attr["part_rarity"];
                        
                        $partsAvail = true; //Anzeigen, dass in dieser Kategorie ein Teil vorhanden ist
                        $acc = $item["acc"];
                        $speed = $item["speed"];
                        $hand = $item["hand"];
                        $dura = $item["dura"];
                        $selectGenerated .= "<option style='color:" . $part_rarity["color"] . "' value='" . $item["id"] . "'>" . outputDetails($acc, $speed, $hand, $dura) . " (" . $item["liga"] . ")</option>";
                    }
                }
            }
            $select .= "<tr>
                        <td>" . put($part, $l) . "</td>
                        <td>
                            <select class='beautySelect' name='$part' ".boolToDis($partsAvail).">";

            $select .= $selectGenerated;
            $select .= "</select>
                        </td>
                      </tr>";
        }

        $select .= "</table>";
    }

    $output .= "</div>"; //Schließen des sketches
    //Tuning ausgeben

    $output .= "<form method='POST' action='?page=garage&sub=cars&mode=tune&garage_id=$id'>";
    $output .= $select;
    $output .= "<input type='hidden' name='garage_id' value='$id'></input>";
    $output .= "<input class='tableTopButton' name='tune' type='submit' value='" . put("save_car", $l) . "'>";
    $output .= "</form>";

    $output .= "</div>";
} else { //Übersicht der Autos ausgeben
    $output = outputTut("cd_your_cars", $l);

    if (isset($post["action"]) && isset($post["garage_id"])) {
        //Auto verkaufen
        $garage_id = intval($post["garage_id"]);
        $notRacing = queryCarIsNotRacing($garage_id);
        if ($notRacing) {
            $sellCar = sellCarSystem($garage_id);
        } else
            $sellCar = "car_is_racing";

        $output .= "<span class='dealInfoText $sellCar'>";
        $output .= put($sellCar, $l);
        $output .= "</span>";
    }

    $cars = queryPlayerCars(); // Autos auslesen
    $nowCars = intval(__count($cars));
    $maxCars = getMaxCars();
    $left = intval($maxCars - $nowCars);

    $output .= "<div class='settings'>
            " . put("garage_full_1", $l) . " <b>$left</b> " . put("garage_full_2", $l) . " ($nowCars/$maxCars) <br/>
            " . put("garage_more_space", $l) . "
            </div>";

    $output .= "<div id='cardealer'>";


    if ($cars)
        foreach ($cars as $car) {//$car["title"]
            $carLiga = $car["carLiga"];
            $acc = calcAcc($car["garage_id"]); // [car => 10, parts => 80]
            $speed = calcSpeed($car["garage_id"]);
            $hand = calcHand($car["garage_id"]);
            $dura = calcDura($car["garage_id"]);


            //Autos, die gefahren werden, können nicht getunt oder verkauft werden.
            $disabled = boolToDis(queryCarIsNotRacing($car["garage_id"]));
           
            $output .= "<div class='dealer'>
                    <div class='imgFlex'>
                        <img class='carImgFlex' src='img/car/" . $car["name"] . ".jpg' />
                    </div>

                    <div class='infoFlex'>
                        <div class='dealTitle'>
                            " . $car["title"] . "
                                <div class='driverLiga'>".levelImg($carLiga)."
                                    </div>
                        </div>

                        <div class='dealInfo'>
                            <span><div class='stat_image_wrapper'><img src='img/stats/acc1.png' alt='Acc'/></div> " . $acc["sum"] . " (" . $acc["car"] . " + " . $acc["parts"] . ")</span> 
                            <span><div class='stat_image_wrapper'><img src='img/stats/speed1.png' alt='speed'/></div> " . $speed["sum"] . " (" . $speed["car"] . " + " . $speed["parts"] . ")</span> 
                            <span><div class='stat_image_wrapper'><img src='img/stats/hand1.png' alt='handling'/></div> " . $hand["sum"] . " (" . $hand["car"] . " + " . $hand["parts"] . ")</span> 
                            <span><div class='stat_image_wrapper'><img src='img/stats/dura1.png' alt='strength'/></div> " . $dura["sum"] . " (" . $dura["car"] . " + " . $dura["parts"] . ")</span> 
                        </div>

                    </div>

                    <!--<div class='dealLiga'><img src='img/liga/" . $carLiga . ".png' alt='Level " . $carLiga . "' title='Level " . $carLiga . "' /></div>-->

                    <div class='dealBuy'>
                        
                        <form method='POST' data-dialog='Sell the car for " . dollar(carSellPrice($car["preis"])) . "? The tuning parts are transferred into the storage.' style='display:inline-block;' action='?page=garage&sub=cars&garage_id=" . $car["garage_id"] . "'>
                            <input type='hidden' name='garage_id' value='" . $car["garage_id"] . "'>
                            <input type='hidden' name='action' value='sell'>
                            <input class='tableTopButton redButton dialog' name='send' type='submit' value='" . put("sell", $l) . "' $disabled>
                        </form>
                        
                        <form method='POST' style='display:inline-block;' action='?page=garage&sub=cars&mode=tune&garage_id=" . $car["garage_id"] . "'>
                            <input type='hidden' name='garage_id' value='" . $car["garage_id"] . "'>
                            <input class='tableTopButton' name='send' type='submit' value='" . put("tune_now", $l) . "' $disabled>
                        </form>
                    </div>
               </div>";
        } else {
        $output .= "<div class='settings'>
            " . put("garage_empty", $l) . "
            </div>";
    }
    $output .= "</div>";
}