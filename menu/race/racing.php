<?php

$info_out = "";

//Spielerliga auslesen
$maxLevel = getPlayerLiga();

if (!isset($get["league"]))
    $leagueOpen = "beginner";
else
    $leagueOpen = $get["league"];

//Ligaliste generieren
function showLigaList() {
    global $leagueOpen, $l, $maxLevel;
    $leagues = queryRaceLeagues();

    //Liga Auswahl ausgeben
    $ret = "<div class='ligaList'>";
    foreach ($leagues as $race_league) {
        $name = $race_league["league"];
        $level = $race_league["level"];
        $href = "href='?page=race&sub=racing&league=$name'";

        if ($name == $leagueOpen)
            $active = "active";
        else
            $active = "";
        if ($level > $maxLevel) {
            $href = "";
            $locked = "race_locked";
        } else
            $locked = "";
        //$ret .= "<li $active><a href='?page=race&sub=racing&league=$race_league'>
        //                    ".levelImg($race_league, true)."</a></li>";

        $ret .= "<a class='flex-item-list $locked' $href><div class='$active'>
                
                    
                        <span class='absTitle'>" . put($name, $l) . "</span>
                            <span>" . formatLevelColor($level) . "</span>
                </div></a>";
    }
    $ret .= "</div>";
    return $ret;
}

$races = queryRaces($leagueOpen);
$cars = queryPlayerCars();
$drivers = queryDrivers();

//Autoliste generieren
function returnCarSelect() {
    global $cars;
    global $liga;
    global $l;
    $carselect = "";
    if ($cars)
        foreach ($cars as $car) {
            $carLiga = $car["carLiga"];
            $car_id = $car["garage_id"];
//        $acc = $car["acc"];
//        $speed = $car["speed"] + calcCarAttribute("speed");
//        $hand = $car["hand"];
//        $dura = $car["dura"];
            $name = $car["title"];

            $carValueList = outputCarPartsSumList($car_id);

            if ($carLiga <= $liga && queryCarIsNotRacing($car_id))
                $carselect .= "<option value='$car_id'>$name ($carValueList)</option>";
        }
    return $carselect;
}

//Fahrerliste gen
function returnDriverSelect() {
    global $drivers;
    global $liga;
    $carselect = "";

    if ($drivers)
        foreach ($drivers as $drv) {
            $driver_liga = $drv["liga"];
            $name = $drv["name"];
            $skill = showSkill($drv["skill"]);
            $id = $drv["id"];

            if ($driver_liga >= $liga && queryDriverIsNotRacing($id))
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

//Rennen starten
if (isset($post['send'])) { //Abgeschicktes Formular
    $race_id = $post["race_id"];
    $car_id = $post["car_id"];
    $driver_id = $post["driver_id"];

    $race = raceNew($race_id, $car_id, $driver_id);

    $info_out .= "<span class='dealInfoText $race'>";
    $info_out .= put($race, $l);
    $info_out .= "</span>";
}

$carSelect = returnCarSelect();
$driverSelect = returnDriverSelect();

$disabled = "";

if (strlen($carSelect) < 1) {
    $disabled = "disabled";
    $carSelect = "<option>------</option>";
}
if (strlen($driverSelect) < 1) {
    $disabled = "disabled";
    $driverSelect = "<option>------</option>";
}

$race_output = "";
$leagueList = "";

if ($races)
    foreach ($races as $race) {
        //level needed
        $level = $race["level"];
        $league = $race["league"];
        $type = $race["type"];


        //Sperre berechnen
        $canRace = queryUserCanRace($race["id"], getPlayerExp(), getPlayerSprit());
        $locked = "flex";
        $whyBlock = "";

        if ($canRace === "exp") {
            $exp_needed = levelExp($liga) * $race["exp_needed"] * getLigaQuot();
            $whyBlock = "only " . ep($exp_needed - getPlayerExp()) . " left";
        } else if ($canRace === "sprit") {
            $whyBlock = "Not enough fuel. " . $race["sprit_needed"] . "L is needed";
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
        $race_output .= "<div class='dealer'>
                    <div class='locked' style='display:$locked'>
                        
                        <div class='whyBlock'>$whyBlock</div>
                    </div>

                    <div class='tuneInfoFlex' style='max-width:100%;  margin-bottom:45px;'>
                        <div class='tuneTitle'>
                            " . getRaceName($league, $type) . "
                        </div>
                       
                        <div class='tuneInfo'> 
                            <div class='tuneDesc'>\"" . put("desc_" . "race", $l) . "\"</div>
                            
                            <div class='racingDetails flex'>
                                " . racingDetail("fuel", gas($race["sprit_needed"])) . "
                                " . racingDetail("time", formatSeconds($race["dur"])) . "
                                " . racingDetail("money", "max. " . $dollar_reward) . "
                                " . racingDetail("exp", "max. " . ep($race["exp"])) . "
                            </div>
                            <div class='racingDetails flex'>
                            
                                " . racingDetail("acc1", $macc) . "
                                " . racingDetail("speed1", $mspeed) . "
                                " . racingDetail("hand1", $mhand) . "
                                " . racingDetail("dura1", $mdura) . "
                            </div>
                            

                        </div>
                    
                    </div>
                    
                    <div class='tuneFooter absolute'>
                    
                        <form method='POST' style='display:inline-block;' action='?page=race&sub=racing&liga=$liga'>   
                        <select name='driver_id' class='select_car'>
                            " . $driverSelect . "
                        </select>
                        <select name='car_id' class='select_car'>
                            " . $carSelect . "
                        </select>

                            <input type='hidden' name='race_id' value='" . $race["id"] . "'>
                            <input class='tableTopButton saveScroll' name='send' type='submit' value='" . put("race_now", $l) . "' $disabled>
                        </form>
                    </div>
                </div>";
    }


$output = outputTut("race_info", $l);

$output .= showLigaList();
//$output .= "<ul class='ligaList'>$leagueList</ul>";

$output .= "<div id='racing'>";

$output .= $info_out;

$output .= $race_output;

$output .= "</div>";






