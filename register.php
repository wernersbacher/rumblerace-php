<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location:main.php");
}
require_once('_mysql.php');
require_once('_lang.php');
$status = "";

if (isset($_POST['send'])) {
    $user = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING)["user"];
    $pass = filter_input_array(INPUT_POST)["pass"];
    $pass2 = filter_input_array(INPUT_POST)["pass2"];
    $email = filter_input_array(INPUT_POST, FILTER_VALIDATE_EMAIL)["email"];
    $userExists = queryExistsUser($user);

    if ($pass === $pass2 && strlen($user) < 13 && strlen($user) > 2 && !$userExists) {
        //Registrieren
        $status = queryRegister($user, $pass, $email);
    } else if ($userExists) {
        $status = "username_exists";
    } else {
        $status = "wrong_input_reg";
    }

    if ($status === "ok_reg") {
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
        <title>RACING INC. | Register</title>
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
            <?php echo put("register_free", $l) ?><br/>
            <?php echo put($error, $l); ?>
            <form action="register.php" method="post">
                <input type="text" name="user" required="required" placeholder="Username" maxlength="55" /><br/>
                <input type="password" name="pass" required="required" placeholder="Password" maxlength="50" /><br/>
                <input type="password" name="pass2" required="required" placeholder="Password (retype)" maxlength="50" /><br/>
                <input type="email" name="email" placeholder="Email (optional)" maxlength="50" /><br/>
                <input type="submit" name="send" value="Register" />

            </form>

            <a href="login.php" ><?php echo put("or_login", $l) ?></a>
            
        </div>
        
        <div id="login_prev" style="width:100%; height:50%; ">
            
            
        </div>


    </body>
</html>