<?php

/*
 * PHP AUsgabe für Tab Menü
 */

function getMenuListTabs($current) {
    global $l, $m, $notify;
    $out = "";

    foreach ($m as $page => $page_inf) {
        if($page_inf["main"] == false)
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
        if ($num > 0)
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
    global $l, $notify;
    //Adding Submenu for page
    $subarray = getSubMenu($page);
    $submenu = "";

    foreach ($subarray as $kat) {

        if ($sub === $kat) {
            $active = " class='sub_active'";
        } else {
            $active = " class='sub_inactive'";
        }

        $num = $notify[$kat];
        $badge = "";
        if ($num > 0)
            $badge = "<div class='tab_badge'>$num</div>";

        $submenu .= '<a href="main.php?page=' . $page . '&sub=' . $kat . '"><span' . $active . '>' . put("s_" . $kat, $l) . ' '.$badge.'</span></a>';
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

$m["office"] = ["subs" => ["secretary", "news", "bonus", "messages"], "icon" => "office40", "main" => true];
$m["garage"] = ["subs" => ["cars", "storage"], "icon" => "car40", "main" => true];
$m["factory"] = ["subs" => ["tuner", "upgrades"], "icon" => "factory", "main" => true];
$m["trader"] = ["subs" => ["cardealer"], "icon" => "tools40", "main" => true];
$m["drivers"] = ["subs" => ["paddock", "sysDrivers"], "icon" => "man40", "main" => true];
$m["race"] = ["subs" => ["racing", "running"], "icon" => "race40", "main" => true];
$m["sprit"] = ["subs" => ["produce", "sell"], "icon" => "fuel40", "main" => true];
$m["market"] = ["subs" => ["partmarket", "spritmarket"], "icon" => "store40", "main" => true];
//$m["special"] = ["subs" => ["upgrades"], "icon" => "special"];
$m["world"] = ["subs" => ["profiles", "chat", "globalstats"], "icon" => "world", "main" => true];
$m["options"] = ["subs" => ["settings", "faq", "newbie"], "icon" => "setting40", "main" => false];

//$m["special"] = ["chat", "upgrades", "achievements", "mainstats", "globalstats"];
//$m["logout"] = [""];

//So wird ein neuer Menüpunkt hinzugefügt:
// Hier eintragen
// Datei anlegen
// in main.php hinzufügen (nur Bei übgeordnetem Punkt)
// Übersetzungen eintragen