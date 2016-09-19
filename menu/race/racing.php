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
        $partPs = calcPS($car_id);
        $partPrf = calcPerf($car_id);
        $carPs = $car["ps"];
        $ps = $carPs+$partPs;
        $perf = $car["perf"]+$partPrf;
        $name = $car["title"];
        
        if($carLiga == $liga && queryCarIsNotRacing($car_id))
            $carselect .= "<option value='$car_id'>$name ($ps ".  put("hp", $l).")</option>";
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
            $carselect .= "<option value='$id'>$name (Skill: $skill)</option>";
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

if(strlen($carSelect) < 1 OR strlen($driverSelect) < 1) {
    $disabled = "disabled";
    $carSelect = "<option>------</option>";
    $driverSelect = "<option>------</option>";
} else $disabled = "";

if($races)
    foreach($races as $race) {
    //Sperre berechnen
    $canRace = queryUserCanRace($race["id"], getPlayerExp(), getPlayerSprit());
    $locked = "block";
    $whyBlock = "";
    
    if($canRace === "exp") {
        $exp_needed = expToLiga($liga)*$race["exp_needed"];
        $whyBlock = "only ".ep($exp_needed-getPlayerExp())." left";
    } else if ($canRace === "sprit") {
        $whyBlock = "Not enough fuel. ".$race["sprit_needed"]."L is needed";
    } else {
        $locked = "none";
    }
        

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
                                " . put("reward", $l) . ": max. ".dollar($race["reward"]).", 
                                " . put("erfahrung", $l) . ": max. ".ep($race["exp"])." 
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
                            <input class='tableTopButton' name='send' type='submit' value='" . put("race_now", $l) . "' $disabled>
                        </form>
                    </div>
                </div>";
    }



$output .= "</div>";






