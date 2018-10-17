<?php

/*
 * MySQL Functions for Logging game events
 * jscon decode und dann checken welche var, um log anzuzeigen
 */

function logRaceDone($name, $position, $reward, $exp) {
    $obj = [
        "name" => $name,
        "position" => $position,
        "reward" => $reward,
        "exp" => $exp
    ];
    $serial = json_encode($obj);
    return queryNewLog($_SESSION["user_id"], "race_done", $serial);
}

function queryNewLog($to_id, $message_code, $properties) {
    global $mysqli;
    $sql = "INSERT INTO sys_log (to_id, message_code, properties, date, open)
            VALUES ('" . mysqli_real_escape_string($mysqli, $to_id) . "', 
                '" . mysqli_real_escape_string($mysqli, $message_code) . "', '" . mysqli_real_escape_string($mysqli, $properties) . "', 
                    '" . time() . "', '0')";

    $entry = querySQL($sql);

    if ($entry) {
        return "log_created";
    } else
        return "database_error";
}
