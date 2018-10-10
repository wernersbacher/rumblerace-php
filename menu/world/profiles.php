<?php

$output = outputTut("profile_info", $l);
$output .= "<div id='profiles'>";

//Falls User gesucht wird...
if(isset($post["search"])) {
    $user = filter_input_array(INPUT_POST)["search"];
} else $user = false;


//User suchen

$output .= '<form class="bigForm" action="?page=world&sub=profiles" method="post">
                        <input width="100" type="text" name="search" placeholder="Username" value="'.$user.'" maxlength="20"> 
                        <input type="submit" name="send" value="'.put("search",$l).'">

                     </form>';


//Blätterseite abfragen
$menge = ceil(queryUserList(0, true, $user));
if (isset($get["s"]) AND $get["s"] <= $menge)
    $s = $get["s"];
else
    $s = 1;


//Seiten berechnen und ausgeben
$pages = getPages($menge, $s, "?page=world&sub=profiles");
$output .= $pages;

//Tabelle ausgeben
$output .= "<table style='font-size:13px;' class='tableRed noclick'>
                <tr>
                  <th colspan='3'>" . put("profile", $l) . "</th>
                </tr>";

//Aktuelle Seite auslesen
$userList = queryUserList($s, false, $user);
if ($userList)
    foreach ($userList as $user) {
        $usrname = $user["username"];
        $i = $user["id"];
        $link = "?page=world&sub=profiles&user=$usrname";
        $link_msg = "?page=office&sub=messages&mode=new&to=$usrname";

        $output .= "<tr>
                <td class=''>#$i</td>
                <td class=''><a href='$link'>$usrname</a></td>
                <td class=''><a href='$link'><button class='tableTopButton'>" . put("profile", $l) . "</button></a> <a href='$link_msg'><button class='tableTopButton'>" . put("write_msg", $l) . "</td>
                
              </tr>";
    } else {
    $output .= "<tr><td colspan='4'>" . put("no_profile_found", $l) . "</td></tr>";
}

$output .= "</table>";










$output .= "</div>";
