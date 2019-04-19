<?php

//Ausgabe anfangen
$output = outputTut("tn_info", $l);
$output .= "<div id='tuner'>";

function buildNewPart($part, $tier) {
    $money = getPlayerMoney();
    $isStillRunning = isPartRunning()[0];
    $data = queryPartData($part, $tier);
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
    global $l, $page, $sub;
    $kats = queryTuningKats();
    //Tuning Kategorien ausgeben
    $ret = "<div class='ligaList'>";
    foreach ($kats as $kat) {
        if ($kat == $activeKat)
            $active = "class='active'";
        else
            $active = "";

        $ret .= "<a class='flex-item-list' href='?page=$page&sub=$sub&mode=parts&kat=$kat'><div $active>
                
                    <img src='img/parts/$kat.png' />
                        <span class='absTitle'>" . put($kat, $l) . "</span>
                
                </div></a>";
    }
    $ret .= "</div>";
    return $ret;
}

//Tuningteil bauen
if (isset($post['send'])) { //Abgeschicktes Formular
    $part = $post["part"];
    $tier = $post["liga"];

    $build = buildNewPart($part, $tier);

    if($build == "part_built")
        $tutorial->tickOff("TUT_STATE_PARTS");
    
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


    $i = 0;
    $playerLiga = getPlayerLiga();
    $hide_flag = true;
    //Die Ligen des Teiles durchgehen
    foreach ($partsData as $key => $data) {
        $tier = $data["tier"];
        /*
        if ($liga > getPlayerLiga())
            continue;
            */
        $hide_flag = false;

        if ($data["part"] === $part) {
            $acc = $data["acc"];
            $speed = $data["speed"];
            $hand = $data["hand"];
            $dura = $data["dura"];

            $preis = dollar($data["preis"]);
            $dur = getTuningDur($data["duration"]);

            if ($i++ == 0) { //nur beim ersten element ausgabe setzen (rest JS)
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

            $labels = "<label>
                        <input onclick='setTuneData(\"$part\", \"$preis\", \"$dur\", \"$acc\", \"$speed\", \"$hand\", \"$dura\");' class='tuneLigas' type='radio' name='liga' value='" . $tier . "' $checked>
                        " . levelImg($data["tier"]) . "
                    </label> " . $labels;
        }
    } //foreach ende
    //Check if any liga part can be showed
    if ($hide_flag)
        continue;

    $progress = "";
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
        $progress .= "    <div id='prog_$counter' class='tuneProgress' data-time-duration='$duration' data-time-toend='$time_to_end'>
                                <div class='tuneProgressBar' style='width:$width%'></div> 
                                <div class='tuneProgressText'>" . $time_to_end . "s " . put("time_left", $l) . "</div>
                            </div>";
    } else if ($time_left > 0) {
        //Gibt die Wartezeit aus, solange man bei einem anderen Teil wartet
        $progress .= "<span id='tunerWaitForOther' class='hidden'>$time_left</span>";
    }


    $output .= "<div class='tuner' id='$part'>
                    

                    <div class='tuneInfoFlex'> <!-- tuneInfoFlex150 -->
                        <div class='tuneHeader'>
                            <div class='imgFlex'>
                                <img class='tuningImage' src='img/parts/$kat.png' />
                            </div>
                            <div class='tuneTitle'>
                                " . put($part, $l) . "
                                    <div class='tuneDesc'>\"" . put("desc_" . $part, $l) . "\"</div>
                            </div>
                        </div>
                        <div class='tuneInfo'> 
                            <div class='racingDetails flex'>
                                " . racingDetail("time", formatSeconds($dur1)) . "
                                " . racingDetail("money", $preis1) . "
                            </div>
                                <div class='racingDetails flex'>

                                    " . racingDetail("acc1", $acc1) . "
                                    " . racingDetail("speed1", $speed1) . "
                                    " . racingDetail("hand1", $hand1) . "
                                    " . racingDetail("dura1", $dura1) . "
                                </div>
                        </div>
                        $progress                    
                            
                        <div class='tuneFooter'>
                            <p class='cool-text'>CHOOSE LEVEL</p>
                            <form method='POST' action='?page=$page&sub=$sub&mode=parts&kat=$kat'>
                            $labels
                                <input type='hidden' name='part' value='$part'>
                                <input style='vertical-align: bottom;' class='tableTopButton saveScroll' name='send' type='submit' value='" . put("build", $l) . "' $disabled>
                            </form>
                        </div> 

                    </div>
                    
                    
                    
               </div>";

    $counter++;
}//foreach ende

$output .= "</div>";
