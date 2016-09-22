<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$get = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$post = filter_input_array(INPUT_POST);

//Check if logged in
require_once('_data.php');
require_once('_checkuser.php');
require_once('_function.php');
require_once('_mysql.php');
require_once('_upgrades.php');
require_once('_lang.php');
require_once('_submenu.php');
require_once('_ads.php');

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
$upgrades = getUserUpgrades();
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

//Überprüfen und Verschieben der Tuning Teile
queryPartsBuildingDone();
//Überprüfe der Rennen 
queryRaceDone();
//Sprit hinzufügen
querySpritAdd();
//Überprüfen om Liga Aufstieg
if (getPlayerLiga() < 8)
    queryLigaChange();


//Ausgeben der aktuellen Seite
$inc = "menu/$page/$sub.php";
if (file_exists($inc)) {
    include($inc);
} else if ($page === "logout") {
    $output = "<div class='textCenter'>" . put("real_logout", $l) . "<br/>";
    $output .= '<form action="logout.php">
                        <input class="tableTopButton" type="submit" value="Logout">
                    </form></div>';
} else {
    include("menu/404.php");
}

$content = $output;
//Load new any maybe changed player data for output
$player = queryPlayerStats();

//Anzeigen des Spritstatistik
$spm = calcSpritMin();
$max = getMaxSprit();
$sprit = "<span title='" . gas(nwc($spm / 60)) . "/sec' id='spritTags' data-spritmax='$max' data-promin='$spm'><span id='playerSprit'>" . gas(getPlayerSprit()) . "</span>/" . gas(nwc($max)) . "</span>";

//Adding Submenu for page
$subarray = getSubMenu($page);
$submenu = "";
if ($subarray) {
    $submenu = '<ul>';
    foreach ($subarray as $value) {
        if ($sub === $value) {
            $active = " class='sub_active'";
        } else {
            $active = "";
        }
        $submenu .= '<a href="main.php?page=' . $page . '&sub=' . $value . '"><li' . $active . '>' . put("s_" . $value, $l) . '</li></a>';
    }
    $submenu .= '</ul>';
}

//Überschrift Mittelpunkt (falls untersiete aktiv)
if ($sub)
    $subpage = " &middot; " . put("s_" . $sub, $l);
else
    $subpage = "";

//Neue Nachrichten Symbol
$newFx = areThereMessenges();
if ($newFx)
    $letter = "letter_new";
else
    $letter = "letter";

//Bonus bereit?
if (isThereBonus()) {
    $bonus = "bonus_new";
} else
    $bonus = "bonus";
?>
<!DOCTYPE html>
<!--
Hello and welcome my friend.
I hope you don't have any problems with your game session!

The game is server-sided. So no hacking or exporting savegame :)

Thanks for playing! 
ALSO VISTIT:
http://bitcoinergame.com
http://wernersbacher.de

-->
<html>
    <head>
        <title><?php echo put($page, $l) ?> | Racing Inc</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="src/font.css">
        <link rel="stylesheet" href="default.css">
        <link rel="stylesheet" href="pages.css">
        <link rel="stylesheet" href="pages.colors.css">
        <link rel="stylesheet" href="lib/jquery-ui.css">
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="backstretch.min.js"></script>
        <script type="text/javascript" src="lib/jquery-ui.min.js"></script>
        <script type="text/javascript" src="lib/form.min.js"></script>
        <script type="text/javascript" src="lib/date.js"></script>
        <script type="text/javascript" src="cookie.js"></script>
        <script type="text/javascript" src="gui.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300' rel='stylesheet' type='text/css'> 
        <link rel="shortcut icon" type="image/x-icon" href="img/logo16.ico">
        <!-- Piwik -->
        <script type="text/javascript">
            var _paq = _paq || [];
            _paq.push(["setCookieDomain", "*.facethepace.com"]);
            _paq.push(["setDomains", ["*.facethepace.com", "*.www.facethepace.com"]]);
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function () {
                var u = "//wernersbacher.de/analyse/";
                _paq.push(['setTrackerUrl', u + 'piwik.php']);
                _paq.push(['setSiteId', '3']);
                var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
                g.type = 'text/javascript';
                g.async = true;
                g.defer = true;
                g.src = u + 'piwik.js';
                s.parentNode.insertBefore(g, s);
            })();
        </script>
    <noscript><p><img src="//wernersbacher.de/analyse/piwik.php?idsite=3" style="border:0;" alt="" /></p></noscript>
    <!-- End Piwik Code -->
</head>
<body>

    <!-- Top Segment Anfang -->
    <div id="topBar">
        <div id="topContent">
            <a href='main.php'><span class="rumblerace">RACING <span class="colored">INC.</span></span></a>
            <span id="blackMenu"><?php echo randomHeader(); ?></span>
        </div>
    </div>
    <!-- Top Segment Ende -->

    <!-- Main Menü Segment Anfang -->

    <div id="mainmenu">
        <ul>
            <a href="main.php"><li><img src="img/office40.png"><?php echo put("office", $l) ?></li></a>
            <a href="main.php?page=garage"><li><img src="img/car40.png">Garage</li></a>
            <a href="main.php?page=tuner"><li><img src="img/tools40.png">Tuner</li></a>
            <a href="main.php?page=drivers"><li><img src="img/man40.png"><?php echo put("drivers", $l) ?></li></a>
            <a href="main.php?page=race"><li><img src="img/race40.png"><?php echo put("race", $l) ?></li></a>
            <a href="main.php?page=sprit"><li><img src="img/fuel40.png"><?php echo put("sprit", $l) ?></li></a>
            <a href="main.php?page=market"><li><img src="img/store40.png"><?php echo put("market", $l) ?></li></a>
            <a href="main.php?page=special"><li><img src="img/special.png"><?php echo put("special", $l) ?></li></a>
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
                <span class="stats">
                    <img src="img/dollar.png" /> <?php echo dollar(getPlayerMoney()) ?><br/>
                    <img src="img/star.png" /> <span title="<?php echo ep(expToLiga(getPlayerLiga() + 1) - getPlayerExp()) ?> left"><?php echo ep(getPlayerExp()) ?></span><br/>
                    <img src="img/energy.png" /> <?php echo $sprit ?><br/>
                </span>
                <div class="playerQuick"  style="margin-top: 4px;">
                    <a href="?page=office&sub=messages"><img src="img/<?php echo $letter ?>.png" alt="messages" /></a></a>
                    <a href="?page=office&sub=bonus"><img src="img/<?php echo $bonus ?>.png" alt="bonus" /></a></a>
                </div>
                <div class="playerLiga">
                    <img src="img/liga/<?php echo getPlayerLiga() ?>.png" />
                </div>
            </div>
            <div id="submenu">
                <?php echo $submenu ?>
            </div>

            <hr/>
            <div id="always">
                <ul>
                    <a><li class="infoPop" data-open="supportus">Support Us</li></a>
                    <a><li class="infoPop" data-open="bugrep">Feedback</li></a>
                </ul>

                <?php echo onlineUser() . " " . put("online_user", $l); ?> 

                <br/>

                <a href="http://markus.wernersbacher.de/pages/about-this-website/">&copy; wernersbacher 2015-2016</a><br/>
                <noscript> <?php echo put("noscript", $l) ?><br/> </noscript>
                <?php echo getLangChange() ?> | ALPHA 0.1.3 <br/> 
                <?php echo "Server: " . date("d M Y H:i:s"); ?>


            </div>
            <?php echo getScrapperAd(); ?>



        </div>

        <div id="contentWindow">
            <span class="h1"><?php echo put($page, $l) . $subpage ?> </span>
            <?php echo $content ?>
            <?php echo getBannerAd(); ?>
        </div>
    </div>

    <!-- Middle Segment Ende -->


    <!-- Footer Segment Anfang -->
    <div id="supportus" title="Support us">
        <p>Any feedback, help, tips, typos and bad grammatic just send an email: <b><a href='mailto:hi@facethepace.com'>hi@facethepace.com</a></b><br/>
            Paypal address for donating: <b>mwernersbach@web.de</b><br/>
            Bitcoin address for donating: <b>1GrK1y4KNrMwkrBeB6wDUCd2ZVEWaqnSuc</b><br/>
            <br/>
            <b>Sharing the game with friends</b> is still the best help we can get. Thank you!
            <br/><br/>
            PS: Wir können natürlich auch <b>Deutsch</b>.
        </p>
    </div>
    <div id="bugrep" title="Report a bug">
        <form id="bugForm" action="bug.php" method="post">
            <p>Just send us feedback, bugs, infos!</p>
            <textarea name="text" style="width:100%; height:100px;"></textarea><br/>
            Leave your email if you want an answer.</br>
            <input type="submit" value="Send" />
        </form>
    </div>


    <!-- Footer Segment Ende -->

</body>
</html>