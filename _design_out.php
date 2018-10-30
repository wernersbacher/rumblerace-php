<?php

/*
 * Funktionen, die HTML etc zurückgeben
 */

function calcRarity($p) {
    $name = "";
    if ($p < 30)
        $name = "bad";
    else if ($p < 50)
        $name = "average";
    else if ($p < 80)
        $name = "good";
    else if ($p < 100)
        $name = "rare";
    else if ($p == 100)
        $name = "legendary";
    return $name;
}


function colorRarity($p) {
    if ($p < 30)
        $obj = ["color" => "#696969", "name" => "bad"];
    else if ($p < 50)
        $obj = ["color" => "#00", "name" => "average"];
    else if ($p < 80)
        $obj = ["color" => "#148600", "name" => "good"];
    else if ($p < 100)
        $obj = ["color" => "#18599e", "name" => "rare"];
    else if ($p == 100)
        $obj = ["color" => "#ec0087", "name" => "legendary"];
    return $obj;
}

function colorFromPercent($i) {
    $hue = $i * 1.2 / 360;
    $rgb = hslToRgb($hue, 1, .5);

    return "rgb(" . $rgb[0] . "," . $rgb[1] . "," . $rgb[2] . ")";
}

function outputLevelProgress($liga = -1, $exp = -1) {
    $prog = htmlLevelprogress($liga, $exp);

    echo $prog["html"];
    return $prog["prog"];
}

//generiert den HTML Output und den Fortschritt
function htmlLevelprogress($liga = -1, $exp = -1) {
    $p_liga = ($liga < 0) ? getPlayerLiga() : $liga;
    static $count = 1;
    $prog = getLigaProg($liga, $exp);
    $p_prog = $prog["prog"];
    //$diffs = getLigaDiffs($liga, $exp);

    $anteil = ep($prog["exp"]) . "/" . ep($prog["liga"]);

    $html = "<div title='$anteil' id='prog_exp-" . $count++ . "' class='smallProgress tuneProgress noJS'>
                                          <div class='tuneProgressBar' style='width:$p_prog%; background-color: " . colorFromPercent($p_prog) . "' ></div> 
                                          <div class='tuneProgressText'>Level $p_liga</div>
                                     </div>";

    //Gibt den absoluten Fortschritt zurück

    $obj = ["html" => $html, "prog" => $prog["exp"]];
    return $obj;
}

function formatLevelColor($lvl) {
    return "<span class='lvlOutput lvl_$lvl'>LEVEL$lvl</span>";
}

function levelImg($l, $big = false) {
    $big_img = boolToParam($big, " liga-big");
    return "<div class='liga_img$big_img'>$l</div>";
}

function racingDetail($type, $value) {
    return "<div class='stat_wrapper'><div class='stat_image_wrapper_tuner'><img src='img/stats/$type.png' alt='$type'/></div> <span class='tune_$type'>$value</span></div> ";
}