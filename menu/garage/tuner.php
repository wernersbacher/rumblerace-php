<?php

//Ausgabe anfangen
$output = outputTut("tn_info", $l);
$output .= "<div id='tuner'>";

function buildNewPart($part, $liga) {
    $money = getPlayerMoney();
    $isStillRunning = isPartRunning();
    $data = queryPartData($part, $liga);
    $price = $data["preis"];
    $part_id = $data["id"];
    $dur = $data["duration"];

    if ($money >= $price && !$isStillRunning) {
        return queryPartBuy($part_id, $price, $dur);
    } else if ($isStillRunning) {
        return "too_many_parts";
    } else {
        return "no_money";
    }
}

//Tuningteil bauen
if (isset($post['send'])) { //Abgeschicktes Formular
    $part = $post["part"];
    $liga = $post["liga"];

    $build = buildNewPart($part, $liga);

    $output .= "<span class='dealInfoText $build'>";
    $output .= put($build, $l);
    $output .= "</span>";
}




if ($mode == "parts") { // Wenn eine Kategorie gewählt wurde
    if (!isset($get["kat"]))
        $kat = "";
    else
        $kat = $get["kat"];
    
    $output .= backLink("?page=garage&sub=tuner");

    $isPartRunningNow = isPartRunning();


    $partsData = queryTuningPartsData($kat);
    $partNames = queryTuningParts($kat);
    
    //Jede Teileklasse durchgehen
    foreach ($partNames as $part) {
        $preis1 = 0;
        $worst1 = 0;
        $best1 = 0;
        $dur1 = 0;
        $labels = "";

        if ($isPartRunningNow)
            $disabled = "disabled";
        else
            $disabled = "";

        //Die Ligen des Teiles durchgehen
        foreach ($partsData as $data) {
            $liga = $data["liga"];

            if ($data["part"] === $part && $liga <= getPlayerLiga() && $liga > 0) {
                $worst = $data["worst"];
                $best = $data["best"];
                $preis = dollar($data["preis"]);
                $dur = $data["duration"];

                if ($liga == 1) { //nur beim ersten element ausgabe setzen (rest JS)
                    $checked = "checked";
                    $preis1 = $preis;
                    $worst1 = $worst;
                    $best1 = $best;
                    $dur1 = $dur;
                } else {
                    $checked = "";
                }

                $labels .= "<label>
                        <input onclick='setTuneData(\"$part\", \"$preis\", \"$worst\", \"$best\", \"$dur\");' class='tuneLigas' type='radio' name='liga' value='" . $liga . "' $checked>
                        <img title='$worst - $best (PS)' style='width:23px;' src='img/liga/" . $data["liga"] . ".png'>
                    </label>";
            }
        }

        $output .= "<div class='tuner' id='$part'>
                    <div class='imgFlex'>
                        <img class='tuningImage' src='img/parts/$kat.jpg' />
                    </div>

                    <div class='tuneInfoFlex'>
                        <div class='tuneTitle'>
                            " . put($part, $l) . "
                        </div>

                        <div class='tuneInfo'> 
                            <div class='tuneDesc'>\"" . put("desc_" . $part, $l) . "\"</div>";


        //falls gerade kein Teil gebaut wird, darf alles ausgegeben werden
        $output .= "        <div class='tuneBuyDetails'>
                                <span class='tuneCost'>$preis1</span> | 
                                <span class='tuneDur'>" . formatSeconds($dur1) . "s</span> |
                                min. <span class='tuneMin'>$worst1</span> " . put("unit_" . $kat, $l) . ", 
                                max. <span class='tuneMax'>$best1</span> " . put("unit_" . $kat, $l) . "
                            </div>";

        if ($isPartRunningNow === $part) {
            $durData = queryRunningPartTime($part);
            $time_to_end = $durData["time_end"] - time();
            $duration = $durData["duration"];
            $time_went = $duration - $time_to_end;

            if ($time_went > 0)
                $width = (100 * $time_went) / $duration;
            else
                $width = 0;
            if ($width > 100)
                $width = 100;
            $output .= "    <div class='tuneProgress' data-time-duration='$duration' data-time-toend='$time_to_end'>
                                <div class='tuneProgressBar' style='width:$width%'></div> 
                                <div class='tuneProgressText'>" . $time_to_end . "s " . put("time_left", $l) . "</div>
                            </div>";
        }
        $output .= "</div>
                        </div>
                    
                    <div class='tuneFooter'>
                        <form method='POST' action='?page=garage&sub=tuner&mode=parts&kat=$kat'>
                        $labels
                            <input type='hidden' name='part' value='$part'>
                            <input style='vertical-align: bottom;' class='tableTopButton' name='send' type='submit' value='" . put("build", $l) . "' $disabled>
                        </form>
                    </div>                    
               </div>";
    }
} else { //Standardübersicht auswählen
    $katNames = queryTuningKats();

//Jede Teileklasse durchgehen
    foreach ($katNames as $kat) {

        $output .= "<div class='tuner' id='$kat'>
                    <div class='imgFlex'>
                        <img class='tuningImage' src='img/parts/" . $kat . ".jpg' />
                    </div>

                    <div class='tuneInfoFlex'>
                        <div class='tuneTitle'>
                            " . put($kat, $l) . "
                        </div>

                        <div class='tuneInfo'> 
                            <div class='tuneDesc'>\"" . put("desc_" . $kat, $l) . "\"</div>
                        </div>
                    </div>
                    
                    <div class='tuneFooter'>
                        <form method='POST' action='?page=garage&sub=tuner&mode=parts&kat=$kat'>
                            <input class='tableTopButton' name='open' type='submit' value='" . put("open_kat", $l) . "'>
                        </form>
                    </div>                    
               </div>";
    }
}

$output .= "</div>";
