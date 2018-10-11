<?php


//TODO
// Salt + in DB speichern

$config['sql_hostname'] = 'localhost';    //MySQL-Server
$config['sql_username'] = 'werner';        //Benutzername
$config['sql_password'] = 't4g23ww';        //Kennwort
$config['sql_database'] = 'werner_rr';        //Datenbank

/**
 *    Fehlerbehandlung
 */
error_reporting(E_ALL);
ini_set('display_errors', true);


/**
 *    Verbindungsaufbau
 */
$mysqli = new MySQLi($config['sql_hostname'], $config['sql_username'], $config['sql_password'], $config['sql_database']);

if (mysqli_connect_errno() != 0 || !$mysqli->set_charset('utf8')) {
    die('<strong>ERROR:</strong> Es konnte keine Verbindung mit dem Datenbank-Server hergestellt werden!');
}

/*
 * BASE FUNCS
 */

function hash5($pass) {
    return md5($pass . "Ich bin witzig");
}

function querySQL($sql) {
    global $mysqli;
    $entry = mysqli_query($mysqli, $sql) or die($sql . "<br/>Error: " . mysqli_error($mysqli));
    return $entry;
}

function getColumn($sql) {
    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return $row;
    } else {
        return false;
    }
}

function getRow($sql) {
    return getColumn($sql)[0];
}

function getCount($sql) {
    $entry = querySQL($sql);
    $count = mysqli_num_rows($entry);
    return $count;
}

function getArray($sql) {

    $entry = querySQL($sql);
    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row;
        }
        if (isset($data))
            return $data;
    } else {
        return false;
    }
}
