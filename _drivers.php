<?php

include("_mysql_names.php");
include_once("_game_config.php");

function getRandomLang($seed = false) {
    $driver_name_langs = [
        30 => "en",
        20 => "de",
        15 => "fr",
        5 => "ru",
        2 => "chn"
    ];


    if ($seed > 0)
        srand(mktime(0, 0, 0) + $seed);
    else
        srand();

    $max_dice = max(array_keys($driver_name_langs));

    $magic_num = rand(1, $max_dice);
    foreach (array_reverse($driver_name_langs, true) as $num => $value) {
        if ($magic_num <= $num)
            return $driver_name_langs[$num];
    }
}
/*
 * Zufälligen Namen generieren aus DB
 */

function getRandomName($first_lang, $seed) {
    global $mysqli_names, $_config;

    $last_lang = $first_lang;
    /*
     * Mit bestimmter Wsk ist der Nachname anders als der Vorname 
     */
    if (randomBool($_config["driver"]["differentLastName"], $seed)) {
        $last_lang = getRandomLang($seed + 1000);
        //console("different $first_lang $last_lang");
    }
    
    $sql = "SELECT f.name as first, l.name as last  FROM " . $first_lang . "_first f, " . $last_lang . "_last l   
        ORDER BY RAND($seed) LIMIT 1;
        ";
    $entry = mysqli_query($mysqli_names, $sql) or die($sql . "<br/>Error: " . mysqli_error($mysqli_names));

    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return $row["first"] . " " . $row["last"];
    } else {
        return "NONAME";
    }
}

class Driver {

    public $kosten;
    public $anteil;
    public $skill;
    public $name;
    public $country;
    public $maxLiga;
    public $nr;
    private static $num_instances_created = 0;

    function __construct($i) {
        $liga = 8;
        $id = $_SESSION["user_id"];
        $maxLiga = getExpRand(1, $liga, $i + $id);
        if (self::$num_instances_created++ < 1)
            $maxLiga = 1;
        $this->maxLiga = $maxLiga;
        $this->kosten = getExpRand(3000, 10000, $i + $id) * pow($this->maxLiga, 2);
        $this->anteil = 20 - getExpRand(5, 15, $i + $id);
        $this->skill = getExpRand(20, 3000, $i + $id);
        $this->id = date("dmY") . $i;
        $this->nr = $i;
        /*
         * Nationalität bestimmen, den Namen daraus ableiten
         */
        $this->country = getRandomLang($i + $id);
        $this->name = getRandomName($this->country, $i + $id);
    }

}

/*
  class Legend extends Driver {

  } */

//Erstellt 5 neue System Fahrer
for ($i = 1; $i <= $_config["driver"]["driverCnt"]; $i++) {
    $drivers[$i] = new Driver($i);
}
