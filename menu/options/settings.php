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
} else if(isset($post["action"]) AND $post["action"] == "changeSwitches") {
    if(isset($post['switch_ads'])) {
        toggleAds(1);
    } else {
        toggleAds(0);
    }
}
$ads = ttc(getSwitches()["ads"]);





$output .= "    <div class='settings'>
                
                <form class='switches' action='?page=$page&sub=$sub' method='post'>
                   
                    <div class='switchUnit'>

                        <div class='switch Desc'>ADs</div>
                        <div class='onoffswitch'>
                            <input type='checkbox' name='switch_ads' class='onoffswitch-checkbox' id='switch_ads' $ads>
                            <label class='onoffswitch-label' for='switch_ads'>
                                <span class='onoffswitch-inner'></span>
                                <span class='onoffswitch-switch'></span>
                            </label>


                        </div>
                    
                    </div>
                    
                    <input type='hidden' name='action' value='changeSwitches'></input>
                    <input type='submit' style='margin-top: 10px;display:block;' class='tableTopButton' value='Save'></input>
                </form>
                <hr/>

                <div class='settingPoint'>Email:</div>
                <span id='driverName'>  " . put($email, $l) . " <span id='driverNameChange'>&#9998;</span></span>
                <form id='driverNameInput' name='input' action='?page=$page&sub=$sub' method='post' style='display: none;'>
                <input type='hidden' name='action' value='changeMail'></input>
                    <input class='focusThis' id='comment' type='text' name='newMail' placeholder='$valMail' value=''/>
                <input type='submit' value='Save' />
                </form>  
                
                <hr/>
                <a href='reset.php' target='_blank'>" . put("use_pwd_forget", $l) . "</a> 


                </div>
       ";
