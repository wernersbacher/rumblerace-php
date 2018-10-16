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
    global $l;
    $kats = queryTuningKats();
    //Tuning Kategorien ausgeben
    $ret = "<ul class='ligaList'>";
    foreach ($kats as $kat) {
        if ($kat == $activeKat)
            $active = "class='active'";
        else
            $active = "";
        
        $ret .= "<li $active>
                <a href='?page=factory&sub=tuner&mode=parts&kat=$kat'>
                    <img src='img/parts/$kat.png' />
                        <span class='absTitle'>".put($kat,$l)."</span>
                </a>
                </li>";
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

$running = isPartRunning();
$isPartRunningNow = $running["part"];
$time_left = $running["end"] - time();
$counter = 1;

$partsData = queryTuningPartsData($kat);
$partNames = queryTuningParts($kat);

$disabled = boolToDis(!$isPartRunningNow);

//Jede Teileklasse durchgehen
foreach ($partNames as $part) {
    $preis1 = 0;
    $worst1 = 0;
    $best1 = 0;
    $dur1 = 0;
    $labels = "";



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
    } //foreach ende

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
                                " . outputAttributesList($acc1, $speed1, $hand1, $dura1) . "
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
    } else if($time_left > 0) {
        //Gibt die Wartezeit aus, solange man bei einem anderen Teil wartet
        $output .= "<span id='tunerWaitForOther' class='hidden'>$time_left</span>";
    }
    
    $output .= "</div>
                        </div>
                    
                    <div class='tuneFooter'>
                        <form method='POST' action='?page=factory&sub=tuner&mode=parts&kat=$kat'>
                        $labels
                            <input type='hidden' name='part' value='$part'>
                            <input style='vertical-align: bottom;' class='tableTopButton saveScroll' name='send' type='submit' value='" . put("build", $l) . "' $disabled>
                        </form>
                    </div>                    
               </div>";

    $counter++;
}//foreach ende

$output .= "</div>";