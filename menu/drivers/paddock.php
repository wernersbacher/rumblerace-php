<?php

$path = "?page=drivers&sub=paddock&mode=manage";

$output = "";
$output .= outputTut("driver_sum", $l);

if (!isset($post["driver_id"]))
    $id = 0;
else
    $id = $post["driver_id"];
$driver = getDriverByID($id);


if (isset($post["action"]) AND $post["action"] == "fire") {
    //remove driver
    $remove = removeDriverByID($id);
    $output .= "<span class='dealInfoText $remove'>";
    $output .= put($remove, $l);
    $output .= "</span>";

    $driver = false;
}


if ($mode == "manage" && $driver) {

    if (isset($post["action"]) AND $post["action"] == "changeName") {
        $newName = trim($post["newName"]);

        if (strlen($newName) > 3 AND strlen($newName) < 18 AND isValid($newName)) {
            changeDriverName($id, $newName);
            $driver["name"] = $newName;
        }
    }




    $output .= backLink("?page=drivers&sub=paddock");

    $name = $driver["name"];
    $skill = showSkill($driver["skill"]);
    $liga = $driver["liga"];
    $anteil = $driver["anteil"];

    /*
     * TODO:
     * Änderung annhemen
     * Fahrer feuern
     */

    $output .= "
        
            
                <div class='sysDriver'>
                <h2> 
                <div id='driverName'> $name <span id='driverNameChange'>&#9998;</span></div>
                <form id='driverNameInput' name='input' action='$path' method='post' style='display: none;'>
                <input type='hidden' name='action' value='changeName'></input>
                <input type='hidden' name='driver_id' value='$id'></input>
                    <input id='comment' type='text' name='newName' value='$name'/>
                <input type='submit' value='Save' />
                </form>     
                </h2>
                    
                <div class='driverLiga absLiga'><img src='img/liga/" . $liga . ".png' alt='League " . $liga . "' title='League " . $liga . "' /></div>
                
                 <hr/>

                <div class='settingPoint'>Skill:</div>
                $skill
                      
                <hr/>
                <form method='POST' style='display:inline-block;' action='$path'>
                    <input type='hidden' name='action' value='fire'></input>
                    <input type='hidden' name='driver_id' value='$id'></input>
                    <input class='tableTopButton redButton dialog' name='send' type='submit' value='" . put("fire_driver", $l) . "'>
                </form>
                    


            ";
} else {



    $output .= "<div id='cardealer'>";

    $drivers = queryDrivers();
    if ($drivers)
        foreach ($drivers as $drvr) {//$car["title"]
            $did = $drvr["id"];
            $driver_id = $drvr["driver_id"];
            $name = $drvr["name"];
            $skill = showSkill($drvr["skill"]);
            $liga = $drvr["liga"];
            $anteil = $drvr["anteil"];

            $output .= " 
                <div class='sysDriver'>
                <h2>$name <div class='driverLiga'><img src='img/liga/" . $liga . ".png' alt='League " . $liga . "' title='League " . $liga . "' /></div></h2>
                Skill: <b>$skill</b> [$driver_id]
                 

                
                <div class='tuneFooter'>
              
                ";

            $output .= "<form method='POST' action='$path'>";
            $output .= "<input type='hidden' name='driver_id' value='$did'></input>";
            $output .= "<span class='dealPrice'>$anteil% Anteil </span> "
                    . "<input class='tableTopButton' name='manage' type='submit' value='" . put("open_driver", $l) . "'>";
            $output .= "</form>";


            $output .= "</div>
                
                </div>
            ";
        } else
        $output .= put("no_driver", $l);
    $output .= "</div>";
}