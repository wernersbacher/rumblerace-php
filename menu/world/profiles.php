<?php

$output = outputTut("profile_info", $l);
$output .= "<div id='profiles'>";

//Wenn ein User mitgegeben wird, soll das profil aufgerufen werden.
$user_data = false;
if(isset($get["user"])) 
    $user_data = queryProfileByName($get["user"]);

//Zeige das Profil an, wenn user_id existiert

if($user_data) {
    
    $username = $user_data["username"];
    $regdate = $user_data["regdate"];
    $activeTime = $user_data["activeTime"];
    $lang = $user_data["lang"];
    
    $money = $user_data["money"];
    
    $output .= backLink("?page=world&sub=profiles");
    
    $output .= "
            <div class='sysDriver' id='user_profile'>
                <h2><img src='img/" . $lang . ".png' alt='Language' /> $username</h2>
                
                <div class='profile_info'>
                    <span>Reg date</span>
                    <span>".date("Y-m-d H:i:s", $regdate)."</span>
                </div>
                <div class='profile_info'>
                    <span>Last online</span>
                    <span>".date("Y-m-d H:i:s", $activeTime)."</span>
                </div>
                <div class='profile_info'>
                    <span>Money:</span>
                    <span>".dollar($money)."</span>
                </div>
            </div>

        ";
    
    
    
    
    return; //Aus dem Include rausgehen, d.h. die Tabelle wird nicht mehr angezeigt.
} 




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
