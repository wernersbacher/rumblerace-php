<?php

/*
 * EVENT Log
 */
$output = "";

$log_output = "";

$logs = queryLogs();
if (!$logs) {
    logGeneric("welcome");
    $logs = queryLogs();
}


foreach ($logs as $key => $log) {
    $type = $log["message_code"];
    $date = $log["date"];
    $open = $log["open"];
    $props = json_decode($log["properties"], TRUE);
    //$logs[$key]["properties"] = $props;
    $html = "";

    switch ($type) {
        case "race_done":
             $html = sprintf(put("log_race_done_sprintf", $l), getRaceName($props["name"], $l), $props["position"], dollar($props["reward"]), ep($props["exp"]));
           
            break;
        case "new_level":
            $html = put("log_advanced", $l) . " " . $props["liga"] . ".";
            break;
        case "part_sold":
            $html = put($props["part"], $l) . " " . put("log_sold_for", $l) . " " . dollar($props["price"]) . ".";
            break;
        case "sprit_sold":
            $html = sprintf(put("log_sprit_sold_sprintf", $l), gas($props["amount"]), dollar($props["price"]), dollar($props["cost"]));
            break;
        case "welcome":
            $html = put("log_welcome_msg", $l);
            break;
    }

    $log_output .= "<div class='sysDriver'>";
    $log_output .= "<h2>" . put("log_" . $type, $l) . "</h2>";
    $log_output .= "<div class='sec_time'>".date("M, d |  H:i:s", $date)."</div>";
    $log_output .= $html;
    $log_output .= "</div>";
}


$output .= $log_output;




//mark as read instantly
markLogAsRead();
