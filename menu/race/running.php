<?php

if (isset($get['cancel'])) { //Abgeschicktes Formular, rennen abbrechen
    $race_id = $post["race_id"];

    $cancel = queryCancelRace($race_id);

    $info = "<span class='dealInfoText $cancel'>";
    $info .= put($cancel, $l);
    $info .= "</span>";
} else
    $info = "";

$races = queryRunningRaces();
$rennen = "";
$counter = 1;
//Rennen ausgeben
if ($races)
    foreach ($races as $race) {
        //rechne den fortschritt
        $time_to_end = $race["time_end"] - time();
        $duration = $race["duration"];
        $time_went = $duration - $time_to_end;

        if ($time_went > 0)
            $width = (100 * $time_went) / $duration;
        else
            $width = 0;
        if ($width > 100)
            $width = 100;
        //Autodaten laden
        $car = queryPlayerCarID($race["car_id"]);

        $carValueList = outputCarPartsSumList($race["car_id"]);

        //Fahrerdaten
        $driver = getDriverByID($race["driver_id"]);
        $league = $race["league"];
        $type = $race["type"];

        $rennen .= "<div class='dealer removeThis'>
                    <div class='tuneInfoFlex' style='max-width:100%;  margin-bottom:45px;'>
                        <div class='tuneTitle'>
                            " . getRaceName($league, $type) . "
                        </div>
                       
                        <div class='tuneInfo'> 
                            <div class='tuneDesc'>" . $car["title"] . " ($carValueList)
                                - " . $driver["name"] . " (" . showSkill($driver["skill"]) . "%)
                                - " . dollar($race["reward"]) . "
                            </div>
                            
                            <div id='prog_$counter' class='tuneProgress' data-time-duration='$duration' data-time-toend='$time_to_end'>
                                <div class='tuneProgressBar' style='width:$width%'></div> 
                                <div class='tuneProgressText'>" . $time_to_end . "s " . put("time_left", $l) . "</div>
                            </div>
                        </div>
                    
                    </div>
                        
                        <div class='tuneFooter'>
                    
                            <form data-dialog='Cancel race? You won't get any rewards.' method='POST' style='display:inline-block;' action='?page=race&sub=running&cancel=true'>

                                <input type='hidden' name='race_id' value='" . $race["id"] . "'>
                                <input class='tableTopButton dialog' name='send' type='submit' value='" . put("cancel", $l) . "'>
                            </form>
                        </div>
                   </div>";

        $counter++;
    } else
    $rennen = put("no_races_running", $l);



$output = outputTut("running_races", $l);


$output .= "<div id='running_races'>";
$output .= $info;
$output .= $rennen;
$output .= "</div>";
