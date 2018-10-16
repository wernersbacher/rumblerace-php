<?php
/*
 * PHP AUsgabe für Tab Menü
 */

function getMenuListTabs($current) {
    global $l, $m;
    $out = "";
    
    foreach($m as $page => $page_inf) {
        
        if ($page === $current) {
            $active = " class='sub_active'";
        } else {
            $active = "";
        }
        $out .= '<a href="main.php?page=' . $page . '"><span' . $active . '><img src="img/'.$page_inf["icon"].'.png">' . put($page, $l) . '</span></a>';
                
    }
    return $out;
}

/*
 * PHP AUsgabe für altes Menü
 */

function getMenuList() {
    global $l, $m;
    $out = '<ul>';
    foreach($m as $page => $page_inf) {
        $out .= "<span class='mainDropDown'>
                <a href='main.php?page=$page'><li><img src='img/".$page_inf["icon"].".png'>" . put($page, $l) . "</li></a>
                    
                <div class='mainDropDown-content'>";
                    
                    foreach($m[$page]["subs"] as $sub) {
                        $out .= "<a href='main.php?page=$page&sub=$sub'>" . put("s_".$sub, $l) . "</a>";
                    }

                  
                $out .= "</div> <!-- closing dropdown-->


                </span>";
        
    }

    $out .= '<span class="mainDropDown"><a href="main.php?page=logout"><li><img src="img/logout40.png">Logout</li></a></ul></span>';

    return $out;
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

$m["office"] = ["subs" => ["news", "bonus", "messages"], "icon" => "office40"];
$m["garage"] = ["subs" => ["cars", "storage"], "icon" => "car40"];
$m["factory"] = ["subs" => ["tuner", "upgrades"], "icon" => "factory"];
$m["trader"] = ["subs" => ["cardealer"], "icon" => "tools40"];
$m["drivers"] = ["subs" => ["paddock", "sysDrivers"], "icon" => "man40"];
$m["race"] = ["subs" => ["racing", "running"], "icon" => "race40"];
$m["sprit"] = ["subs" => ["produce", "sell"], "icon" => "fuel40"];
$m["market"] = ["subs" => ["partmarket", "spritmarket"], "icon" => "store40"];
//$m["special"] = ["subs" => ["upgrades"], "icon" => "special"];
$m["world"] = ["subs" => ["profiles", "chat", "globalstats"], "icon" => "world"];
//$m["options"] = ["subs" => ["settings", "faq", "newbie"], "icon" => "setting40"];

//$m["special"] = ["chat", "upgrades", "achievements", "mainstats", "globalstats"];
//$m["logout"] = [""];

//So wird ein neuer Menüpunkt hinzugefügt:
// Hier eintragen
// Datei anlegen
// in main.php hinzufügen (nur Bei übgeordnetem Punkt)
// Übersetzungen eintragen