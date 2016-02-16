<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$get = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

//Check if logged in
require_once('_checkuser.php');
require_once('_function.php');
require_once('_mysql.php');
require_once('_lang.php');
require_once('_submenu.php');

//Language changer
if (isset($post["lang"])) {
    $lang = $post["lang"];
    if ($lang == "en" OR $lang == "de") {
        $update = queryLangChange($lang);
    }
}

//Hier noch User stats holen, wie money, liga etc.
//Spieler stats und Sprache holen
$player = queryPlayerStats();
$l = getPlayerLang();


//Holen der aktuellen Seite

$page = $get["page"];

if (!$page) {
    $page = "office";
}

if (!isset($get["sub"]))
    $sub = getFirstSubmenu($page);
else
    $sub = $get["sub"];

if (!isset($get["mode"]))
    $mode = "";
else
    $mode = $get["mode"];

//Überprüfen und Verschieben der Teile
queryPartsBuildingDone();
//Überprüfe der Rennen 
queryRaceDone();
//Überprüfen om Liga Aufstieg
if(getPlayerLiga() <8)
    queryLigaChange();


//Ausgeben der aktuellen Seite
$inc = "menu/$page/$sub.php";
if (file_exists($inc)) {
    include($inc);
} else if ($page === "logout") {
    $output = "<div class='textCenter'>If you really want to logout, click the button below.<br/>";
    $output .= '<form action="logout.php">
                        <input type="submit" value="Logout">
                    </form></div>';
} else {
    include("menu/404.php");
}

$content = $output;
//Load new any maybe changed player data for output
$player = queryPlayerStats();

//Adding Submenu for page
$subarray = getSubMenu($page);
$submenu = "";
if ($subarray) {
    $submenu = '<div id="submenu">
                    <ul>';
    foreach ($subarray as $value) {
        if ($sub === $value) {
            $active = " class='sub_active'";
        } else {
            $active = "";
        }
        $submenu .= '<a href="main.php?page=' . $page . '&sub=' . $value . '"><li' . $active . '>' . put("s_" . $value, $l) . '</li></a>';
    }
    $submenu .= '</ul>
                </div>';
}

//Überschrift Mittelpunkt (falls untersiete aktiv)
if ($sub)
    $subpage = " &middot; " . put("s_" . $sub, $l);
else
    $subpage = "";

//Neue Nachrichten Symbol
$newFx = areThereMessenges();
if($newFx)
    $letter = "letter_new";
else
    $letter = "letter";
?>
<!DOCTYPE html>
<!--
Sources:
https://www.flickr.com/photos/aigle_dore/5952275132/
https://www.iconfinder.com/icons/67528/camaro_car_sports_car_icon#size=128
https://www.iconfinder.com/icons/532794/building_ecommerce_house_market_marketplace_shop_shopping_store_icon#size=512
https://www.iconfinder.com/icons/299053/computer_icon#size=512
https://www.iconfinder.com/icons/27879/cog_gear_settings_icon#size=128
https://www.iconfinder.com/icons/6035/exit_icon#size=128
https://www.iconfinder.com/icons/172465/finish_flag_goal_icon#size=128
https://www.iconfinder.com/icons/131481/boy_guy_male_man_men_play_power_spiderman_super_man_superman_icon#size=128
https://www.flickr.com/photos/131101324@N06/21256220346/
https://www.flickr.com/photos/dryheatpanzer/7077143377/
https://www.flickr.com/photos/stradablog/8066643385/
https://www.flickr.com/photos/gfreeman23/8623429560/in/photostream/
https://www.flickr.com/photos/spacemunkie/4104717985/
-->
<html>
    <head>
        <title><?php echo put($page, $l) ?> | RumbleRace</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="src/font.css">
        <link rel="stylesheet" href="default.css">
        <link rel="stylesheet" href="pages.css">
        <link rel="stylesheet" href="pages.colors.css">
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="backstretch.min.js"></script>
        <script type="text/javascript" src="gui.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300' rel='stylesheet' type='text/css'> 
    </head>
    <body>

        <!-- Top Segment Anfang -->
        <div id="topBar">
            <div id="topContent">
                <span class="rumblerace">RUMBLE <span class="colored">RACE</span></span>
                <span id="blackMenu">today on rumblerace: how to drive fast</span>
            </div>
        </div>
        <!-- Top Segment Ende -->

        <!-- Main Menü Segment Anfang -->

        <div id="mainmenu">
            <ul>
                <a href="main.php"><li><img src="img/office40.png"><?php echo put("office", $l) ?></li></a>
                <a href="main.php?page=race"><li><img src="img/race40.png"><?php echo put("race", $l) ?></li></a>
                <a href="main.php?page=garage"><li><img src="img/car40.png">Garage</li></a>
                <a href="main.php?page=market"><li><img src="img/store40.png"><?php echo put("market", $l) ?></li></a>
                <a href="main.php?page=special"><li><img src="img/special.png">Special</li></a>
                <a href="main.php?page=stats"><li><img src="img/stats40.png">Stats</li></a>
                <a href="main.php?page=options"><li><img src="img/setting40.png"><?php echo put("options", $l) ?></li></a>
                <a href="main.php?page=help"><li><img src="img/help40.png"><?php echo put("help", $l) ?></li></a>
                <a href="main.php?page=logout"><li><img src="img/logout40.png">Logout</li></a>
            </ul>
        </div>

        <!-- Main Menü Segment Ende -->

        <!-- Middle Segment Anfang -->

        <div id="middle">
            <div id="leftMenu">
                <div id="player-info">
                    <span class="playername"><?php echo $_SESSION["username"] ?></span><br/>
                    <?php echo dollar(getPlayerMoney()) ?><br/>
                    <?php echo ep(getPlayerExp()) ?><br/>
                    <div class="playerQuick"  style="margin-top: 4px;">
                        <a href="?page=office&sub=messages"><img src="img/<?php echo $letter ?>.png" alt="messages" /></a></a>
                    </div>
                    <div class="playerLiga">
                        <img src="img/liga/<?php echo getPlayerLiga() ?>.png" />
                    </div>
                </div>

                <?php echo $submenu ?>

            </div>

            <div id="contentWindow">
                <span class="h1"><?php echo put($page, $l) . $subpage ?> </span>
                <?php echo $content ?>
            </div>
        </div>

        <!-- Middle Segment Ende -->


        <!-- Footer Segment Anfang -->

        <?php include("_footer.php") ?>

        <!-- Footer Segment Ende -->

    </body>
</html>
