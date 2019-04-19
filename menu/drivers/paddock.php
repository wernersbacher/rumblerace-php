<?php

$path_overview = "?page=$page&sub=$sub";
$path = "$path_overview&mode=manage";

$output = "";
$output .= outputTut("driver_sum", $l);

if (!isset($post["driver_id"]))
    $id = 0;
else
    $id = $post["driver_id"];
$driver = getDriverByID($id);


if (isset($post["action"]) AND $post["action"] == "fire" AND queryDriverIsNotRacing($id)) {
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

        if (/* $driver["liga"] < 8 && */ $driver AND getPlayerMoney() >= $cost) {
            $up = upgradeDriver($post["driver_id"], $cost);

            $output .= "<span class='dealInfoText $up'>";
            $output .= put($up, $l);
            $output .= "</span>";
        }
    }

    if (isset($post["action"]) AND $post["action"] == "sell" AND queryDriverIsNotRacing($id)) {
        $driver = getDriverByID($id);

        $name = $driver["name"];
        $skill = showSkill($driver["skill"]);
        $liga = $driver["liga"];
        $anteil = $driver["anteil"];
        $country = $driver["country"];
        $nameChanged = $driver["nameChanged"];

        $output .= "<div class='textCenter'>";
        $output .= "<b>" . getFlag($country) . " " . getNameChanged($name, $nameChanged) . " " . levelImg($liga) . "</b><br/>";
        $output .= put("market_sell_driver", $l) . ":";

        $output .= "<form method='POST' action='$path_overview'>
                        <input type='hidden' name='action' value='confirm_sell'></input>
                        <input type='hidden' name='driver_id' value='$id'></input>
                        <input type='number' min='0.01' step='0.01' name='price' placeholder='100' class='tableTopInput'>
                        <input class='sellButton tableTopButton' name='confirmed' type='submit' value='verkaufen'>
                    </form>";

        $output .= "<br/>" . put("market_with", $l);
        $output .= "</div>";
    }

    $driver = getDriverByID($id);



    $output .= backLink("?page=$page&sub=$sub");

    $name = $driver["name"];
    $skill = showSkill($driver["skill"]);
    $liga = $driver["liga"];
    $anteil = $driver["anteil"];
    $country = $driver["country"];
    $nameChanged = $driver["nameChanged"];

    $upgradeCost = driverUpgradeCost($liga);

    $disabled = boolToDis($upgradeCost < getPlayerMoney());



    $output .= "
        
            
                <div class='sysDriver'>
                <h2> 
                <div id='driverName'> " . getFlag($country) . " " . getNameChanged($name, $nameChanged) . " <span id='driverNameChange'>&#9998;</span></div>
                <form id='driverNameInput' name='input' action='$path' method='post' style='display: none;'>
                <input type='hidden' name='action' value='changeName'></input>
                <input type='hidden' name='driver_id' value='$id'></input>
                    <input id='comment' type='text' name='newName' value='$name'/>
                <input type='submit' value='Save' />
                </form>     
                </h2>
                    
                <div class='driverLiga absLiga'>
                            " . levelImg($liga) . "</div>
                
                 <hr/>
                 <div class='driver_quick'>
                 
                    <div class='settingPoint'>Skill:<br/>$skill%</div>
                    

                    <div class='settingPoint'>" . put("anteil", $l) . ":<br/>$anteil%</div>
                    

                </div>
                <hr/>
                <form  method='POST' style='display:inline-block;' action='$path'>
                    <input type='hidden' name='action' value='upgrade'></input>
                    <input type='hidden' name='driver_id' value='$id'></input>
                    <input class='tableTopButton dialog' name='send' type='submit' value='" . put("upgrade_driver", $l) . "' $disabled>
                </form>
                <span> " . put("kostenpunkt", $l) . ": " . dollar($upgradeCost) . " </span>

                <br/>
                <form method='POST' style='display:inline-block;' action='$path'>
                    <input type='hidden' name='action' value='sell'></input>
                    <input type='hidden' name='driver_id' value='$id'></input>
                    <input class='tableTopButton dialog' name='send' type='submit' value='" . put("sell_driver", $l) . "'>
                </form>
                
                <form data-dialog='Do you want to fire your driver?' method='POST' style='display:inline-block;' action='$path'>
                    <input type='hidden' name='action' value='fire'></input>
                    <input type='hidden' name='driver_id' value='$id'></input>
                    <input class='tableTopButton redButton dialog' name='send' type='submit' value='" . put("fire_driver", $l) . "'>
                </form>


            ";

    //Fahrer übersicht
} else {

    if (isset($post["action"]) AND $post["action"] == "confirm_sell" AND queryDriverIsNotRacing($id)) {
        //Teil auf den Markt schmeißen

        // # unnötig
        $num = $post["price"];
        $num = preg_replace('~[^0-9|^.|(?=2.)]~', '', $num);
        if ($num >= 0.1 && $num < 100000000000)
            $sell = queryDriverSell($id, $num);
        else
            $sell = "sell_check_input";

        $output .= "<span class='dealInfoText $sell'>";
        $output .= put($sell, $l);
        $output .= "</span>";
    }



    $output .= "<div id='cardealer'>";

    $drivers = queryDrivers();
    if ($drivers)
        foreach ($drivers as $drvr) {//$car["title"]
            $did = $drvr["id"];
            $driver_id = $drvr["driver_id"];
            $name = $drvr["name"];
            $country = $drvr["country"];
            $skill = showSkill($drvr["skill"]);
            $liga = $drvr["liga"];
            $anteil = $drvr["anteil"];
            $nameChanged = $drvr["nameChanged"];

            $output .= " 
                <div class='sysDriver'>
                <h2>" . getNameChanged($name, $nameChanged) . "  <div class='driverLiga'>
                            " . levelImg($liga) . "</div></h2>
                ".outputDriverInfo($country, $skill, $anteil)."  <!--[$driver_id]-->
                 

                
                <div class='tuneFooter absolute'>
              
                ";

            $output .= "<form method='POST' action='$path'>";
            $output .= "<input type='hidden' name='driver_id' value='$did'></input>";
            $output .= "<input class='tableTopButton' name='manage' type='submit' value='" . put("open_driver", $l) . "'>";
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