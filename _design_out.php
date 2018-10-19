<?php

/*
 * Funktionen, die HTML etc zurückgeben
 */

function colorRarity($p) {
    if ($p < 30)
        $code = "#696969";
    //$code = "#a90329";
    else if ($p < 50)
        $code = "#00";
    else if ($p < 80)
        $code = "#148600";
    else if ($p < 100)
        $code = "#18599e";
    else if ($p == 100)
        $code = "#ec0087";
    return $code;
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
