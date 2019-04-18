<?php

$config['sql_hostname'] = 'localhost';    //MySQL-Server
$config['sql_username'] = 'werner';        //Benutzername
$config['sql_password'] = 't4g23ww';        //Kennwort
$config['sql_database'] = 'werner_names';        //Datenbank

/**
 *    Fehlerbehandlung
 */
error_reporting(E_ALL);
ini_set('display_errors', true);


/**
 *    Verbindungsaufbau
 */
$mysqli_names = new MySQLi($config['sql_hostname'], $config['sql_username'], $config['sql_password'], $config['sql_database']);

if (mysqli_connect_errno() != 0 || !$mysqli_names->set_charset('utf8')) {
    die('<strong>ERROR:</strong> Es konnte keine Verbindung mit dem Datenbank-Server hergestellt werden!');
}

function queryNamesSQL($sql) {
    global $mysqli_names;
    $entry = mysqli_query($mysqli_names, $sql) or die($sql . "<br/>Error: " . mysqli_error($mysqli_names));
    return $entry;
}