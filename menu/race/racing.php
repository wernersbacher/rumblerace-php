<?php

$output = outputTut("race_info", $l);

//Spielerliga auslesen
$maxLiga = getPlayerLiga();

if (!isset($get["liga"]) OR $get["liga"] > $maxLiga)
    $liga = 1;
else
    $liga = $get["liga"];

//LIgaliste generieren
function showLigaList() {
    global $maxLiga;
    global $liga;
    
    //Liga Auswahl ausgeben
    $ret = "<ul class='ligaList'>";
    for ($i = 1; $i <= $maxLiga; $i++) {
        if($i == $liga) $active = "class='active'"; else $active = "";
        $ret .= "<li $active><a href='?page=race&sub=racing&liga=$i'><img src='img/liga/$i.png' /></a></li>";
    }
    $ret .= "</ul>";
    return $ret;
}

$races = queryRaces($liga);
$cars = queryPlayerCars();
$drivers = queryDrivers();

//Autoliste generieren
function returnCarSelect() {
    global $cars;
    global $liga;
    global $l;
    $carselect = "";
if($cars)
    foreach ($cars as $car) {
        $carLiga = $car["carLiga"];
        $car_id = $car["garage_id"];
//        $acc = $car["acc"];
//        $speed = $car["speed"] + calcCarAttribute("speed");
//        $hand = $car["hand"];
//        $dura = $car["dura"];
        $name = $car["title"];
        
        $carValueList = outputCarPartsSumList($car_id);
        
        if($carLiga <= $liga && queryCarIsNotRacing($car_id))
            $carselect .= "<option value='$car_id'>$name ($carValueList)</option>";
    }
    return $carselect; 
}

//Fahrerliste gen
function returnDriverSelect() {
    global $drivers;
    global $liga;
    $carselect = "";
    
if($drivers)
    foreach ($drivers as $drv) {
        $driver_liga = $drv["liga"];
        $name = $drv["name"];
        $skill = showSkill($drv["skill"]);
        $id = $drv["id"];
        
        if($driver_liga >= $liga && queryDriverIsNotRacing($id))
            $carselect .= "<option value='$id'>$name (Skill: $skill%)</option>";
    }
    return $carselect;
}


function raceNew($race_id, $car_id, $driver_id) {
    $data = queryRaceData($race_id);
    
    //checkt ob das auto gerade verfügbar ist, und das rennen freigeschaltet ist
    if (queryDriverIsNotRacing($driver_id) && queryCarIsNotRacing($car_id) && queryUserCanRace($race_id, getPlayerExp(), getPlayerSprit()) == true) {
        return queryRacing($car_id, $data["id"], $data["dur"], $data["sprit_needed"], $driver_id);
    } else if (!queryUserHasCarID($car_id)) {
        return "too_many_races";
    } else {
        return "database_error";
    }
}


$output .= showLigaList();

$output .= "<div id='racing'>";
    
//Rennen starten
if (isset($post['send'])) { //Abgeschicktes Formular
    $race_id = $post["race_id"];
    $car_id = $post["car_id"];
    $driver_id = $post["driver_id"];
    
    $race = raceNew($race_id, $car_id, $driver_id);

    $output .= "<span class='dealInfoText $race'>";
    $output .= put($race, $l);
    $output .= "</span>";
}

$carSelect = returnCarSelect();
$driverSelect = returnDriverSelect();

$disabled = "";

if(strlen($carSelect) < 1) {
    $disabled = "disabled";
    $carSelect = "<option>------</option>";
} 
if(strlen($driverSelect) < 1) {
   $disabled = "disabled";
    $driverSelect = "<option>------</option>"; 
}

if($races)
    foreach($races as $race) {
    //Sperre berechnen
    $canRace = queryUserCanRace($race["id"], getPlayerExp(), getPlayerSprit());
    $locked = "block";
    $whyBlock = "";
    
    if($canRace === "exp") {
        $exp_needed = expToLiga($liga)*$race["exp_needed"]* getLigaQuot();
        $whyBlock = "only ".ep($exp_needed-getPlayerExp())." left";
    } else if ($canRace === "sprit") {
        $whyBlock = "Not enough fuel. ".$race["sprit_needed"]."L is needed";
    } else {
        $locked = "none";
    }
    
    //Einzelne Gewichtungen ausrechnen
    $macc = outputToProcent($race["macc"]);
    $mspeed = outputToProcent($race["mspeed"]);
    $mhand = outputToProcent($race["mhand"]);
    $mdura = outputToProcent($race["mdura"]);
    
    $dollar_reward = dollar(calcDollarReward($race["sprit_needed"]));
    

    //Ausgabe einzelner Rennen
    $output .= "<div class='dealer'>
                    <div class='locked' style='display:$locked'>
                        LOCKED <br/>
                        <span style='font-size:15px;'>$whyBlock</span>
                    </div>

                    <div class='tuneInfoFlex' style='max-width:80%;  margin-bottom:45px;'>
                        <div class='tuneTitle'>
                            ".getRaceName($race["name"])."
                        </div>
                       
                        <div class='tuneInfo'> 
                            <div class='tuneDesc'>\"" . put("desc_" . "race", $l) . "\"</div>
                            
                            <div class='tuneBuyDetails' style=''>
                                ".$race["sprit_needed"]."L, 
                                ".formatSeconds($race["dur"])."s, 
                                " . put("reward", $l) . ": max. $dollar_reward, 
                                " . put("erfahrung", $l) . ": max. ".ep($race["exp"])." 
                            </div>
                            <div class='tuneBuyDetails'>
                                <div class='stat_image_wrapper_tuner'><img src='img/stats/acc1.png' alt='Acc'/></div> <span class='tune_acc'>$macc</span> |
                                <div class='stat_image_wrapper_tuner'><img src='img/stats/speed1.png' alt='speed'/></div> <span class='tune_speed'>$mspeed</span> | 
                                <div class='stat_image_wrapper_tuner'><img src='img/stats/handling1.png' alt='hand'/></div> <span class='tune_speed'>$mhand</span> |
                                <div class='stat_image_wrapper_tuner'><img src='img/stats/strength1.png' alt='str'/></div> <span class='tune_speed'>$mdura</span> 
                            </div>
                            

                        </div>
                    
                    </div>
                    
                    <div class='tuneFooter'>
                    
                        <form method='POST' style='display:inline-block;' action='?page=race&sub=racing&liga=$liga'>   
                        <select name='driver_id' class='select_car'>
                            ".$driverSelect."
                        </select>
                        <select name='car_id' class='select_car'>
                            ".$carSelect."
                        </select>

                            <input type='hidden' name='race_id' value='".$race["id"]."'>
                            <input class='tableTopButton saveScroll' name='send' type='submit' value='" . put("race_now", $l) . "' $disabled>
                        </form>
                    </div>
                </div>";
    }



$output .= "</div>";






