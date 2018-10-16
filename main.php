<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
$get = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$post = filter_input_array(INPUT_POST);

//Check if logged in
require_once('_data.php');
require_once('_checkuser.php');
require_once('_overwrite.php');
require_once('_function.php');
require_once('_mysql.php');
require_once('_upgrades.php');
require_once('_rewards.php');
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

if (!isset($get["page"]))
    $page = "office";
else
    $page = $get["page"];

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


//Load new any maybe changed player data for output
$player = queryPlayerStats();

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

$player = queryPlayerStats();

$content = $output;
//Check for tooltip data
if (!isset($htmlTooltips))
    $htmlTooltips = "";


//Anzeigen des Spritstatistik
$spm = calcSpritMin();
$max = getMaxSprit();
//$sprit = "<span title='" . gas(nwc($spm / 60)) . "/sec' id='spritTags' data-spritmax='$max' data-promin='$spm'><span id='playerSprit'>" . gas(round(getPlayerSprit(), 2)) . "</span>/" . gas(nwc($max)) . "</span>";
$sprit2 = "<span title='" . gas(nwc($spm / 60)) . "/sec' id='spritTags' data-spritmax='$max' data-promin='$spm'>
    <span id='playerSprit'>" . gas(round(getPlayerSprit(), 2)) . "</span>
        </span>";

//Adding Submenu for page
$subarray = getSubMenu($page);
$submenu = "";

if ($subarray) {
    //$submenu = '<ul>';
    foreach ($subarray as $value) {
        if ($sub === $value) {
            $active = " class='sub_active'";
        } else {
            $active = "";
        }
        $submenu .= '<a href="main.php?page=' . $page . '&sub=' . $value . '"><span' . $active . '>' . put("s_" . $value, $l) . '</span></a>';
    }
    //$submenu .= '</ul>';
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
http://wernersbacher.de

Love for Meri

-->
<html>
    <head>
        <title><?php echo put($page, $l) ?> | Racing Inc</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <link rel="stylesheet" href="src/font.css">
        <link href="https://fonts.googleapis.com/css?family=Kodchasan:400,700" rel="stylesheet">
        <link rel="stylesheet" href="mobile.css">
        <link rel="stylesheet" href="default.css">
        <link rel="stylesheet" href="pages.css">
        <link rel="stylesheet" href="pages.colors.css">
        <link rel="stylesheet" href="lib/jquery-ui.css">
        <link rel="stylesheet" href="lib/tooltipster.bundle.min.css" />
        <link rel="stylesheet" href="lib/tooltipster-sideTip-borderless.min.css" />
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="backstretch.min.js"></script>
        <script type="text/javascript" src="lib/store.modern.min.js"></script>
        <script type="text/javascript" src="lib/jquery-ui.min.js"></script>
        <script type="text/javascript" src="lib/jquery.connections.js"></script>
        <script type="text/javascript" src="lib/form.min.js"></script>
        <script type="text/javascript" src="lib/date.js"></script>
        <script type="text/javascript" src="lib/tooltipster.bundle.min.js"></script>
        <script type="text/javascript" src="cookie.js"></script>
        <!--<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>-->
        <script type="text/javascript" src="gui.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300' rel='stylesheet' type='text/css'> 
        <link rel="shortcut icon" type="image/x-icon" href="img/logo16.ico">
        <!-- Piwik -->
        <!--<script type="text/javascript">
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
        </script>-->
    <noscript><p><img src="//wernersbacher.de/analyse/piwik.php?idsite=3" style="border:0;" alt="" /></p></noscript>
    <!-- End Piwik Code -->
</head>
<body>

    <div id="pageWrapper" >
        <!-- Top Segment Anfang -->
        <div id="topBar">
            <div id="topContent">
                <a href='main.php'><span class="rumblerace">RACING <span class="colored">INC.</span></span></a>
                <span id="blackMenu">ALPHA</span>
            </div>
        </div>
        <!-- Top Segment Ende -->

        <!-- Main Menü Segment Anfang -->

        <div id="mainMenuWrapper">
            <div id="mainmenu">
                <?php echo getMenuList(); ?>
            </div>

        </div>

        <!-- Main Menü Segment Ende -->

        <div id="mid-header">

            <?php if (isPlayerGuest()) { ?>
                <div id="guestInfo">
                    <?php echo put("attention_reg", $l); ?><br/>
                    <i><?php
                        if (isset($get["reg"]))
                            echo put($get["reg"], $l);
                        ?> </i>
                    <form class="bigForm" action="register.php?guest=true" method="post">
                        <input type="text" name="user" required="required" placeholder="Username" maxlength="55" />
                        <input type="password" name="pass" required="required" placeholder="Password" maxlength="50" />
                        <input type="password" name="pass2" required="required" placeholder="Password (retype)" maxlength="50" />
                        <input type="hidden" name="email" placeholder="Email (optional)" maxlength="50" />
                        <input type="hidden" name="register" value="yes"/>
                        <input type="submit" name="send" value="Register" />

                    </form>

                </div> 
            <?php } ?>

        </div>

        <!-- Middle Segment Anfang -->
        <div id="middle">
            <div id="leftMenu">
                <!--<div id="player-info">
                    <span class="playername"><?php echo $_SESSION["username"] ?> <img id="mobile_liga" style="height:16px; display: none;" src="img/liga/<?php echo getPlayerLiga() ?>.png"></span><br/>
                    <span class="stats">
                        <img src="img/dollar.png" /> <?php echo dollar(getPlayerMoney()) ?><br/>
                        <img src="img/star.png" /> <span title="<?php echo ep(expToLiga(getPlayerLiga() + 1) - getPlayerExp()) ?> left"><?php echo ep(getPlayerExp()) ?></span><br/>
                        <a href="main.php?page=sprit"><img src="img/energy.png" /> <?php echo $sprit ?></a><br/>
                        <a href="main.php?page=factory&sub=upgrades" title="Free Upgrade Points"><img src="img/ups.png" /> <?php echo getPlayerUpPoints() ?></a> | 
                        <a href="?page=race&sub=running" title="Running races"><img src="img/wait.png" /> <?php echo getCurrentRunningRaces() ?></a><br/>
                    </span>
                    <div class="playerQuick"  style="margin-top: 4px;">
                        <a href="?page=office&sub=messages"><img src="img/<?php echo $letter ?>.png" alt="messages" /></a></a>
                        <a href="?page=office&sub=bonus"><img src="img/<?php echo $bonus ?>.png" alt="bonus" /></a></a>
                    </div>
                    <div class="playerLiga" id="desktop_liga">
                        <img src="img/liga/<?php echo getPlayerLiga() ?>.png" />
                    </div>
                </div>-->

                <div id="blackLeftInfo">
                    <div id="playerData">
                        <img src="img/ava.png" /><br/>
                        <span class="playername"><?php echo $_SESSION["username"] ?> <img id="mobile_liga" style="display:none; height:16px; " src="img/liga/<?php echo getPlayerLiga() ?>.png"></span><br/>


                        <?php
                        //Gibt den absoluten Fortschritt zurück
                        $expForNewLevel = outputLevelProgress();
                        ?>




                    </div>
                    <div id="blackButtons">

                        <a href="main.php?page=office&sub=messages"><div><img src="img/office40.png" /><span <?php echo boolToHide($newFx) ?> class="badge alert"><?php echo $newFx; ?></span></div></a>
                        <a href="#"><div><img src="img/car40.png" /></div>
                            <a href="#"><div><img src="img/tools40.png" /></div></a>


                    </div>

                    <div id="blackStats">
                        <div class='vertical-align'><img src="img/award.png"/>
                            <span class="blackOutput"><?php echo $expForNewLevel ?></span>
                        </div>
                        <div class='vertical-align'><img src="img/money32.png"/>
                            <span class="blackOutput"><?php echo format(getPlayerMoney()) ?></span>
                        </div>
                        <div class='vertical-align'><img src="img/fuel.png"/>
                            <span class="blackOutput"><?php echo $sprit2 ?></span>
                        </div>
                    </div>


                </div>

            </div>

            <div id="contentWindow">
                <div id="tabsWrapper"> <!-- submenu -->
                    <div id="tabs">

                        <?php
                        echo $submenu;
                        ?>

                    </div>
                    <div id="bottomLine"></div>   
                </div>

<!--<span class="h1"><?php echo put($page, $l) . $subpage ?> </span>-->
                <?php echo $content ?>
                <?php echo getBannerAd(); ?>
            </div>
        </div>
        <footer>
            <div id="always">
                <div>

                    <ul>
                        <a><li class="infoPop" data-open="supportus">Support Us</li></a>
                        <a><li class="infoPop" data-open="bugrep">Feedback</li></a>
                    </ul>
                </div>
                <div>

                    <?php echo onlineUser() . " " . put("online_user", $l); ?> 

                    <br/>

                    <a href="http://markus.wernersbacher.de/pages/about-this-website/">&copy; wernersbacher 2016-2017</a><br/>
                    <noscript> <?php echo put("noscript", $l) ?><br/> </noscript>
                    <?php echo getLangChange() ?> | ALPHA 0.1.3 <br/> 
                    <?php echo "Server: " . date("d M Y H:i:s"); ?>

                </div>
            </div>
        </footer>

    </div>
    <!-- Middle Segment Ende -->


    <!-- Footer Segment Anfang -->
    <div id="supportus" title="Support us">
        <p>Any feedback, help, tips, typos and bad grammatic just send an email: <b><a href='mailto:racingInc@wernersbacher.de'>racingInc@wernersbacher.de</a></b><br/>
            Paypal address for donating: <b>mwernersbach@web.de</b><br/>
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
            Leave your email if you want to receive an answer.</br>
            <input type="submit" value="Send" />
        </form>
    </div>

    <div id="tooltip_templates">
        <?php echo $htmlTooltips; ?>
    </div>


</body>
</html>
