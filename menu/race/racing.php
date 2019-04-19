<?php

$info_out = "";

//Spielerliga auslesen
$maxLevel = getPlayerLiga();

if (!isset($get["league"]))
    $leagueOpen = "beginner";
else
    $leagueOpen = $get["league"];

$leagueTier = getTierFromLeague($leagueOpen);

//Ligaliste generieren
function showLigaList() {
    global $leagueOpen, $l, $maxLevel;
    $leagues = queryRaceLeagues();

    //Liga Auswahl ausgeben
    $ret = "<div class='ligaList'>";
    foreach ($leagues as $race_league) {
        $name = $race_league["league"];
        $tier = $race_league["tier"];
        $href = "href='?page=$page&sub=$sub&league=$name'";

        if ($name == $leagueOpen)
            $active = "active";
        else
            $active = "";
        if ($tier > $maxLevel) {
            $href = "";
            $locked = "race_locked";
        } else
            $locked = "";
        //$ret .= "<li $active><a href='?page=$page&sub=$sub&league=$race_league'>
        //                    ".levelImg($race_league, true)."</a></li>";

        $ret .= "<a class='flex-item-list $locked' $href><div class='$active'>
                
                    
                        <span class='absTitle'>" . put($name, $l) . "</span>
                            <span>" . formatLevelColor($tier) . "</span>
                </div></a>";
    }
    $ret .= "</div>";
    return $ret;
}

$races = queryRaces($leagueOpen);
$cars = queryPlayerCars();
$drivers = queryDrivers();

//Autoliste generieren
function returnCarSelect($tier) {
    global $cars;
    //$liga = getPlayerLiga();
    //global $l;
    $carselect = "";
    if ($cars)
        foreach ($cars as $car) {
            $carTier = $car["carTier"];
            //Nur geeignete Fahrzeuge ausgeben
            if ($tier != $carTier)
                continue;
            $car_id = $car["garage_id"];
            $name = $car["title"];

            $carValueList = outputCarPartsSumList($car_id);

            if (queryCarIsNotRacing($car_id))
                $carselect .= "<option value='$car_id'>$name ($carValueList)</option>";
        }
    return $carselect;
}

//Fahrerliste gen
function returnDriverSelect($tier) {
    global $drivers;
    global $liga;
    $carselect = "";

    if ($drivers)
        foreach ($drivers as $drv) {
            $driver_liga = $drv["liga"];
            if($driver_liga != $tier)
                continue;
            $name = $drv["name"];
            $skill = showSkill($drv["skill"]);
            $id = $drv["id"];

            if ($driver_liga >= $liga && queryDriverIsNotRacing($id))
                $carselect .= "<option value='$id'>$name (Skill: $skill%)</option>";
        }
    return $carselect;
}

function raceNew($race_id, $car_id, $driver_id) {
    $race_data = queryRaceData($race_id);
    $db_err = "database_error";
    
    //getDriverByID
    
    //checkt ob das auto gerade verfügbar ist, und das rennen freigeschaltet ist
    $carTier = queryUserHasCarID($car_id);
    $driverTier = queryUserHasDriverID($driver_id);
    if(!$carTier)
        return "too_many_races";
    if($carTier != $race_data["tier"])
        return "wrong_car_tier";
    if(!$driverTier)
        return "wrong_driver_selected";
    if($driverTier != $race_data["tier"])
        return "wrong_driver_tier";
    //if(!queryCarTierReq($car_id))
    //  return $db_error;
    if(!queryDriverIsNotRacing($driver_id)) 
        return $db_err;
    if(!queryCarIsNotRacing($car_id))
        return $db_err;
    if(queryUserCanRace($race_id, getPlayerExp(), getPlayerSprit()) == false)
        return $db_err;
    
    return queryRacing($car_id, $race_data["id"], $race_data["dur"], $race_data["sprit_needed"], $driver_id);
}

//Rennen starten
if (isset($post['send'])) { //Abgeschicktes Formular
    $race_id = $post["race_id"];
    $car_id = $post["car_id"];
    $driver_id = $post["driver_id"];

    $race = raceNew($race_id, $car_id, $driver_id);
    if ($race == "race_started")
        $tutorial->tickOff("TUT_STATE_SPRIT");

    $info_out .= "<span class='dealInfoText $race'>";
    $info_out .= put($race, $l);
    $info_out .= "</span>";
}


$carSelect = returnCarSelect($leagueTier);
$driverSelect = returnDriverSelect($leagueTier);

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
        $tier = $race["tier"];
        $league = $race["league"];
        $type = $race["type"];


        //Sperre berechnen
        $canRace = queryUserCanRace($race["id"], getPlayerExp(), getPlayerSprit());
        $locked = "flex";
        $whyBlock = "";
        /* //ONLY FOR EXP REQ
          if ($canRace === "exp") {
          $exp_needed = levelExp($liga) * $race["exp_needed"] * getLigaQuot();
          $whyBlock = "only " . ep($exp_needed - getPlayerExp()) . " left";
          } else */
        if ($canRace === "sprit") {
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
                    
                        <form method='POST' style='display:inline-block;' action='?page=$page&sub=$sub&league=$league'>   
                        <select name='driver_id' class='select_car beautySelect nice-select-sm '>
                            " . $driverSelect . "
                        </select>
                        <select name='car_id' class='select_car beautySelect nice-select-sm '>
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






