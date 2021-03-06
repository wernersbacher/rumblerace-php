<?php

$output = "";

function sendNewMail($to_user, $subject, $message) {
    global $l;
    $user_id = queryExistsUser($to_user);
    $subject = substr($subject, 0, 13);

    if ($user_id AND strlen($message) > 1 AND strlen($message) < 10000) {
        if (strlen($subject) < 1)
            $subject = "<i>" . put("no_title", $l) . "</i>";
        $status = queryNewMessage($user_id, $_SESSION["user_id"], $subject, $message);
    } else {
        $status = "check_mes_input";
    }

    return $status;
}

if ($mode == "new") {
    $to_user = "";
    $message = "";
    $subject = "";
    
    //falls geantwortet wird
    if(isset($get["to"])) {
        $to_user = $get["to"];
    }
    
    //Falls etwas falsches eingegeben wurde
    if (isset($post["send"]) AND isset($post["message"]) AND isset($post["to_user"])) {
        $to_user = $post["to_user"];
        $message = $post["message"];

        if (isset($post["subject"]) AND strlen($post["subject"]) > 0)
            $subject = $post["subject"];


        $send = sendNewMail($to_user, $subject, $message);

        $output .= "<span class='dealInfoText $send'>";
        $output .= put($send, $l);
        $output .= "</span>";

        if ($send == "message_sent") {
            $to_user = "";
            $message = "";
            $subject = "";
        }
    }

    $output .= "<div id='messageBox'>";
    $output .= backLink("?page=$page&sub=$sub");
    $output .= "<div id='messageOutput'><h2>Write a new message</h2>";
    $output .= "<div class='messageSub'>";

    $output .= "<form method='post' action='?page=$page&sub=$sub&mode=new'>
                    <input type='text' maxlength='13' name='to_user' placeholder='Receiver' value='$to_user' required><br/>
                    <input type='text' maxlength='13' name='subject' placeholder='Subject' value='$subject' /><br/>
                    <textarea name='message' placeholder='Your message' required>$message</textarea><br/>
                    <input type='submit' name='send' value='" . put("send_mes", $l) . "' class='tableTopButton' />
                </form>";

    $output .= "</div>";
    $output .= "</div>";
    $output .= "</div>";
} else if ($mode == "read" && isset($post["m_id"])) {
    $output .= outputTut("messages_write_back", $l);
    $output .= backLink("?page=$page&sub=$sub");
    $m_id = $post["m_id"];

    $fxData = queryMessageData($m_id);
    if ($fxData) { //Wenn Nachricht geladen wurde
        $username = $fxData["username"];
        $user_id = $fxData["from_id"];
        $lang = $fxData["lang"];
        if ($user_id == 0) {
            $username = "System";
            $lang = "de";
        }
        $output .= "<div id='messageBox'>";

        $output .= "<div id='messageOutput'><h2>" . $fxData["betreff"] . "</h2>
                        " . put("from", $l) . " " . user($fxData["from_id"], $username) . " <img src='img/" . $lang . ".png' alt='Language' /> , " . date("d M Y H:i", $fxData["date"]) . "
                        <div class='messageSub'>
                        " . htmlentities($fxData["message"]) . "
                        </div>
                        <a href='?page=$page&sub=$sub&mode=new&to=$username' class='tableTopButton'>+ " . put("answer", $l) . "</a>
                    </div>";

        $output .= "</div>";
        
        markAsRead($m_id);
    } else {
        $output .= "Nachricht wurde gelöscht.";
    }
} else {
    
    if(isset($post["delSys"])) {
        queryDeleteSystem();
        $del = outputTut("sms_del", $l);
    } else if(isset($post["delOld"])) {
        queryDeleteOld();
        $del = outputTut("sms_del", $l);
    } else if(isset($post["readAll"])) {
        queryReadAll();
        $del = outputTut("sms_read", $l);
    } else $del = "";

    $output .= outputTut("messages_info", $l);
    $output .= $del;
    $output .= "<div id='messageBox'>";
    $output .= "<div class='messageButtons'>";

    //Neue Nachricht Button
    $output .= "<div class='centerChildVertical'><a href='?page=$page&sub=$sub&mode=new' class='tableTopButton'>+ " . put("new_mes", $l) . "</a></div>";
    
    //Lösche alle Nachrichten, älter als 30 tage
    $output .= "<form class='centerChildVertical' method='POST' data-dialog='Really delete old messages?' style='display:inline-block;' action='?page=$page&sub=$sub'><input type='hidden' name='delOld' value='delOld'><input class='tableTopButton dialog' name='delSys' type='submit' value='" . put("delOld", $l) . "'></form>";
    
    //Alle Nachrichten als gelesen markieren
    $output .= "<form class='centerChildVertical' method='POST' style='display:inline-block;' action='?page=$page&sub=$sub'><input type='hidden' name='readAll' value='read'><input class='tableTopButton' name='readAll' type='submit' value='" . put("readAll", $l) . "'></form>";

    //Toggle System nachrichten
    $output .= "<div class='centerChildVertical'><a href='#' id='toggle_sys' class='tableTopButton'>" . put("toggle_sys", $l) . "</a></div>";
    
    $output .= "</div>"; 
    
    //Tabellen Header
    $output .= "<table style='font-size:13px;' class='tableRed messages noclick'>
                <tr>
                  <th>" . put("absender", $l) . "</th>
                  <th>" . put("betreff", $l) . "</th>
                  <th>Status</th>
                  <th></th>
                </tr>";

    $messages = queryMessages();

    if ($messages)
        foreach ($messages as $item) {

            $link = "?page=$page&sub=$sub&mode=read";
            if ($item["open"] == 0)
                $status = "<span class='unread'>" . put("unread", $l) . "</span>";
            else
                $status = put("readit", $l);

            if ($item["from_id"] > 0) {
                $username = $item["username"];
                $class = "user";
            } else {
                $username = "System";
                $class = "sys";
            }

            $output .= "<tr class='$class'>";
            $output .= "<td>" . $username . "</td>
                <td>" . put($item["betreff"], $l) . "</td>
                <td>$status</td>
                <td>
                    <form method='post' action='$link'>
                        <input type='hidden' name='m_id' value='" . $item["id"] . "'/>
                        <input class='tableTopButton smallTableTop' type='submit' name='open' value='" . put("read", $l) . "' />
                    </form>
                </td>";
            $output .= "</tr>";
        } else {
        $output .= "<tr><td colspan='4'>" . put("message_empty", $l) . "</td></tr>";
    }

    $output .= "</table></div>";
}