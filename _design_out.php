<?php

/* 
 * Funktionen, die HTML etc zurückgeben
 */

function colorFromPercent($i) {
    $hue = $i * 1.2/360;
    $rgb = hslToRgb($hue, 1, .5);
    
    return "rgb(".$rgb[0].",".$rgb[1].",".$rgb[2].")";
}