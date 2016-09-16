<?php

session_start();

require_once('_function.php');
require_once('_mysql.php');


if (!isset($_SESSION['user_id'])) {
    echo "no login";
} else {
    $post = (filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING));
    if(strlen($post["text"]) > 2)
        queryAddBugreport($post["text"], $_SESSION['user_id'], time());
    echo "done";
}


