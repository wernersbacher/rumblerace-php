<?php

/*
 * SQL Funktionen, die sich um die Auslesen der Notifications kümmern
 */

function getNotificationsArray() {
    global $m;

    /*
     * durch $m loopen, um array zu generieren, am ende summe für eltern erstellen
     */

    $return = [
        "messages" => 1
    ];

    foreach ($m as $page => $info) {
        $subs = $info["subs"];
        $return[$page] = 0; //Page initalisieren
        foreach ($subs as $kat) {
            //Falls keine Ausgabe generiert wurde, dann Standard auf 0 setzen
            if (!array_key_exists($kat, $return))
                $return[$kat] = 0;
            //Hochzählen der Page um den Wert der Kat
            $return[$page] += $return[$kat];
        }
    }


    return $return;
}
