<?php
include("../_checkuser.php"); 

require dirname(__DIR__). '/vendor/autoload.php';

$options = array(
    'cluster' => 'eu',
    'encrypted' => true
);

$pusher = new Pusher(
        'b41c8eb316335d2af468', '1672a9ccf1c759caf193', '293270', $options
);

/* 
 * Hier müssen geschickte Nachrichten überprüft werden:
 * Session, Schimpwörter, Ban etc
 * 
 * Die Nachrichten kommen dann in die DB und werden mit dem JS Befehl an alle Clients geschickt.
 */

$post = $_POST;

$type = $post["type"];
$msg = htmlentities(substr($post["message"],0,200));
saveToDB($_SESSION["username"], $msg);

$data['message'] = $msg;
$data['sender'] = $_SESSION["username"];
$data["time"] = time();
$pusher->trigger('main-chat', 'new-msg', json_encode($data));
