<?php

/*
 * MySQL Functions for Logging game events
 * jscon decode und dann checken welche var, um log anzuzeigen
 */

/*
 * spezielle Log Funktionen
 */

function logGeneric($type) {
    $obj = [
        "type" => $type
    ];
    return queryNewLog($type, $obj);
}

function logSpritSold($type, $amount, $price, $cost, $seller_id) {
    $obj = [
        "type" => $type,
        "price" => $price,
        "amount" => $amount,
        "cost" => $cost
        
    ];
    return queryNewLog($type, $obj, $seller_id);
}

function logPartSold($type, $liga, $part, $seller_id) {
    $obj = [
        "type" => $type,
        "part" => $part,
        "price" => $liga,
    ];
    return queryNewLog($type,$obj, $seller_id);
}

function logNewLevel($type, $liga) {
    $obj = [
        "type" => $type,
        "liga" => $liga,
    ];
    return queryNewLog($type, $obj);
}

function logRaceDone($name, $position, $reward, $exp) {
    $obj = [
        "name" => $name,
        "position" => $position,
        "reward" => $reward,
        "exp" => $exp
    ];
    return queryNewLog("race_done", $obj);
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
    return getArray($sql);
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

function queryNewLog($type, $properties, $id = null) {
    //check if $id is there
    $id = isset($id) ? $id : $_SESSION["user_id"];
    $serial = json_encode($properties);
    global $mysqli;
    $sql = "INSERT INTO sys_log (to_id, message_code, properties, date, open)
            VALUES ('$id', 
                '" . mysqli_real_escape_string($mysqli, $type) . "', '" . mysqli_real_escape_string($mysqli, $serial) . "', 
                    '" . time() . "', '0')";

    $entry = querySQL($sql);

    if ($entry) {
        return "log_created";
    } else
        return "database_error";
}
