<?php

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
    /*
    $out = '<ul>
            <a href="main.php"><li><img src="img/office40.png"><?php echo put("office", $l) ?></li></a>
            <a href="main.php?page=garage"><li><img src="img/car40.png">Home</li></a>
            <a href="main.php?page=trader"><li><img src="img/tools40.png">' . put("trader", $l) . '</li></a>
            <a href="main.php?page=drivers"><li><img src="img/man40.png"><?php echo put("drivers", $l) ?></li></a>
            <a href="main.php?page=race"><li><img src="img/race40.png"><?php echo put("race", $l) ?></li></a>
            <a href="main.php?page=sprit"><li><img src="img/fuel40.png"><?php echo put("sprit", $l) ?></li></a>
            <a href="main.php?page=market"><li><img src="img/store40.png"><?php echo put("market", $l) ?></li></a>
            <a href="main.php?page=special"><li><img src="img/special.png"><?php echo put("special", $l) ?></li></a>
            <a href="main.php?page=options"><li><img src="img/setting40.png"><?php echo put("options", $l) ?></li></a>
            <a href="main.php?page=help"><li><img src="img/help40.png"><?php echo put("help", $l) ?></li></a>
            <a href="main.php?page=logout"><li><img src="img/logout40.png">Logout</li></a>
            

        </ul>';*/

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
$m["garage"] = ["subs" => ["cars", "storage", "items"], "icon" => "car40"];
$m["trader"] = ["subs" => ["tuner", "cardealer"], "icon" => "tools40"];
$m["drivers"] = ["subs" => ["paddock", "sysDrivers"], "icon" => "man40"];
$m["race"] = ["subs" => ["racing", "endurance", "running"], "icon" => "race40"];
$m["sprit"] = ["subs" => ["produce", "sell"], "icon" => "fuel40"];
$m["market"] = ["subs" => ["partmarket", "spritmarket"], "icon" => "store40"];
$m["special"] = ["subs" => ["upgrades", "chat", "globalstats"], "icon" => "special"];
$m["options"] = ["subs" => ["settings"], "icon" => "setting40"];
$m["help"] = ["subs" => ["faq", "newbie"], "icon" => "help40"];

//$m["special"] = ["chat", "upgrades", "achievements", "mainstats", "globalstats"];
//$m["logout"] = [""];

//So wird ein neuer Menüpunkt hinzugefügt:
// Hier eintragen
// Datei anlegen
// in main.php hinzufügen (nur Bei übgeordnetem Punkt)
// Übersetzungen eintragen