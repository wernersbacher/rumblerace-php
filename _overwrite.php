<?php

/*
 * Überschreiben von PHP eigenen Funktionen.
 */

/*
 * 7.2 PHP Warning Fix
 */

function __count($obj) {
    if (is_array($obj) || $obj instanceof Countable)
        return count($obj);
    else
        return false;
}
