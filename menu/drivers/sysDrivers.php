<?php

include_once '_drivers.php';

function newDriver($money, $kosten, $driver_id, $nr, $anteil) {
    global $drivers;
    //Checkt, ob der fahrer NICHT schon angeheuert wurde
    if(queryUserHasNotDriverID($driver_id) AND $money > $kosten) {
        $name = $drivers[$nr]->name;
        $skill = $drivers[$nr]->skill;
        $liga = $drivers[$nr]->maxLiga;
        $country = $drivers[$nr]->country;
        return queryNewDriver($driver_id, $name, $skill,$country, $liga, $kosten, $anteil);
    } else {
        return "database_error";
    }
}

$output = outputTut("driver_info", $l);

if(isset($post["driver_nr"]) AND $post["driver_nr"] >=1) { //Anheuern eines Fahrers
    $nr = $post["driver_nr"];
    $money = getPlayerMoney();
    $kosten = $drivers[$nr]->kosten;
    $driver_id = $drivers[$nr]->id;
    $anteil = $drivers[$nr]->anteil;
    
    $new = newDriver($money, $kosten, $driver_id, $nr, $anteil);
    if($new == "driver_added")
        $tutorial->tickOff("TUT_STATE_BUYDRIVER");
    
    $output .= "<span class='dealInfoText $new'>";
    $output .= put($new, $l);
    $output .= "</span>";
}

$userDrv = queryDriversIDs(); //array
$driverPut = "";
foreach($drivers as $drv) {
    $drive_id = $drv->id;
    
    //Überspringen, fall schon in Besitz
    if($userDrv && in_array($drive_id, $userDrv))
        continue;
    
    $name = $drv->name;
    $country = $drv->country;
    $skill = showSkill($drv->skill);
    $anteil = $drv->anteil;
    $kosten = $drv->kosten;
    $liga = $drv->maxLiga;
    $nr = $drv->nr;
    
    $disabled = boolToDis($kosten < getPlayerMoney());
        
    $driverPut .= " 
                <div class='sysDriver'>
                <h2>$name <div class='driverLiga'>
                            ".levelImg($liga)."</div></h2>
                ".outputDriverInfo($country, $skill, $anteil)."
                 <!-- SRC:http://www.iconarchive.com/show/flag-icons-by-famfamfam.html -->

                
                <div class='tuneFooter absolute'>
                    
                    <span class='dealPrice'>" . dollar($kosten) . " </span>
                    <form method='POST' style='display:inline-block;' action='?page=$page&sub=$sub'>
                        <input type='hidden' name='driver_nr' value='$nr'>
                        <input class='tableTopButton dialog' name='send' type='submit' value='" . put("get_driver", $l) . " '$disabled>
                    </form>
                </div>
                
                </div>


            ";
            
}
if(strlen($driverPut) <1)
       $driverPut = "You hired everyone!";


$output .= "<div id='sysDriver'>";
$output .= $driverPut;
$output .= "</div>";