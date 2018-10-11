<?php
session_start();
$userGuest = false;
$userSet = isset($_SESSION['username']);
if ($userSet && explode(":", $_SESSION['username'])[0] == "guest") //check, register ein user ist, der daten angibt
    $userGuest = true;

if ($userSet AND ! $userGuest) {
    header("Location:main.php");
}
//check mit userguest

require_once('_game_config.php');
require_once('_mysql.php');
require_once('_lang.php');
require_once("gen.php");
require_once('_overwrite.php');
require_once('_function.php');
$status = "";
if (isset($_POST['register']) && isset($_GET['guest'])) { //umbennen des accounts
    $user = filter_input_array(INPUT_POST)["user"];
    $pass = filter_input_array(INPUT_POST)["pass"];
    $pass2 = filter_input_array(INPUT_POST)["pass2"];
    $userExists = queryExistsUser($user);

    if ($pass === $pass2 && strlen($user) < 13 && strlen($user) > 2 && !$userExists && checkUsername($user)) {
        //Registrieren
        $status = queryGuestRegister($_SESSION['username'], $user, $pass);
    } else if ($userExists) {
        $status = "username_exists";
    } else if ($pass !== $pass2) {
        $status = "password_not_correct";
    } else if (strlen($user) >= 13 OR strlen($user) <= 2) {
        $status = "user_too_short_long";
    } else if (!checkUsername($user)) {
        $status = "bad_user_char";
    } else if (countEmail($email) > 0 AND strlen($email) > 0) {
        $status = "email_taken";
    } else {
        $status = "wrong_input_reg";
    }

    if ($status === "ok_reg") {
        setcookie("guestpw", "", time() - 3600);

        queryLogin($user, $pass);
        saveSession($_SESSION["user_id"]);
        header('location: main.php?reg=ok_reg');
    } else {
        var_dump("test");
        header('location: main.php?reg=' . $status);
    }
} else if (isset($_POST['register'])) {
    $user = filter_input_array(INPUT_POST)["user"];
    $pass = filter_input_array(INPUT_POST)["pass"];
    $pass2 = filter_input_array(INPUT_POST)["pass2"];
    $email = filter_input_array(INPUT_POST, FILTER_VALIDATE_EMAIL)["email"];
    $userExists = queryExistsUser($user);

    if ($pass === $pass2 && strlen($user) < 13 && strlen($user) > 2 && !$userExists && checkUsername($user) && (countEmail($email) < 1 OR strlen($email) < 1)) {
        //Registrieren
        $status = queryRegister($user, $pass, $email);
    } else if ($userExists) {
        $status = "username_exists";
    } else if ($pass !== $pass2) {
        $status = "password_not_correct";
    } else if (strlen($user) >= 13 OR strlen($user) <= 2) {
        $status = "user_too_short_long";
    } else if (!checkUsername($user)) {
        $status = "bad_user_char";
    } else if (countEmail($email) > 0 AND strlen($email) > 0) {
        $status = "email_taken";
    } else {
        $status = "wrong_input_reg";
    }

    if ($status === "ok_reg") {
        queryLogin($user, $pass);
        saveSession($_SESSION["user_id"]);
        header('location: main.php?reg=ok_reg');
    }
    $error = $status;
} else if (isset($_POST['guest'])) {
    //check if user is already a guest
    $guest = isGuestLoggedIn();
    if ($guest) {
        $status = queryLogin($guest[0], $guest[1]);
        if ($status === "ok_user") {
            $user = queryPlayerByID($_SESSION["user_id"]);
            login($_SESSION["user_id"], $user["username"], $user["lang"]);
            saveSession($_SESSION["user_id"]);
            header('location: main.php');
        } else {
            setcookie("guestpw", "", time() - 3600);
        }
    } else {
        //Register as guest

        do {
            $user = generateRandomUsername();
        } while (queryExistsUser($user));

        $pass = generateRandomPassword();
        //passwort im cookie speichern
        $status = queryRegister($user, $pass, $email);
        if ($status === "ok_reg") {
            saveSession($_SESSION["user_id"]);
            saveGuestDetails($user, $pass);
            header('location: main.php');
        } else {
            $status = "database_error";
            $error = $status;
        }
        
    }
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
        <script type="text/javascript" src="lib/store.js"></script>
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
            <form class="bigForm" action="register.php" method="post">
                <input type="text" name="user" required="required" placeholder="Username" maxlength="55" /><br/>
                <input type="password" name="pass" required="required" placeholder="Password" maxlength="50" /><br/>
                <input type="password" name="pass2" required="required" placeholder="Password (retype)" maxlength="50" /><br/>
                <input type="email" name="email" placeholder="Email (optional)" maxlength="50" /><br/>
                <input type="hidden" name="register" value="yes"/>
                <input type="submit" name="send" value="Register" />

            </form>

            <a href="login.php" ><?php echo put("or_login", $l) ?></a>

        </div>

        <div id="login_prev" style="width:100%; height:50%; ">


        </div>


    </body>
</html>