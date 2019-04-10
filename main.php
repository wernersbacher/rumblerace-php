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
require_once('_tutorial.php');
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

if (!isset($get["page"]) OR !$tutorial->isAtState(getMainReq($get["page"])))
    $page = "office";
else
    $page = $get["page"];

if (!isset($get["sub"]) OR !$tutorial->isAtState(getMainReq($get["page"])) OR !$tutorial->isAtState(getSubReq($get["sub"])))
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
if (getPlayerLiga() < $_config["liga"]["maxLiga"])
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
$sprit2 = "<span title='" . gas(nwc($spm / 60)) . "/sec, MAX: ".gas(getMaxSprit())."' id='spritTags' data-spritmax='$max' data-promin='$spm'>
    <span id='playerSprit'>" . gas(round(getPlayerSprit(), 2)) . "</span>
        </span>";


//Load Notifications
$notify = getNotifications();

//var_dump(getNotificationsArray());
//for($i = 1; $i<20; $i++) 
//    echo "$i: ".format(levelExp($i)).",  ".format(levelExp($i))."<br/>";


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
        
        <?php include("html/meta.html") ?>

</head>
<body>

    <div id="pageWrapper" >

        <!-- Top Segment Anfang -->
        <div id="topBar">
            <div id="topContent">
                <div><a href='main.php'><span class="rumblerace">RACING <span class="colored">INC.</span></span></a></div>
                <div class="titleMenu"> 
                    <a href="main.php?page=options&sub=faq"><div class="titleButton"><img src="img/help40.png" /></div></a>
                    <a href="main.php?page=options&sub=settings"><div class="titleButton"><img src="img/setting40.png" /></div></a>
                    <a href="logout.php"><div class="titleButton"><img src="img/logout40.png" /></div></a>
                </div>
            </div>
        </div>
        <!-- Top Segment Ende -->

        <!-- Main Menü Segment Anfang -->

        <div id="whiteBGWrapper">

            <div id="mainMenuWrapperTabs" >

                <div id="tabsWrapperMain"> <!-- mainmenu as tabs -->
                    <div id="tabsMain">

                        <?php
                        echo getMenuListTabs($page);
                        ?>

                    </div>
                    <div class="bottomLine"></div>   
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
                    <div id="blackLeftInfo">
                        <div id="playerData">
                            <img class="playerAvatar" src="img/ava.png" /><br/>
                            <span class="playername"><?php echo $_SESSION["username"] ?> <img id="mobile_liga" style="display:none; height:16px; " src="img/liga/<?php echo getPlayerLiga() ?>.png"></span><br/>


                            <?php
                            //Gibt den absoluten Fortschritt zurück
                            $expForNewLevel = outputLevelProgress();
                            ?>

                        </div>

                        <div id="blackStats">
                            <div class='vertical-align'><img src="img/award.png"/>
                                <span class="blackOutput"><?php echo format($expForNewLevel) ?></span>
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

                    <?php
                        if(!$tutorial->isDone())
                            echo getTutorialBox();
                    ?>
                    
                    
                    <div id="mainFrame">
                        
                    
                        <div id="tabsWrapper"> <!-- submenu -->
                            <div id="tabs">

                                <?php
                                echo outputSubmenu($page, $sub);
                                ?>

                            </div>
                            <div class="bottomLine"></div>   
                        </div>



                        <?php echo $content ?>
                        <?php echo getBannerAd(); ?>
                    </div>
                </div>
                
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
