<?php

/*
 * MySQL Functions for Logging game events
 * jscon decode und dann checken welche var, um log anzuzeigen
 */

/*
 * spezielle Log Funktionen
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

/*
 * read Logs
 */

function queryLogs() {
    $sql = "SELECT * 
            FROM sys_log
            WHERE to_id = " . $_SESSION["user_id"] . "
            ORDER BY date DESC
                LIMIT 50";

    // Convert props json to array

    $raw = getArray($sql);


    return $raw;
}

/*
 * Create logs, mark as read
 */

function markLogAsRead() {
    $sql = "UPDATE sys_log SET open = 1 WHERE to_id = '" . $_SESSION["user_id"] . "'";
    querySQL($sql);
}

function getNewLogNum() {
    $sql = "SELECT COUNT(id) as num FROM sys_log WHERE to_id = '" . $_SESSION["user_id"] . "' AND open = '0'";
    $entry = querySQL($sql);

    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);
    $c = intval($row["num"]);
    if ($c) {
        return $c;
    } else
        return 0;
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
