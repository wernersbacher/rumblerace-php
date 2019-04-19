<?php

/*
 * PHP AUsgabe für Tab Menü
 */

function getMenuListTabs($current) {
    global $l, $m, $notify, $tutorial;
    $out = "";

    foreach ($m as $page => $page_inf) {
        if ($page_inf["main"] == false)
            continue;

        if (!$tutorial->isAtState($page_inf["minTutorial"]))
            continue;

        //active showing
        if ($page === $current) {
            $active = " class='sub_active'";
        } else {
            $active = " class='sub_inactive'";
        }

        //notify showing
        $num = $notify[$page];
        $badge = "";
        if ($num > 0 AND $tutorial->isDone())
            $badge = "<div class='tab_badge'>$num</div>";

        $out .= '<a href="main.php?page=' . $page . '"><span' . $active . '><img src="img/' . $page_inf["icon"] . '.png">' . put($page, $l) . ' ' . $badge . '</span></a>';
    }
    return $out;
}

/*
 * PHP AUsgabe für altes Menü
 */

function getMenuList() {
    global $l, $m;
    $out = '<ul>';
    foreach ($m as $page => $page_inf) {
        $out .= "<span class='mainDropDown'>
                <a href='main.php?page=$page'><li><img src='img/" . $page_inf["icon"] . ".png'>" . put($page, $l) . "</li></a>
                    
                <div class='mainDropDown-content'>";

        foreach ($m[$page]["subs"] as $sub) {
            $out .= "<a href='main.php?page=$page&sub=$sub'>" . put("s_" . $sub, $l) . "</a>";
        }


        $out .= "</div> <!-- closing dropdown-->


                </span>";
    }

    $out .= '<span class="mainDropDown"><a href="main.php?page=logout"><li><img src="img/logout40.png">Logout</li></a></ul></span>';

    return $out;
}

function outputSubmenu($page, $sub) {
    global $l, $notify, $tutorial;
    //Adding Submenu for page
    $subarray = getSubMenu($page);
    $submenu = "";

    foreach ($subarray as $kat) {

        if (!$tutorial->isAtState(getSubReq($kat)))
            continue;

        if ($sub === $kat) {
            $active = " class='sub_active'";
        } else {
            $active = " class='sub_inactive'";
        }

        $num = $notify[$kat];
        $badge = "";
        if ($num > 0)
            $badge = "<div class='tab_badge'>$num</div>";

        $submenu .= '<a href="main.php?page=' . $page . '&sub=' . $kat . '"><span' . $active . '>' . put("s_" . $kat, $l) . ' ' . $badge . '</span></a>';
    }
    return $submenu;
}

function getSubMenu($page) {
    global $m;
    if (array_key_exists($page, $m)) { //Falls Seite eine Unterseite hat
        return $m[$page]["subs"];
    }
}

function getFirstSubmenu($page) {
    global $m;
    if (array_key_exists($page, $m)) { //Falls Seite eine Unterseite hat
        return $m[$page]["subs"][0];
    } else {
        return "";
    }
}

//Untermenü hinzufügen - nicht vergessen in _lang.php einzutragen!

$m = array();

$m["office"] = ["subs" =>
    ["secretary", "news", "bonus", "messages"],
    "icon" => "office40", "main" => true, "minTutorial" => ""];

$m["garage"] = ["subs" =>
    ["cars", "storage"],
    "icon" => "car40", "main" => true, "minTutorial" => "TUT_STATE_STORAGE"];

$m["factory"] = ["subs" =>
    ["tuner", "upgrades"],
    "icon" => "factory", "main" => true, "minTutorial" => "TUT_STATE_PARTS"];

$m["trader"] = ["subs" =>
    ["cardealer", "partmarket"],
    "icon" => "tools40", "main" => true, "minTutorial" => ""];

$m["drivers"] = ["subs" =>
    ["paddock", "sysDrivers", "drivermarket"],
    "icon" => "man40", "main" => true, "minTutorial" => "TUT_STATE_BUYDRIVER"];

$m["race"] = ["subs" =>
    ["racing", "running"],
    "icon" => "race40", "main" => true, "minTutorial" => "TUT_STATE_DRIVE"];

$m["sprit"] = ["subs" =>
    ["produce", "spritmarket", "sell"],
    "icon" => "fuel40", "main" => true, "minTutorial" => "TUT_STATE_SPRIT"];

//$m["market"] = ["subs" => ["partmarket", "spritmarket"], "icon" => "store40", "main" => true];
//$m["special"] = ["subs" => ["upgrades"], "icon" => "special"];

$m["world"] = ["subs" =>
    ["profiles", "chat", "globalstats"],
    "icon" => "world", "main" => true, "minTutorial" => "TUT_STATE_END"];

$m["options"] = ["subs" =>
    ["settings", "faq", "newbie"],
    "icon" => "setting40", "main" => false, "minTutorial" => ""];

function getMainReq($page) {
    global $m;
    return $m[$page]["minTutorial"];
}

function getSubReq($sub) {
    $subReq = [
        "partmarket" => "TUT_STATE_END",
        "spritmarket" => "TUT_STATE_END",
        "sell" => "TUT_STATE_END",
        "bonus" => "TUT_STATE_END",
        "messages" => "TUT_STATE_END"
    ];
    if (array_key_exists($sub, $subReq))
        return $subReq[$sub];
    else
        return "";
}

//$m["special"] = ["chat", "upgrades", "achievements", "mainstats", "globalstats"];
//$m["logout"] = [""];

//So wird ein neuer Menüpunkt hinzugefügt:
// Hier eintragen
// Datei anlegen
// in main.php hinzufügen (nur Bei übgeordnetem Punkt)
// Übersetzungen eintragen