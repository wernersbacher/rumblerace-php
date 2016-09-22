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

//Einzelner Fahrer
if ($mode == "manage" && $driver) {

    if (isset($post["action"]) AND $post["action"] == "changeName") {
        $newName = trim($post["newName"]);

        if (strlen($newName) > 3 AND strlen($newName) < 18 AND isValid($newName)) {
            changeDriverName($id, $newName);
        }
    } else if (isset($post["action"]) AND $post["action"] == "upgrade") {
        $driver = getDriverByID(intval($post["driver_id"]));
        $cost = driverUpgradeCost($driver["liga"]);
        if ($driver AND getPlayerMoney() >= $cost) {
            $up = upgradeDriver($post["driver_id"], $cost);

            $output .= "<span class='dealInfoText $up'>";
            $output .= put($up, $l);
            $output .= "</span>";
        }
    }

    $driver = getDriverByID($id);



    $output .= backLink("?page=drivers&sub=paddock");

    $name = $driver["name"];
    $skill = showSkill($driver["skill"]);
    $liga = $driver["liga"];
    $anteil = $driver["anteil"];

    $upgradeCost = driverUpgradeCost($liga);

    if ($upgradeCost > getPlayerMoney())
        $disabled = "disabled";
    else
        $disabled = "";


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
                 <div class='driver_quick'>
                 
                    <div class='settingPoint'>Skill:<br/>$skill</div>
                    

                    <div class='settingPoint'>" . put("anteil", $l) . ":<br/>$anteil%</div>
                    

                </div>
                <hr/>
                <form data-dialog='Do you want to upgrade the driver?' method='POST' style='display:inline-block;' action='$path'>
                    <input type='hidden' name='action' value='upgrade'></input>
                    <input type='hidden' name='driver_id' value='$id'></input>
                    <input class='tableTopButton dialog' name='send' type='submit' value='" . put("upgrade_driver", $l) . "' $disabled>
                </form>
                <span> " . put("kostenpunkt", $l) . ": " . dollar($upgradeCost) . " </span>

                <br/>
                <form data-dialog='Do you want to fire your driver?' method='POST' style='display:inline-block;' action='$path'>
                    <input type='hidden' name='action' value='fire'></input>
                    <input type='hidden' name='driver_id' value='$id'></input>
                    <input class='tableTopButton redButton dialog' name='send' type='submit' value='" . put("fire_driver", $l) . "'>
                </form>
                    


            ";

    //Fahrer übersicht
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
            $output .= "<span class='dealPrice'>$anteil% " . put("anteil", $l) . " </span> "
                    . "<input class='tableTopButton' name='manage' type='submit' value='" . put("open_driver", $l) . "'>";
            $output .= "</form>";


            $output .= "</div>
                
                </div>
            ";
        } else {
        $output .= "<div class='settings'>";
        $output .= put("no_driver", $l);
        $output .= "</div>";
    }
    $output .= "</div>";
}