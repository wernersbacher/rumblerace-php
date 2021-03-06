<?php

/*
 * Überschreiben von PHP eigenen Funktionen.
 */

/*
 * 7.2 PHP Warning Fix
 */

function __count($obj) {
    if (is_array($obj) || $obj instanceof Countable)
        return count($obj);
    else
        return false;
}

/**
 * Converts an HSL color value to RGB. Conversion formula
 * adapted from http://en.wikipedia.org/wiki/HSL_color_space.
 * Assumes h, s, and l are contained in the set [0, 1] and
 * returns r, g, and b in the set [0, 255].
 *
 * @param   {number}  h       The hue
 * @param   {number}  s       The saturation
 * @param   {number}  l       The lightness
 * @return  {Array}           The RGB representation
 */

function hue2rgb($p, $q, $t){
            if($t < 0) $t += 1;
            if($t > 1) $t -= 1;
            if($t < 1/6) return $p + ($q - $p) * 6 * $t;
            if($t < 1/2) return $q;
            if($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
            return $p;
        }
function hslToRgb($h, $s, $l){
    if($s == 0){
        $r = $l;
        $g = $l;
        $b = $l; // achromatic
    }else{
        $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
        $p = 2 * $l - $q;
        $r = hue2rgb($p, $q, $h + 1/3);
        $g = hue2rgb($p, $q, $h);
        $b = hue2rgb($p, $q, $h - 1/3);
    }

    return array(round($r * 255), round($g * 255), round($b * 255));
}
/*
 * number format
 */
    #    Output easy-to-read numbers
    #    by james at bandit.co.nz
function format($n) {
        // first strip any formatting;
        $n = (0+str_replace(",","",$n));
        
        // is this a number?
        if(!is_numeric($n)) return false;
        // now filter it;
        $string = "";
        
        if($n>1000000000000) $string = numberWithCommas(($n/1000000000000)).'TR';
        else if($n>1000000000) $string = numberWithCommas(($n/1000000000)).'B';
        else if($n>1000000) $string =  numberWithCommas(($n/1000000)).'M';
        else if($n>100000) $string =  numberWithCommas(($n/1000)).'T';
        else $string = numberWithCommas($n);
        
        return $string;
    }