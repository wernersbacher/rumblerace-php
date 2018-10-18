<?php

/*
 * SQL Funktionen, die sich um die Auslesen der Notifications kümmern
 */

function areThereMessenges() {

    $sql = "SELECT COUNT(id) as num FROM faxes WHERE to_id = '" . $_SESSION["user_id"] . "' AND open = '0'";
    $entry = querySQL($sql);

    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);
    $c = intval($row["num"]);
    if ($c) {
        return $c;
    } else
        return 0;
}


function isThereBonus() {
    $sql = "SELECT user_id FROM bonus WHERE user_id = '" . $_SESSION["user_id"] . "' AND last + 3600 < " . time();

    $entry = querySQL($sql);

    $count = mysqli_num_rows($entry);

    return $count;
}


/*
 * Generierung der Notifies
 */

function checkNotifies() {
    
    $return = [
        // getCurrentRunningRaces() ?
        "messages" => areThereMessenges(),
        "bonus" => isThereBonus(),
        "secretary" => getNewLogNum(),
        "upgrades" => getPlayerUpPoints()
    ];
    
    return $return;
}

function getNotifications() {
    global $m;

    /*
     * durch $m loopen, um array zu generieren, am ende summe für eltern erstellen
     */

    $return = checkNotifies();

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
