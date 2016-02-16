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
        <title>Rumblerace Login</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="src/font.css">
        <link rel="stylesheet" href="default.css">
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,100,300' rel='stylesheet' type='text/css'> 
    </head>
    <body>

        <div id="topBar">
            <div id="topContent">
                <span class="rumblerace">RUMBLE <span class="colored">RACE</span></span>
                <span id="blackMenu"></span>
            </div>
        </div>

        <div id="loginWindow">
            <?php echo put("register_free", $l) ?><br/>
            <?php echo put($error, $l); ?>
            <form action="register.php" method="post">
                <input type="text" name="user" required="required" placeholder="Username" maxlength="55" /><br/>
                <input type="password" name="pass" required="required" placeholder="Password" maxlength="50" /><br/>
                <input type="password" name="pass2" required="required" placeholder="Password (again)" maxlength="50" /><br/>
                <input type="email" name="email" placeholder="Email (optional)" maxlength="50" /><br/>
                <input type="submit" name="send" value="Register" />

            </form>


        </div>

        <?php include("_footer.php") ?>

    </body>
</html>