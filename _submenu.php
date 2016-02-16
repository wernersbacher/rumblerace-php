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
$m["market"] = ["partmarket", "carmarket"];
$m["race"] = ["racing", "endurance"];
$m["stats"] = ["mainstats"];
$m["options"] = ["settings"];
$m["help"] = ["newbie", "faq"];
$m["special"] = ["upgrades", "achievements"];
//$m["logout"] = [""];

//So wird ein neuer Menüpunkt hinzugefügt:
// Hier eintragen
// Datei anlegen
// in main.php hinzufügen
// Übersetzungen eintragen