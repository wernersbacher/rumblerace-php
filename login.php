<?php

include("gen.php");
include("_checkuser.php");
require_once('_function.php');


//session_start();
define('SECURE', true);
error_reporting(E_ALL);

require_once('_mysql.php');
require_once('_lang.php');
require_once('_function.php');


/**
 *    Anmeldevorgang
 */
if (isset($_POST['send'])) {
    $user = filter_input_array(INPUT_POST)["user"];
    $pass = filter_input_array(INPUT_POST)["pass"];

    $status = queryLogin($user, $pass);

    if ($status === "ok_user") {
        saveSession($_SESSION["user_id"]);
        header('location: main.php');
    }
    $error = $status;
} else {
    $error = NULL;
    $user_id = NULL;
}
?>

<html>
    <head>
        <title>RACING INC. | Login</title>
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
        <link rel="shortcut icon" type="image/x-icon" href="img/logo16.ico">
    </head>
    <body>
        
        <div id="topBar">
            <div id="topContent">
                <a href='main.php'><span class="rumblerace">RACING  <span class="colored">INC.</span>
                        <span id="smallHeader">face<span class="gr">the</span>pace<span class="it">.com</span></span>
                    </span></a>
                <span id="blackMenu"></span>
            </div>
        </div>
        
        <div id="loginWindow">
            
            <?php echo put("play_as_guest", $l) ?> 
            <form class="bigForm" action="register.php" method="post">
                <input type="hidden" name="guest" value="yes"/>
                <input type="submit" name="send" value="PLAY AS GUEST" />
            </form>
            
            <hr/>
            
            <?php echo put("please_login", $l) ?> (BETA)<br/>
            <?php echo put($error, $l); ?>
            <form class="bigForm" action="login.php" method="post">
                <input type="text" name="user" required="required" placeholder="Username" maxlength="55" />
                <input type="password" name="pass" required="required" placeholder="Password" maxlength="50" />
                <input type="hidden" name="login" value="yes"/>
                <input type="submit" name="send" value="Login" />
                <div class="sizeNormal"><a href="reset.php"><?php echo put("resetpwd", $l) ?></a></div>
            </form>
            <hr/>
            
            <a href="register.php" ><?php echo put("or_register", $l) ?></a>
            
            
        </div>
        
        
        <div id="login_prev" style="width:100%; height:50%; ">
            
            
        </div>
        

    </body>
</html>