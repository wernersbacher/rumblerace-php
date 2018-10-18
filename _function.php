<?php

require_once '_game_config.php';

require_once '_design_out.php';

define("LIGA_MULTI", 5);
define("LIGA_START", 400);
define("LIGA_MAX", 8);

/* Erst Diff ausrechnen
 * Gibt den Fortschritt zur nächsten Liga in % an.
 */

function getLigaDiffs($liga = -1, $exp = -1) {
    $p_liga = ($liga < 0) ? getPlayerLiga() : $liga; //only use params if available
    $player = ($exp < 0) ? getPlayerExp() : $exp;

    $new_liga = expToLiga($p_liga + 1);
    $old_liga = expToLiga($p_liga);
    $diff_liga = $new_liga - $old_liga;
    $diff_player = $new_liga - $player;

    $player_exp = $diff_liga - $diff_player;

    return ["liga" => $diff_liga, "exp" => ($player_exp >= 0) ? $player_exp : "max"];
}

function getLigaProg($liga = -1, $exp = -1) {
    $p_liga = ($liga < 0) ? getPlayerLiga() : $liga; //only use params if available
    $player = ($exp < 0) ? getPlayerExp() : $exp;

    $diffs = getLigaDiffs($liga, $exp);
    $diff_liga = $diffs["liga"];
    $diff_player = $diffs["exp"];

    if ($p_liga > LIGA_MAX)
        $diffs["prog"] = 100;
    else if ($player < 1 OR $diff_player == 'max')
        $diffs["prog"] = 0;
    else
        $diffs["prog"] = round(100 * $diff_player / $diff_liga, 2);
    //return round(100 * $diff_player / $diff_liga, 2);
    
    return $diffs;
}

function getLigaQuot() {
    return LIGA_MULTI / 4;
}

function driverUpgradeCost($liga) {
    return expToLiga($liga + 1);
}

function login($id, $username, $lang) {
    $_SESSION['user_id'] = $id;
    $_SESSION['username'] = $username;
    $_SESSION['lang'] = $lang;
}

function isPlayerGuest() {
    if (explode(":", $_SESSION['username'])[0] == "guest")
        return true;
    else
        return false;
}

function getPlayerAds() {
    global $player;
    if ($player["ads"])
        return true;
    else
        return false;
}

function getPlayerUpPoints() {
    global $player;
    return $player["uppoints"];
}

function getPlayerLiga() {
    // var_dump($player);
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
    $rand = getExpRand($min, $max);
    if ($rand < 1)
        $rand = 1;
    return $rand;
}

function getFreeGarageSlots() {
    $cars = queryPlayerCars(); // Autos auslesen
    $nowCars = __count($cars);
    $maxCars = getMaxCars();
    $left = $maxCars - $nowCars;

    return $left;
}

function getValues($array) {
    $ret_array = array();
    foreach ($array as $key => $value) {
        $rand = getExpRand(floor($value * (2 / 3)), $value);
        if ($rand < 1 && $value > 0) //Gibt immer min. 1 zurück, außer maximal ist auch 0
            $rand = 1;
        else if ($value == 0)
            $rand = 0;
        $ret_array[$key] = $rand;
    }

    return $ret_array;
}

function getMaxSprit() {
    global $_config;
    $max = $_config["vars"]["startMaxSprit"];
    $max += upgrade("sprit_max");
    return $max;
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
    return $min + $bonus;
}

function calcNewSprit() {
    $sps = calcSpritMin() / 60;
    $old = floatval(getPlayerSprit());
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

/*
 * Berechnen des Rewards Multiplikators
 * Berechnung des Fahrer-Faktors
 * pn: performance needed
 * 
 * Es wird berechnet, wie hoch der Strecken-Richtwert überhaupt ist.
 */

function calcRacePerformance($pneeded, $macc, $mspeed, $mhand, $mdura, $exp, $car_id, $driver_id) {
    global $_config;
    $car_factor = 1;
    //Get car data
    $carAttr = getCarPartsSum($car_id);
    //$car = queryPlayerCarID($car_id);
    $skill = getDriverSkill($driver_id);
    //$expf = calcExpFactor($exp, $skill);
    $driver_factor = $skill / 100; //make to dec num
    //Wenn die Boni zu groß sind, wird der Richtwert angehoben, und vice versa
    $reference_factor = ($macc + $mspeed + $mhand + $mdura) / 4;
    $pn = $reference_factor * $pneeded;

    $track_perf = $carAttr["acc"] * $macc + $carAttr["speed"] * $mspeed + $carAttr["hand"] * $mhand + $carAttr["dura"] * $mdura;

    //Check, if perf über perf needed
    if ($track_perf < $pn) {
        $car_factor = ($track_perf / $pn);
    }
    $car_weight = $_config["racing"]["carWeight"];
    $gain_factor = $car_weight * $car_factor + (1 - $car_weight) * $driver_factor; // gewichtung von Skill zu Auto
    if ($gain_factor < $_config["racing"]["minGoodness"])
        $gain_factor = 0;

    return $gain_factor * 100;
}

function calcPosition($rewardMulti) {
    $pos = ceil((101 - $rewardMulti) / 9);
    if ($pos < 1)
        $pos = 1;
    else if ($pos > 10)
        $pos = 10;
    return $pos;
}

function calcRewardMulti($pos) {
    $fac = round(((11 - $pos) / 10) ** 1.5, 2);
    return $fac;
}

/*
 * Gibt den Dollartwert zurück, den man bekommt, abhängig von den Sprit.
 * Achtung: Gibt fertigen Wert zurück, gerundet.
 */

function calcDollarReward($sprit) {
    //global $_config;
    /*
     * Noch liga mit einberechnen, sowie upgrades.
     */

    //$expo = $_config["calc"]["rewardBaseExpo"];
    //return 10 * pow($sprit, $expo);
    $dollar = 55000 / (1 + EXP(-($sprit - 500) * 0.005)) - 4269;
    return round($dollar, -1);
}

/*
 * Gibt die Summe der Fahrzeugattribute aus, vor allem für Racing nützlich
 * $id entspricht der $car_id in der DB.
 */

function getCarPartsSum($id) {
    return [
        "acc" => calcPart($id, "acc")["sum"],
        "speed" => calcPart($id, "speed")["sum"],
        "hand" => calcPart($id, "hand")["sum"],
        "dura" => calcPart($id, "dura")["sum"],
    ];
}

function outputCarPartsSumList($id) {
    $carvals = getCarPartsSum($id);
    return $carvals["acc"] . "/" . $carvals["speed"] . "/" . $carvals["hand"] . "/" . $carvals["dura"];
}

/*
 * Berechnet den Wert eines Teiles UND eines Autowertes
 */

function calcPart($id, $kind) {
    $carParts = queryPlayerPartsID($id);
    $car = queryPlayerCarID($id);
    $perf = 0;
    $counter = 0;
    $sum = 0;
    foreach ($carParts as $part) {
        //$kat = $part["kat"];
        $perf += $part[$kind];
        if ($part[$kind] > 0)
            $counter++;
    }
    if ($counter > 0)
        $perf = floor($perf / $counter);

    return ["car" => $car[$kind], "parts" => $perf, "sum" => $car[$kind] + $perf];
}

function calcAcc($id) {
    return calcPart($id, "acc");
}

function calcSpeed($id) {
    return calcPart($id, "speed");
}

function calcHand($id) {
    return calcPart($id, "hand");
}

function calcDura($id) {
    return calcPart($id, "dura");
}

function ps($ps) {
    return $ps . " " . put("hp", getPlayerLang());
}

function prf($partPrf) {
    return $partPrf . " " . put("perf", getPlayerLang());
}

//HTML Output Funktionen

function boolToDis($bool) {
    return $bool ? "" : "disabled";
}

/*
 * für eingabe true: wird angezeigt
 */

function boolToHide($bool) {
    if (!$bool)
        return " style='display:none;'";
}

function outputProfileList($list) {
    global $l;
    $out = "";

    foreach ($list as $pair)
        $out .= " <div class='profile_info'>
                    <div><b>" . put($pair["title"], $l) . "</b></div>
                    <div>" . $pair["value"] . "</div>
                </div>";
    return $out;
}

function outputAttributesList($acc1, $speedl, $handl, $dural) {
    return "<div class='stat_image_wrapper_tuner'><img src='img/stats/acc1.png' alt='Acc'/></div> <span class='tune_acc'>$acc1</span> |
                                <div class='stat_image_wrapper_tuner'><img src='img/stats/speed1.png' alt='speed'/></div> <span class='tune_speed'>$speedl</span> | 
                                <div class='stat_image_wrapper_tuner'><img src='img/stats/handling1.png' alt='hand'/></div> <span class='tune_speed'>$handl</span> |
                                <div class='stat_image_wrapper_tuner'><img src='img/stats/strength1.png' alt='str'/></div> <span class='tune_speed'>$dural</span>";
}

function outputToProcent($dec) {
    return intval($dec * 100) . "%";
}

function outputDetails($acc, $speed, $hand, $dura, $br = false) {
    if ($br)
        $umbruch = "<br/>";
    else
        $umbruch = "";

    return "A:$acc S:$speed $umbruch H:$hand D:$dura";
}

function outputTut($val, $l) {
    return "<span class='pageInfo'>" . put($val, $l) . "</span>";
}

function outputBool($bool) {
    global $l;
    if ($bool)
        return put("yes", $l);
    else
        return put("no", $l);
}

function outputRar($rar) {
    global $l;
    return "<span class='text_$rar'>" . put("rar_" . $rar, $l) . "</span>";
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

function getCurrency() {
    $save = getPlayerLangID();
    if ($save === 1) { //falls deutsch
        return "€";
    } else {
        return "$";
    }
}

function dollar($val) {
    $save = getPlayerLangID();
    $number = format($val);
    if ($save === 1) { //falls deutsch
        return $number . "€";
    } else {
        return "$" . $number;
    }
}

function ep($val) {
    if (!is_numeric($val))
        return $val;

    $save = getPlayerLangID();
    if ($save === 0) { //falls deutsch
        return numberWithCommas($val) . " EXP";
    } else {
        return numberWithCommas($val) . " EP";
    }
}

function gas($val) {
    return format($val) . " &#8467;";
}

function formatSeconds($val) {
    return date("H:i:s", $val - 3600);
}

function calcCost($base, $count) { //cost for sprit
    $pow = 1.12;
    return $base * pow($pow, $count + 1);
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
    $out = '<form id="langForm" data-lang="' . $l . '" style="display:inline-block;" method="post" action="' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] . '">
                    <input type="hidden" name="lang" value="' . $lang . '">
                    <input type="submit" value="" name="submit" class="langSubmit" style="background:url(img/' . $lang . '.png)">
                 </form>';
    return $out;
}

function randomHeader() {
    $z = date("z");
    $head = getHeaderArray();
    $key = $z % __count($head);
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

/*
 * returns skill in %, like 23
 */

function showSkill($ep) {
    global $_config;
    //return round($ep / 100, 1);
    //Neu: a*LOG(1+x)/(1+a*LOG(1+x)) verwendnen?
    return min(round($ep ** (1 / 3), 1), $_config["driver"]["maxSkill"]);
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

function sendLog() {
    
}

function queryLigaChange() {
    $liga = getPlayerLiga();
    $exp = getPlayerExp();

    $expLiga = LIGA_START;
    $newLiga = 1;

    while ($expLiga < $exp) {
        $expLiga *= LIGA_MULTI;
        $newLiga++;
    }

    if ($newLiga > $liga)
        upgradeLiga($newLiga);
}

function expToLiga($l) {
    if (intval($l) === 1)
        return 0;
    $l -= 1;
    $exp = LIGA_START;
    for ($i = 1; $i < $l; $i++) {
        $exp *= LIGA_MULTI;
    }
    return $exp;
}

function checkUsername($string) {
    return (preg_match('/[^a-zA-Z0-9_.*-]/', $string) == 0);
}

function ttc($bool) {//turn true into "checked"
    if ($bool)
        return "checked";
    else
        return "";
}

function orderUpgrades($array) {
    uasort($array, function($a, $b) {
        $res = strcmp($a['chain'], $b['chain']);

        // If the rates are the same...
        if ($res === 0) {
            // Then compare by id
            $res = $a['pre_id'] > $b['pre_id'] ? 1 : -1;
        }

        return $res;
    });
    return $array;
}

function getRandomString($length) {
    $validCharacters = "12345abcdefghijklmnopqrstuvwxyz678910";
    $validCharNumber = strlen($validCharacters);
    $result = "";
    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $validCharacters[$index];
    }
    return $result;
}

function generateRandomUsername() {
    return "guest:" . getRandomString(5);
}

function saveSession($user_id) {
    $token = GenerateRandomToken(); // generate a token, should be 128 - 256 bit
    storeLoginForUser($user_id, $token);
    $cookie = $user_id . ':' . $token;
    $mac = hash_hmac('sha256', $cookie, SECRET_KEY);
    $cookie .= ':' . $mac;
    setcookie('rememberme', $cookie, time() + 60 * 60 * 24 * 30);
}

function saveGuestDetails($user, $pw) {
    setcookie('guestpw', $user . "::" . $pw, time() + 60 * 60 * 24 * 30);
}

function isLoggedIn() {
    $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
    if ($cookie) {
        list ($user_id, $token, $mac) = explode(':', $cookie);
        if (!hash_equals(hash_hmac('sha256', $user_id . ':' . $token, SECRET_KEY), $mac)) {
            return false;
        }
        $usertoken = getTokenByUserID($user_id);
        if (is_string($usertoken) && hash_equals($usertoken, $token)) {
            return $user_id;
        } else
            return false;
    }
}

function isGuestLoggedIn() {
    $cookie = isset($_COOKIE['guestpw']) ? $_COOKIE['guestpw'] : '';
    if ($cookie) {
        list ($user, $pass) = explode('::', $cookie);
        return [$user, $pass];
    } else
        return false;
}

//Blätter Funktion, HTML Ausgabe
function getPages($menge, $s, $direct_str) {
    //Anzahl der Seiten ausgegeben
    $html = "<div class='pages'>";
    if ($menge > 1)
        for ($a = 0; $a < $menge; $a++) {
            $b = $a + 1;

            //Wenn der User sich auf dieser Seite befindet, keinen Link ausgeben 
            if ($s == $b) {
                $html .= "  <span><b>$b</b></span> ";
            }

            //Aus dieser Seite ist der User nicht, also einen Link ausgeben 
            else {
                $html .= "  <span><a href=\"$direct_str&s=$b\">$b</a></span> ";
            }
        }
    $html .= "</div>";

    return $html;
}
