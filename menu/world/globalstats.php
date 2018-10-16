<?php

$output = outputTut("global_stats", $l);

$output .= "<table class='tableRed tableClick stats'><tr>
                  <th colspan='4'>Most money</th>
                </tr>";

$moneyTop = mostMoney();
$expTop = mostExp();

$i = 0;
foreach ($moneyTop as $user) {
    $i++;
    $usrname = $user["username"];
    $liga = $user["liga"];
    $money = dollar($user["money"]);
    $link = "?page=world&sub=profiles&user=$usrname";
    $output .= "<tr>
                <td class='stat_td'>#$i</td>
                <td class='stat_tdx2'><a href='$link'>$usrname</a></td>
                <td>$money</td>
                <td class='stat_tdx2'>".put("liga", $l)." $liga</td>
              </tr>";
}
$output .= "</table>";

$output .= "<table class='tableRed tableClick stats'><tr>
                  <th colspan='4'>Most exp</th>
                </tr>";
$i = 0;
foreach ($expTop as $user) {
    $i++;
    $usrname = $user["username"];
    $liga = $user["liga"];
    $exp = ep($user["exp"]);
    $link = "?page=world&sub=profiles&user=$usrname";
    $output .= "<tr>
                <td class='stat_td'>#$i</td>
                <td class='stat_tdx2'><a href='$link'>$usrname</a></td>
                <td>$exp</td>
                <td class='stat_tdx2'>".put("liga", $l)." $liga</td>
              </tr>";
}
$output .= "</table>";