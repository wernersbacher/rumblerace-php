<?php

/*
 * AJAX OUTPUT: minimum sprit price on market. 
 */

require_once '../_mysql.php';

$sql = "SELECT MIN(price) as min FROM sprit_market";

$result = getColumn($sql);

echo $result["min"];