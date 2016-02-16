<?php

function getPlayerLiga() {
    global $player;
    return $player["liga"];
}

function getPlayerMoney() {
    global $player;
    return $player["money"];
}

function getPlayerLang() {
    return $_SESSION["lang"];
}

function getPlayerExp() {
    global $player;
    return $player["exp"];
}

function getPlayerLangID() {
    global $langArr;
    return $langArr[$_SESSION["lang"]];
}

function getValue($min, $max) {
    return rand($min, $max);
}

function calcPS($id) {
    $carParts = queryPlayerPartsID($id);
    $ps = 0;
    foreach($carParts as $part) {
        $kat = $part["kat"];
        if($kat == "motor" OR $kat == "auspuff" OR $kat == "turbo") {
            $ps += $part["value"];
        }    
    }
    return $ps;
}

function calcPerf($id) {
    $carParts = queryPlayerPartsID($id);
    $perf = 0;
    foreach($carParts as $part) {
        $kat = $part["kat"];
        if($kat == "bremse" OR $kat == "schaltung") {
            $perf += $part["value"];
        }    
    }
    return $perf;
}

function ps($ps) {
    return $ps ." ". put("hp", getPlayerLang());
}
function prf($partPrf) {
    return $partPrf ." ". put("perf", getPlayerLang());
}

//HTML Output Funktionen

function outputTut($val, $l) {
    return "<span class='pageInfo'>" . put($val, $l) . "</span>";
}

function numberWithCommas($val) {

    $komma = ".";
    $tausend = ",";
    $save = getPlayerLangID();
    if ($save === 0) { //falls deutsch
        $komma = ",";
        $tausend = ".";
    }
    return number_format($val, 2, $tausend, $komma);
}

function dollar($val) {
    $save = getPlayerLangID();
    if ($save === 1) { //falls deutsch
        return numberWithCommas($val) . "€";
    } else {
        return "$" . numberWithCommas($val);
    }
}

function ep($val) {
    $save = getPlayerLangID();
    if ($save === 0) { //falls deutsch
        return numberWithCommas($val) . " EXP";
    } else {
        return numberWithCommas($val) . " EP";
    }
}

function formatSeconds($val) {
    return date("H:i:s", $val-3600);
}

function backLink($link) {
    return "<div class='backLink'><a href='$link'>&#8678; " . put("back_overview", getPlayerLang()) . "</a></div>";
}

function user($id, $name) {
    return "<a href='#$id' class='brightLink'>$name</a>";
}