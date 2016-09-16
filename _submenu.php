<?php

function getSubMenu($page) {
    global $m;
    if(array_key_exists($page, $m)) { //Falls Seite eine Unterseite hat
        return $m[$page];
    }
}

function getFirstSubmenu($page) {
    global $m;
    if(array_key_exists($page, $m)) { //Falls Seite eine Unterseite hat
        return $m[$page][0];
    } else {
        return "";
    }
}

//Untermenü hinzufügen - nicht vergessen in _lang.php einzutragen!

$m = array();

$m["office"] = ["news", "messages"];
$m["garage"] = ["cars", "tuner", "storage", "cardealer"];
$m["drivers"] = ["paddock","sysDrivers"];
$m["sprit"] = ["produce", "buy"];
$m["market"] = ["partmarket", "carmarket"];
$m["race"] = ["racing", "endurance", "running"];
$m["options"] = ["settings"];
$m["help"] = ["faq", "newbie"];
$m["special"] = ["upgrades", "achievements", "mainstats"];
//$m["logout"] = [""];

//So wird ein neuer Menüpunkt hinzugefügt:
// Hier eintragen
// Datei anlegen
// in main.php hinzufügen (Bei übgeordnetem Punkt)
// Übersetzungen eintragen