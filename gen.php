<?php

require __DIR__ . '/vendor/autoload.php';
$factory = new RandomLib\Factory;
$generator = $factory->getHighStrengthGenerator();

define("SECRET_KEY", "pJXwZRwK4BTuuy9KdXTUcimO@8+i2VSpTqOT@/99Fqb8HExN80l7C/xiU08cQJA2");

function GenerateRandomToken() {
    global $generator;
    $randomStringLength = 64;
    $randomStringAlphabet = '0123456789@abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+/';
    $randomString = $generator->generateString($randomStringLength, $randomStringAlphabet);

    return $randomString;
}

