<?php 

include("_function.php");


$array = [];

for($i=1;$i<100000;$i++) {
    $key = (int)getExpRand(20,40);
    //echo $key." key<br/>";
    if(array_key_exists($key, $array))
        $array[$key]++;
    else $array[$key] = 1;
}

ksort($array);

foreach($array as $key => $num) {
    echo $key.": x".$num."<br/>";
}