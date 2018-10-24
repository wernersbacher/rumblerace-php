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

$sys_cats = ["races",
    "sold",
    "other",
];

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
            $css = "races";

            break;
        case "new_level":
            $html = put("log_advanced", $l) . " " . $props["liga"] . ".";
            $css = "other";
            break;
        case "part_sold":
            $html = put($props["part"], $l) . " " . put("log_sold_for", $l) . " " . dollar($props["price"]) . ".";
            $css = "sold";
            break;
        case "sprit_sold":
            $html = sprintf(put("log_sprit_sold_sprintf", $l), gas($props["amount"]), dollar($props["price"]), dollar($props["cost"]));
            $css = "sold";
            break;
        case "welcome":
            $html = put("log_welcome_msg", $l);
            $css = "other";
            break;
    }

    $log_output .= "<div class='sysDriver sys_$css'>";
    $log_output .= "<h2>" . put("log_" . $type, $l) . "</h2>";
    $log_output .= "<div class='sec_time'>" . date("M, d |  H:i:s", $date) . "</div>";
    $log_output .= $html;
    $log_output .= "</div>";
}


//Toggle System nachrichten
$output .= "<div id='sys_toggles' class='messageButtons'>";
foreach ($sys_cats as $sys_cat) {

    $output .= "<div class='centerChildVertical'><a href='#' data-cat='$sys_cat' id='toggle_$sys_cat' class='tableTopButton toggle_sys'>" . put("toggle_$sys_cat", $l) . "</a></div>";
}
$output .= "</div>";


$output .= "<div id='scroll_log'>";
$output .= $log_output;
$output .= "</div>";



//mark as read instantly
markLogAsRead();
