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

$m["office"] = ["news", "bonus", "messages"];
$m["garage"] = ["cars", "storage", "items"];
$m["trader"] = [ "tuner", "cardealer"];
$m["drivers"] = ["paddock","sysDrivers"];
$m["sprit"] = ["produce", "sell"];
$m["market"] = ["partmarket", "spritmarket"];
$m["race"] = ["racing", "endurance", "running"];
$m["options"] = ["settings"];
$m["help"] = ["faq", "newbie"];
//$m["special"] = ["chat", "upgrades", "achievements", "mainstats", "globalstats"];
$m["special"] = ["quests", "upgrades", "chat", "globalstats"];
//$m["logout"] = [""];

//So wird ein neuer Menüpunkt hinzugefügt:
// Hier eintragen
// Datei anlegen
// in main.php hinzufügen (nur Bei übgeordnetem Punkt)
// Übersetzungen eintragen