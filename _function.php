<?php

function login($id, $username, $lang) {
    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['lang'] = $lang;
}

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

function getPlayerSprit() {
    global $player;
    return $player["sprit"];
}

function getPlayerLangID() {
    global $langArr;
    return $langArr[$_SESSION["lang"]];
}

function getPlayerEmail() {
    global $player;
    return $player["email"];
}

function getValue($min, $max) {
    return getExpRand($min, $max);
}

function getMaxSprit() {
    return 10000;
}

function calcSpritMin() {
    $bonus = 0.5;
    $min = 0;
    $data = querySpritUser();
    if ($data)
        foreach ($data as $teil) {
            $min += $teil["lit"] * $teil["count"];
        } else
        $min = 0;
    return $min+$bonus;
}

function calcNewSprit() {
    $sps = calcSpritMin() / 60;
    $old = getPlayerSprit();
    $last = getLastSpritUpdate();
    $now = time();
    $max = getMaxSprit();

    $sprit = ($now - $last) * $sps + $old; //Neuer Sprit wert
    if ($sprit > $max)
        $sprit = $max;
    return $sprit;
}

function getExpRand($min, $max, $seed = false) {
    if ($seed > 0)
        srand(mktime(0, 0, 0) + $seed);
    else
        srand();
    $ran = rand(0, 99999999) / 100000000;
    //$random = pow($ran,2);
    //$random = 4 * -pow($ran-0.5,3) + 0.5; //4*-(x-0.5)^(3)+0.5 mittelverteilt
    $random = 3.3 * -pow($ran - 0.6, 3) + 0.3; //3.3*-(x-0.6)^(3)+0.3 weiter links/mittel

    return (int) floor($min - 1 + ($max - $min + 1) * $random);
}

function getDriverSkill($driver_id) {
    $driver = getDriverByID($driver_id);

    return showSkill($driver["skill"]);
}

function calcExpFactor($race, $skill) {
    $pure = ($skill * 2) / $race;
    $added = (3 + $pure) / 4;
    if ($added > 1)
        $added = 1;
    return $added;
}

function calcReward($reward, $psReward, $exp, $car_id, $driver_id) {
    $gain = 0;
    //Get car data
    $car = queryPlayerCarID($car_id);
    $partPs = calcPS($car_id);
    $carPs = $car["ps"];
    $ps = $carPs + $partPs;
    $skill = getDriverSkill($driver_id);

    $expf = calcExpFactor($exp, $skill);

    if ($ps >= $psReward) {
        $gain = $reward;
    } else {
        //$reward = (($ps+($psReward-$ps)/2)/100) * $reward;
        $gain = ($ps / 100) * $reward;
    }

    $gain *= $expf; //If good driver, max, if not, less rewards

    return $gain;
}

function calcExpReward($exp, $psReward, $car_id, $driver_id) {
    $gain = 0;
    $car = queryPlayerCarID($car_id);
    $partPrf = calcPerf($car_id);
    $per = $car["perf"] + $partPrf;

    $skill = getDriverSkill($driver_id);
    $expf = calcExpFactor($exp, $skill);

    if ($per >= $psReward) {
        $gain = $exp;
    } else {
        //$reward = (($ps+($psReward-$ps)/2)/100) * $reward;
        $gain = ($per / 100) * $exp;
    }

    $gain *= $expf; //If good driver, max, if not, less rewards

    return $gain;
}

function calcPS($id) {
    $carParts = queryPlayerPartsID($id);
    $ps = 0;
    foreach ($carParts as $part) {
        $kat = $part["kat"];
        if ($kat == "motor" OR $kat == "auspuff" OR $kat == "turbo") {
            $ps += $part["value"];
        }
    }
    return $ps;
}

function calcPerf($id) {
    $carParts = queryPlayerPartsID($id);
    $perf = 0;
    foreach ($carParts as $part) {
        $kat = $part["kat"];
        if ($kat == "bremse" OR $kat == "schaltung") {
            $perf += $part["value"];
        }
    }
    return $perf;
}

function ps($ps) {
    return $ps . " " . put("hp", getPlayerLang());
}

function prf($partPrf) {
    return $partPrf . " " . put("perf", getPlayerLang());
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

function nwc($x) {
    return numberWithCommas($x);
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

function gas($val) {
    return $val." &#8467;";
}

function formatSeconds($val) {
    return date("H:i:s", $val - 3600);
}

function calcCost($base, $count) {
    $pow = 1.12;
    return $base * pow($count + 1, $pow);
}

function backLink($link) {
    return "<div class='backLink'><a href='$link'>&#8678; " . put("back_overview", getPlayerLang()) . "</a></div>";
}

function user($id, $name) {
    return "<a href='#$id' class='brightLink'>$name</a>";
}

function getLangChange() {
    global $l;
    if ($l == "en") {
        $lang = "de";
    } else {
        $lang = "en";
    }
    $out = '<form id="langForm" data-lang="'.$l.'" style="display:inline-block;" method="post" action="' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '">
                    <input type="hidden" name="lang" value="' . $lang . '">
                    <input type="submit" value="" name="submit" class="langSubmit" style="background:url(img/' . $lang . '.png)">
                 </form>';
    return $out;
}

function randomHeader() {
    $z = date("z");
    $head = getHeaderArray();
    $key = $z % count($head);
    return $head[$key];
}

function getRaceName($name) {
    global $l;
    $exp = explode("_", $name);
    return put($exp[0], $l) . " " . put($exp[1], $l);
}

function calcSkillGain($liga, $ep) {
    return $ep / (pow($liga, 1.7) * 100);
}

function showSkill($ep) {
    return round($ep / 100, 1);
}

function isValid($str) {
    return !preg_match('/[^A-Za-z0-9 .#\\-$]/', $str);
}

function generateToken($now, $email) {

    $SALT = "Ende aus, Mickey Maus";
    $md5 = md5($SALT . $now . $email);

    return $md5;
}

function sendMail($empfaenger, $betreff, $inhalt) {
    mail($empfaenger, $betreff, $inhalt, "noreply@facethepace.com");
}

define("LIGA_MULTI", 4);
define("LIGA_START", 300);

function queryLigaChange() {
    $liga = getPlayerLiga();
    $exp = getPlayerExp();
    
    $expLiga = LIGA_START;
    $newLiga = 1;
    
    while($expLiga < $exp) {
        $expLiga *= LIGA_MULTI;
        $newLiga++;
    }
    
    if($newLiga > $liga)
            upgradeLiga($newLiga);
}

function expToLiga($l) {
    
    $exp = LIGA_START;
    for($i=1; $i<$l; $i++) {
        $exp *= LIGA_MULTI;
    }
    return $exp;
}
