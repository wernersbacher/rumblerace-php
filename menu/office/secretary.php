<?php

/*
 * EVENT Log
 */
$output = "";

$log_output = "";

$logs = queryLogs();
if ($logs)
    foreach ($logs as $key => $log) {
        $type = $log["message_code"];
        $props = json_decode($log["properties"], TRUE);
        //$logs[$key]["properties"] = $props;
        $html = "";

        switch ($type) {
            case "race_done":
                $html = put("log_race_fin", $l) . " #" . $props["position"] . "<br/>
                " . put("log_reward", $l) . ": " . dollar($props["reward"]) . " & " . ep($props["exp"]) . ".";
                break;
            case "new_level":
                $html = put("log_advanced", $l) . " " . $props["liga"] . ".";
                break;
            case "part_sold":
                $html = put($props["part"], $l) . " " . put("log_sold_for", $l) . " " . dollar($props["price"]) . ".";
                break;
            case "sprit_sold":
                $html = "You sold " . gas($props["amount"]) . " at " . dollar($props["price"]) . " (" . dollar($props["cost"]) . ").";
                $html = sprintf(put("log_sprit_sold_sprintf", $l), gas($props["amount"]), dollar($props["price"]), dollar($props["cost"]));
                break;
        }

        $log_output .= "<div class='log_item'>";
        $log_output .= "<h2>" . put("log_" . $type, $l) . "</h2>";
        $log_output .= $html;
        $log_output .= "</div>";
    }



$output .= $log_output;




//mark as read instantly
markLogAsRead();
