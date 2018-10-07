<?php

//Ausgabe anfangen
$output = outputTut("tn_info", $l);
$output .= "<div id='tuner'>";

function buildNewPart($part, $liga) {
    $money = getPlayerMoney();
    $isStillRunning = isPartRunning()[0];
    $data = queryPartData($part, $liga);
    $price = $data["preis"];
    $part_id = $data["id"];
    $dur = getTuningDur($data["duration"]);

    if ($money >= $price && !$isStillRunning) {
        return queryPartBuy($part_id, $price, $dur);
    } else if ($isStillRunning) {
        return "too_many_parts";
    } else {
        return "no_money";
    }
}


/*
 * Generiert Tuning Kats Übersicht
 */
function showTunerKats($activeKat) {
    
    $kats = queryTuningKats();
    //Tuning Kategorien ausgeben
    $ret = "<ul class='ligaList'>";
    foreach ($kats as $kat) {
        if($kat == $activeKat) $active = "class='active'"; else $active = "";
        $ret .= "<li $active><a href='?page=trader&sub=tuner&mode=parts&kat=$kat'><img src='img/parts/$kat.png' /></a></li>";
    }
    $ret .= "</ul>";
    return $ret;
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


if (!isset($get["kat"]))
        $kat = "motor";
    else
        $kat = $get["kat"];
  
    $output .= showTunerKats($kat);

    $isPartRunningNow = isPartRunning()[0];
    $counter=1;

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
                $acc = $data["acc"];
                $speed = $data["speed"];
                $hand = $data["hand"];
                $dura = $data["dura"];
                
                $preis = dollar($data["preis"]);
                $dur = getTuningDur($data["duration"]);

                if ($liga == 1) { //nur beim ersten element ausgabe setzen (rest JS)
                    $checked = "checked";
                    $preis1 = $preis;
                    $dur1 = $dur;
                    
                    $acc1 = $acc;
                    $speed1 = $speed;
                    $hand1 = $hand;
                    $dura1 = $dura;
                } else {
                    $checked = "";
                }

                $labels .= "<label>
                        <input onclick='setTuneData(\"$part\", \"$preis\", \"$dur\", \"$acc\", \"$speed\", \"$hand\", \"$dura\");' class='tuneLigas' type='radio' name='liga' value='" . $liga . "' $checked>
                        <img title='Cool!' style='width:23px;' src='img/liga/" . $data["liga"] . ".png'>
                    </label>";
            }
        }

        $output .= "<div class='tuner' id='$part'>
                    <div class='imgFlex'>
                        <img class='tuningImage' src='img/parts/$kat.png' />
                    </div>

                    <div class='tuneInfoFlex tuneInfoFlex150'>
                        <div class='tuneTitle'>
                            " . put($part, $l) . "
                        </div>

                        <div class='tuneInfo'> 
                            <div class='tuneDesc'>\"" . put("desc_" . $part, $l) . "\"</div>";


        //falls gerade kein Teil gebaut wird, darf alles ausgegeben werden
        $output .= "        <div class='tuneBuyDetails'>
                                <span class='tuneCost'>$preis1</span> | 
                                <span class='tuneDur'>" . formatSeconds($dur1) . "s</span>
                            </div>";
        $output .= "        <div class='tuneBuyDetails'>
                                <div class='stat_image_wrapper_tuner'><img src='img/stats/acc1.png' alt='Acc'/></div> <span class='tune_acc'>$acc1</span> |
                                <div class='stat_image_wrapper_tuner'><img src='img/stats/speed1.png' alt='speed'/></div> <span class='tune_speed'>$speed1</span> | 
                                <div class='stat_image_wrapper_tuner'><img src='img/stats/handling1.png' alt='hand'/></div> <span class='tune_speed'>$hand1</span> |
                                <div class='stat_image_wrapper_tuner'><img src='img/stats/strength1.png' alt='str'/></div> <span class='tune_speed'>$dura1</span> 
                            </div>";

        if ($isPartRunningNow === $part) {
            $durData = queryRunningPartTime($part);
            $time_to_end = $durData["time_end"] - time();
            $duration = $durData["saved_dur"];
            $time_went = $duration - $time_to_end;

            if ($time_went > 0)
                $width = (100 * $time_went) / $duration;
            else
                $width = 0;
            if ($width > 100)
                $width = 100;
            $output .= "    <div id='prog_$counter' class='tuneProgress' data-time-duration='$duration' data-time-toend='$time_to_end'>
                                <div class='tuneProgressBar' style='width:$width%'></div> 
                                <div class='tuneProgressText'>" . $time_to_end . "s " . put("time_left", $l) . "</div>
                            </div>";
        }
        $output .= "</div>
                        </div>
                    
                    <div class='tuneFooter'>
                        <form method='POST' action='?page=trader&sub=tuner&mode=parts&kat=$kat'>
                        $labels
                            <input type='hidden' name='part' value='$part'>
                            <input style='vertical-align: bottom;' class='tableTopButton' name='send' type='submit' value='" . put("build", $l) . "' $disabled>
                        </form>
                    </div>                    
               </div>";
    
        $counter++;
        }

$output .= "</div>";
