<?php

$output = outputTut("driverm_info", $l);

$path = "?page=$page&sub=$sub";

$output .= "<div id='market'>";

//Blätterseite abfragen
$menge = ceil(queryMarketDriver(0, true));
if (isset($get["s"]) AND $get["s"] <= $menge)
    $s = $get["s"];
else
    $s = 1;

$pages = getPages($menge, $s, $path);
$output .= $pages;

$output .= "<table style='font-size:13px;' class='tableRed table100 noclick'>
                <tr>
                  <th colspan='3'>" . put("market", $l) . "</th>
                </tr>";

//Aktuelle Seite auslesen
$driverMarket = queryMarketDriver($s, false);
if ($driverMarket)
    foreach ($driverMarket as $item) {

        $driver_id = $item["driver_id"];
        $name = $item["name"];
        $country = $item["country"];
        $skill = showSkill($item["skill"]);
        $liga = $item["liga"];
        $anteil = $item["anteil"];
        $nameChanged = $item["nameChanged"];

        $link = $path . "&mode=buy&id=" . $item["drv_id"];

        $output .= "<tr>";
        $output .= "
                <td class='partTitle'>
                    <a class='maxSize centerChildVertical2' href='$link'>
                        <div class='partTitleFormat'>" . getNameChanged($name, $nameChanged) . "  <div class='driverLiga'>
                            " . levelImg($liga) . "</div></div>
                    </a>
                    </td>
                <td class='partPerf'><a class='maxSize centerChildVertical2' href='$link'><div>" . getFlag($country) . " Skill: <b>$skill%</b></div></a></td>
                <td><a class='maxSize' href='$link'>" . dollar($item["sell"]) . "<br/>" . $item["username"] . "</a></td>
                
                <!--<td>
                    <a href='$link'><button class='tableTopButton'>" . put("buy", $l) . "</button></a>
                </td>-->
                    ";
        $output .= "</tr>";
    } else {
    $output .= "<tr><td colspan='4'>" . put("market_empty", $l) . "</td></tr>";
}

$output .= "</table>";
$output .= $pages;



$output .= "</div>";





$output .= "</div>";
