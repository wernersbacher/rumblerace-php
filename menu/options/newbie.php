<?php

$output = outputTut("not_done", $l);


$output .= "<h2>Technical questions?</h2>";

$output .= "The method to calculate the car part value looks like this:<br/>";

$string = '<?php 
    function getExpRand($min, $max) {
    $ran = rand(0,99999999)/100000000; //calculating a random
    $random = 3.3 * -pow($ran-0.6,3) + 0.3; //3.3*-(x-0.6)^(3)+0.3 
    
    return floor($min-1+($max-$min+1)* $random);
}
?>';

$output .= highlight_string($string, true);