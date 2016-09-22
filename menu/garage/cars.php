<?php

if (!isset($post["garage_id"]))
    $id = 0;
else
    $id = $post["garage_id"];

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
        $output .= "<span class='dealInfoText $tuning'>";
        $output .= put($tuning, $l);
        $output .= "</span>";
    }

    $car = queryPlayerCarID($id);
    $mountedParts = queryPlayerPartsID($id);

    $output .= "
            <div id='cartuning'>
                <div class='carTuningTitle'>" . $car["title"] . "
                    <img src='img/liga/" . $car["liga"] . ".png' />
                </div>
                <div class='carTuningInfo'>" . $car["ps"] . " " . put("hp", $l) . "</div>
                <div id='car_sketch'>";

    $katNames = queryTuningKats();
    $storage = queryStorage();

    $select = "";
    //Jede Kategorie durchgehen
    foreach ($katNames as $kat) {

        //Direktes Ausgeben desr SKetchangaben
        $output.= "<div id = '$kat' class = 'tuningPart'>
        <div class = 'tuningPartName'>" . put($kat, $l) . "</div>
        </div>";

        //Speichern der Tabelle
        $select .= "<table class='tuningTable tableRed tableClick'>
                <tr>
                  <th colspan='2'>" . put($kat, $l) . "</th>
                </tr>";

        $allParts = queryTuningParts($kat);
        foreach ($allParts as $part) {
            $select .= "<tr>
                        <td>" . put($part, $l) . "</td>
                        <td>
                            <select name='$part'>";

            //Ausgeben der aktuell eingebauten Sachen.
            if (array_key_exists($part, $mountedParts)) {
                $select .= "<option value='none'>" . $mountedParts[$part]["value"] . " " . put("unit_" . $kat, $l) . " (" . $mountedParts[$part]["liga"] . ") **</option>";
            }

            $select .= "<option value='0'>----------</option>";

            //ausgeben der Teile im Lager
            if ($storage)
                foreach ($storage as $item) {
                    if ($item["part"] == $part && $item["liga"] == $car["liga"] && $item["garage_id"] == 0) {
                        $select .= "<option value='" . $item["id"] . "'>" . $item["value"] . " " . put("unit_" . $kat, $l) . " (" . $item["liga"] . ")</option>";
                    }
                }



            $select .= "</select>
                        </td>
                      </tr>";
        }

        $select .= "</table>";
    }

    $output.= "</div>"; //Schließen des sketches
    //Tuning ausgeben

    $output .= "<form method='POST' action='?page=garage&sub=cars&mode=tune'>";
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


    $output .= "<div id='cardealer'>";

    $cars = queryPlayerCars();
    if ($cars)
        foreach ($cars as $car) {//$car["title"]
            $carLiga = $car["carLiga"];
            $partPs = calcPS($car["garage_id"]);
            $partPrf = calcPerf($car["garage_id"]);
            $carPs = $car["ps"];
            $ps = $carPs + $partPs;
            $perf = $car["perf"] + $partPrf;

            //Autos, die gefahren werden, können nicht getunt oder verkauft werden.
            $disabled = "disabled";
            if (queryCarIsNotRacing($car["garage_id"]))
                $disabled = "";

            $output .= "<div class='dealer'>
                    <div class='imgFlex'>
                        <img src='img/car/" . $car["name"] . ".jpg' />
                    </div>

                    <div class='infoFlex'>
                        <div class='dealTitle'>
                            " . $car["title"] . "
                        </div>

                        <div class='dealInfo'>
                            <span>" . ps($ps) . " (" . ps($carPs) . " + " . ps($partPs) . ")</span> 
                            <span>$perf Perf. (" . $car["perf"] . " + " . $partPrf . " Perf.)</span> 
                        </div>

                    </div>

                    <div class='dealLiga'><img src='img/liga/" . $carLiga . ".png' alt='League " . $carLiga . "' title='League " . $carLiga . "' /></div>

                    <div class='dealBuy'>
                        
                        <form method='POST' data-dialog='Sell the car for " . dollar(carSellPrice($car["preis"])) . "? The tuning parts are transferred into the storage.' style='display:inline-block;' action='?page=garage&sub=cars'>
                            <input type='hidden' name='garage_id' value='" . $car["garage_id"] . "'>
                            <input type='hidden' name='action' value='sell'>
                            <input class='tableTopButton redButton dialog' name='send' type='submit' value='" . put("sell", $l) . "' $disabled>
                        </form>
                        
                        <form method='POST' style='display:inline-block;' action='?page=garage&sub=cars&mode=tune'>
                            <input type='hidden' name='garage_id' value='" . $car["garage_id"] . "'>
                            <input class='tableTopButton ' name='send' type='submit' value='" . put("tune_now", $l) . "' $disabled>
                        </form>
                    </div>
               </div>";
        } else
        $output .= put("garage_empty", $l);
    $output .= "</div>";
}