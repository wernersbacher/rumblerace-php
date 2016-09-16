<?php

$output = outputTut("opt_info", $l);

$email = getPlayerEmail();
if (strlen($email) < 1) {
    $email = "add_email";
    $valMail = "";
} else
    $valMail = $email;

if (isset($post["action"]) AND $post["action"] == "changeMail") {
    $newMail = $post["newMail"];

    if (!filter_var($newMail, FILTER_VALIDATE_EMAIL) === false)
        if (countEmail($newMail) < 1) {

            updateEmail($newMail);
            $email = $newMail;
        }
}


$output .= "    <div class='settings'>
    
                <div class='settingPoint'>Email:</div>
                <span id='driverName'>  " . put($email, $l) . " <span id='driverNameChange'>&#9998;</span></span>
                <form id='driverNameInput' name='input' action='?page=options&sub=settings' method='post' style='display: none;'>
                <input type='hidden' name='action' value='changeMail'></input>
                    <input class='focusThis' id='comment' type='text' name='newMail' placeholder='$valMail' value=''/>
                <input type='submit' value='Save' />
                </form>  
                
                <hr/>
                <a href='reset.php' target='_blank'>".put("use_pwd_forget", $l)."</a> 


                </div>
       ";
