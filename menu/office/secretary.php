<?php

/*
 * EVENT Log
 */
$output = "";

$logs = queryLogs();

$log_output = "";

foreach ($logs as $key => $log) {
    $type = $log["message_code"];
    $props = json_decode($log["properties"], TRUE);
    //$logs[$key]["properties"] = $props;
    $html = "";

    switch ($type) {
        case "race_done":
            $html = "Your race is finished. Your position: #" . $props["position"] . "<br/>
                You won " . dollar($props["reward"]) . ".";
            break;
        case "new_level":
            $html = "New level aquired!";
            break;
        case "part_sold":
            $html = "Your part was sold on the market.";
            break;
    }

    $log_output .= "<h2>" . put("log_" . $type, $l) . "</h2>";
    $log_output .= $html;
}



$output .= $log_output;




//mark as read instantly
markLogAsRead();
