<?php

function sendToken($email, $token) {
    $l = queryPlayerByMail($email)["lang"];
    $text = put("text_mail_pwd", $l) . "\n\nhttps://facethepace.com/reset.php?mail=" . urlencode($email) . "&token=$token \n\n" . put("closing", $l);
    
    sendMail($email, put("betreff_mail_pwd", $l), $text);
}

//reset password function
require_once('_mysql.php');
require_once('_lang.php');
require_once('_function.php');
$post = filter_input_array(INPUT_POST);
$get = filter_input_array(INPUT_GET);
$status = "";


if (isset($post["email"]) && !(isset($get["mail"]) OR isset($get["reset"]))) {
    $email = $post["email"];
    if (countEmail($email) == 1) {
        $now = time();
        $token = generateToken($now, $email);

        //Speicher Token
        saveToken($email, $now, $token);

        //Email senden
        sendToken($email, $token);
    }

    $status = "mail_send_if_ex";
}
?>

<html>
    <head>
        <title>RACING INC. | Reset Password</title>
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

            <?php
            
            if (isset($get["mail"])) {
                $email = $get["mail"];
                $token = $get["token"];
                if (countEmail($email) && ctype_xdigit($get["token"]) && isTokenValid($email, $token)) {
                    //Link is valid
                    $player = queryPlayerByMail($email);
                    echo put("type_new_pwd", $l);
                    echo '<form class="bigForm" action="reset.php?reset=yep" method="post">
                        <input type="hidden" name="email" value="'.$email.'" />
                        <input type="hidden" name="token" value="'.$token.'" />
                        <input type="password" name="pass" required="required" placeholder="Password" maxlength="50" /><br/>
                        <input type="password" name="pass2" required="required" placeholder="Password (retype)" maxlength="50" /><br/>
                        <input type="submit" name="send" value="Save" />

                    </form>';
                    
                    
                } else {
                    echo put("link_expired", $l);
                }
                
            } else if(isset($get["reset"])) {
                
                //Save new password (und check ob der link noch valide ist)
                
                if(isset($post["email"]) && isset($post["token"]) && isset($post["pass"]) && isset($post["pass2"]) && isTokenValid($post["email"], $post["token"])) {
                    //Mail und token sind da und machen auch sinn, passworte sind auch da
                    
                    if($post["pass"] == $post["pass2"]) {
                        //Alles gut, gespeichert und info ausgeben
                        changePass($post["pass"], $post["email"]);
                        echo put("pass_saved", $l);
                        //token resetten, damit link ungültig wird
                        saveToken($post["email"], 0, '');
                    } else {
                        echo put("wrong_pass", $l);
                    }
                    
                    
                } else {
                    echo put("link_expired", $l);
                }
                
            } else {


                echo put("reset_info", $l);

                echo '<form class="bigForm" action="reset.php" method="post">
                        <input type="email" name="email" placeholder="Email" maxlength="50" /> 
                        <input type="submit" name="send" value="Send" />

                     </form>';

                echo put($status, $l);
            }
            ?>


        </div>

        <div id="login_prev" style="width:100%; height:50%; ">


        </div>


    </body>
</html>