<?php

//TODO
// Salt + in DB speichern

$config['sql_hostname'] = 'localhost';    //MySQL-Server
$config['sql_username'] = 'root';        //Benutzername
$config['sql_password'] = '';        //Kennwort
$config['sql_database'] = 'rumblerace';        //Datenbank

/**
 *    Fehlerbehandlung
 */
error_reporting(E_ALL);
ini_set('display_errors', true);


/**
 *    Verbindungsaufbau
 */
$mysqli = new MySQLi($config['sql_hostname'], $config['sql_username'], $config['sql_password'], $config['sql_database']);

if (mysqli_connect_errno() != 0 || !$mysqli->set_charset('utf8')) {
    die('<strong>ERROR:</strong> Es konnte keine Verbindung mit dem Datenbank-Server hergestellt werden!');
}

// *************************************************************************
// *************************************************************************
// *************************************************************************

function hash5($pass) {
    return md5($pass . "Ich bin witzig");
}

function querySQL($sql) {
    global $mysqli;
    $entry = mysqli_query($mysqli, $sql) or die($sql . "<br/>Error: " . mysqli_error($mysqli));
    return $entry;
}

function queryExistsUser($user) {
    global $mysqli;
    $sql = "SELECT id FROM user WHERE username = '" . mysqli_real_escape_string($mysqli, $user) . "'";
    $query = querySQL($sql);
    $row = mysqli_fetch_assoc($query);

    if ($row['id']) {
        return $row['id'];
    } else {
        return false;
    }
}

function queryLogin($user, $pass) {
    global $mysqli;
    if ($user && !empty($pass)) {
        $pass = hash5($pass);

        $sql = "SELECT id, username, lang FROM user WHERE username = '" . mysqli_real_escape_string($mysqli, $user) .
                "' AND pass = '" . mysqli_real_escape_string($mysqli, $pass) . "'";
        $entry = querySQL($sql);

        $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

        if (count($row) >= 1) {
            //Falls User gefunden wurde
            $_SESSION['user_id'] = $row["id"];
            $_SESSION['username'] = $row["username"];
            $_SESSION['lang'] = $row["lang"];
            $status = "ok_user";
        } else {
            $status = 'no_user_found';
        }
    } else {
        $status = 'no_correct_input';
    }

    return $status;
}

function queryRegister($user, $pass, $email) {
    global $mysqli;
    $lang = "de";

    mysqli_autocommit($mysqli, FALSE);

    $addUser = mysqli_query($mysqli, "INSERT INTO user (username, pass, email, lang, regdate)
                VALUES ('" . mysqli_real_escape_string($mysqli, $user) . "', "
            . "'" . mysqli_real_escape_string($mysqli, hash5($pass)) . "', "
            . "'" . mysqli_real_escape_string($mysqli, $email) . "', '$lang', '" . time() . "')");
    $user_id = mysqli_insert_id($mysqli);
    $addStats = mysqli_query($mysqli, "INSERT INTO stats (id, money, liga) VALUES ('" . $user_id . "', 10000, 1)");
    if ($addUser && $addStats) {
        mysqli_commit($mysqli);
        $status = "ok_reg";
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $user;
        $_SESSION['lang'] = $lang;
    } else {
        mysqli_rollback($mysqli);
        $status = "bad_reg";
    }
    mysqli_autocommit($mysqli, TRUE);

    return $status;
}

function queryLangChange($lang) {
    $sql = "UPDATE user
            SET lang = '$lang'
            WHERE id = '" . $_SESSION["user_id"] . "'"; #

    $entry = querySQL($sql);

    if ($entry) {
        $_SESSION["lang"] = $lang;
        return true;
    } else {
        return false;
    }
}

function queryPlayerStats() {

    $sql = "SELECT money, liga, exp FROM stats WHERE id = '" . $_SESSION["user_id"] . "' LIMIT 1";

    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return $row;
    } else {
        return false;
    }
}

function queryNewCars($liga) {

    $sql = "SELECT * FROM new_cars WHERE liga <= $liga ORDER BY liga ASC, preis ASC, ps ASC";

    $entry = querySQL($sql);
    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row;
        }
        return $data;
    } else {
        return false;
    }
}

function queryNewCarCost($model) {
    global $mysqli;

    $sql = "SELECT preis FROM new_cars WHERE name = '" . mysqli_real_escape_string($mysqli, $model) . "' LIMIT 1";

    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return $row["preis"];
    } else {
        return false;
    }
}

function queryCarBuy($model, $cost) {
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    $addCar = mysqli_query($mysqli, "INSERT INTO garage (user_id, car_id) 
            values ('" . $_SESSION["user_id"] . "', '" . mysqli_real_escape_string($mysqli, $model) . "')"
    );
    $spend = mysqli_query($mysqli, "UPDATE stats
            SET money = money - $cost
            WHERE id = '" . $_SESSION["user_id"] . "'"
    );
    if ($addCar && $spend) {
        mysqli_commit($mysqli);
        $out = "car_bought";
    } else {
        mysqli_rollback($mysqli);
        $out = "database_error";
    }
    mysqli_autocommit($mysqli, TRUE);
    return $out;
}

function queryPlayerCars() {
    $sql = "SELECT nc.id, gr.id as garage_id, gr.car_id, nc.title, nc.ps, nc.liga as carLiga, nc.name, nc.perf
            FROM garage gr
                INNER JOIN new_cars nc 
                    ON gr.car_id = nc.name 
            WHERE gr.user_id = '" . $_SESSION["user_id"] . "' AND gr.sell = '0'
            ORDER BY nc.liga DESC, nc.ps DESC";

    $entry = querySQL($sql);

    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row;
        }
    }

    if (isset($data)) {
        return $data;
    } else {
        return array();
    }
}

function queryPlayerCarID($id) {
    global $mysqli;
    $sql = "SELECT gr.id as garage_id, gr.car_id, nc.title, nc.ps, nc.liga
            FROM garage gr
                INNER JOIN new_cars nc 
                    ON gr.car_id = nc.name 
            WHERE gr.user_id = '" . $_SESSION["user_id"] . "' AND gr.id = '" . mysqli_real_escape_string($mysqli, $id) . "'";
    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return $row;
    } else {
        return "database_error";
    }
}

function queryPlayerPartsID($id) {
    global $mysqli;
    $sql = "SELECT sr.part, sr.value, sr.liga, pa.kat
            FROM storage sr
                INNER JOIN parts pa
                    ON pa.part = sr.part
            WHERE user_id = '" . $_SESSION["user_id"] . "' AND garage_id = '" . mysqli_real_escape_string($mysqli, $id) . "'";
    $entry = querySQL($sql);

    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[$row["part"]] = array("kat" => $row["kat"], "value" => $row["value"], "liga" => $row["liga"]);
        }
    }
    if (isset($data)) {
        return $data;
    } else {
        return array();
    }
}


function queryTuningKats() {
    //Gibt nur die Namen zurück.
    $sql = "SELECT kat FROM parts";
    $entry = querySQL($sql);

    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row["kat"];
        }
        return array_unique($data);
    } else {
        return false;
    }
}

function queryTuningParts($kat) {
    global $mysqli;
    $sql = "SELECT part FROM parts WHERE kat = '" . mysqli_real_escape_string($mysqli, $kat) . "' AND liga > '0'";
    $entry = querySQL($sql);

    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row["part"];
        }
        return array_unique($data);
    } else {
        return false;
    }
}

function queryTuningPartsData($kat) {
    global $mysqli;
    //Gibt nur die Namen zurück.
    $sql = "SELECT * FROM parts WHERE kat = '" . mysqli_real_escape_string($mysqli, $kat) . "' ORDER BY liga desc";
    $entry = querySQL($sql);

    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row;
        }
        return $data;
    } else {
        return false;
    }
}

function queryPartData($part, $liga) {
    global $mysqli;
    $sql = "SELECT id, preis, duration FROM parts WHERE part = '" . mysqli_real_escape_string($mysqli, $part) . "' AND liga = '" . mysqli_real_escape_string($mysqli, $liga) . "'";
    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return $row;
    } else {
        return "database_error";
    }
}

function isPartRunning() {
    $sql = "SELECT pa.part as part
            FROM storage_run sr 
            INNER JOIN parts pa
                ON pa.id = sr.part_id
            WHERE sr.user_id = '" . $_SESSION["user_id"] . "'";
    $entry = querySQL($sql);


    if ($entry) {
        $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);
        return $row["part"];
    } else {
        return false;
    }
}

function queryPartBuy($part_id, $price, $dur) {
    global $mysqli;
    $time_end = time() + $dur;
    mysqli_autocommit($mysqli, FALSE);

    $addPart = mysqli_query($mysqli, "INSERT INTO storage_run (user_id, part_id, time_end) 
            values ('" . $_SESSION["user_id"] . "', '" . mysqli_real_escape_string($mysqli, $part_id) . "', '" . $time_end . "')"
    );
    $spend = mysqli_query($mysqli, "UPDATE stats
            SET money = money - $price
            WHERE id = '" . $_SESSION["user_id"] . "'"
    );
    if ($addPart && $spend) {
        mysqli_commit($mysqli);
        $out = "part_built";
    } else {
        mysqli_rollback($mysqli);
        $out = "database_error";
    }
    mysqli_autocommit($mysqli, TRUE);
    return $out;
}

function queryRunningPartTime($part) {
    global $mysqli;
    $sql = "SELECT sr.time_end as time_end, pa.duration as duration FROM storage_run sr
            LEFT JOIN parts pa
                ON pa.id = sr.part_id
                WHERE pa.part = '" . mysqli_real_escape_string($mysqli, $part) . "' AND sr.user_id = '" . $_SESSION["user_id"] . "'";
    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);
        return $row;
    } else {
        return false;
    }
}

function queryPartsBuildingDone() {
    global $mysqli;
    $sql = "SELECT sr.id as storage_id,
                sr.time_end as time_end, 
                pa.duration as duration,
                sr.user_id as user_id,
                sr.part_id as part_id,
                pa.worst as worst,
                pa.best as best,
                pa.liga as liga,
                pa.part as part
            FROM storage_run sr
            LEFT JOIN parts pa
                ON pa.id = sr.part_id
                WHERE sr.user_id = '" . $_SESSION["user_id"] . "'";
    $entry = querySQL($sql);

    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row;
        }
    } else
        return;
    if (!isset($data))
        return;
    foreach ($data as $part) {
        $time_to_end = $part["time_end"] - time();

        if ($time_to_end <= 0) {
            //Teil ist fertig, muss verschoben werden ins Lager
            mysqli_autocommit($mysqli, FALSE);
            $part_id = $part["part_id"];
            $storage_id = $part["storage_id"];
            $value = getValue($part["worst"], $part["best"]);

            $addBuiltPart = mysqli_query($mysqli, "INSERT INTO storage (user_id, part_id, value, liga, part) 
                values ('" . $_SESSION["user_id"] . "', '" . mysqli_real_escape_string($mysqli, $part_id) . "', '" . $value . "', '" . $part["liga"] . "', '" . $part["part"] . "')"
            );
            $deleteProgress = mysqli_query($mysqli, "DELETE
                FROM storage_run
                WHERE id = '$storage_id'"
            );
            if ($addBuiltPart && $deleteProgress) {
                mysqli_commit($mysqli);
                $out = "part_done";
            } else {
                mysqli_rollback($mysqli);
                $out = "database_error";
            }
            mysqli_autocommit($mysqli, TRUE);
        }
    }
}

function queryStorage() {
    $sql = "SELECT sr.id as id, sr.value as value, pa.liga as liga, pa.part as part, pa.kat as kat, sr.garage_id
            FROM storage sr
            LEFT JOIN parts pa
                ON pa.id = sr.part_id
            WHERE sr.user_id = '" . $_SESSION["user_id"] . "' AND sr.sell = 0
            ORDER BY part ASC, value DESC";
    $entry = querySQL($sql);

    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row;
        }
        if (isset($data))
            return $data;
    } else {
        return false;
    }
}
//checkt, ob user ein auto mit der $id besitrzt.
function queryUserHasCarID($id) {
    global $mysqli;

    $sql = "SELECT id FROM garage WHERE user_id = '" . $_SESSION["user_id"] . "' AND id = '" . mysqli_real_escape_string($mysqli, $id) . "' AND sell = '0'";
    $entry = querySQL($sql);

    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

    if (count($row) >= 1) {
        return true;
    } else
        return false;
}


function queryRaces($liga) {
    global $mysqli;
    $sql = "SELECT * FROM races WHERE liga = '" . mysqli_real_escape_string($mysqli, $liga) . "' ORDER BY exp asc";
    $entry = querySQL($sql);
    $data = [];
    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row;
        }
        return $data;
    } else {
        return false;
    }
}

function queryRaceData($race_id) {
    global $mysqli;
    $sql = "SELECT * FROM races WHERE id = '" . mysqli_real_escape_string($mysqli, $race_id) . "'";
    $entry = querySQL($sql);
    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return $row;
    } else {
        return false;
    }
}

//checkt, ob das auto $car_id dem user gehört und gerade KEIN rennen fährt
function queryCarIsNotRacing($id) {
    global $mysqli;

    $sql = "SELECT gr.id FROM garage gr
            LEFT JOIN races_run rr
            ON gr.id = rr.car_id
            WHERE gr.user_id = '" . $_SESSION["user_id"] . "' AND gr.id = '" . mysqli_real_escape_string($mysqli, $id) . "' AND gr.sell = '0' AND rr.user_id IS NULL";
    $entry = querySQL($sql);

    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

    if (count($row) >= 1) {
        return true;
    } else
        return false;
}

//checkt, ob das angestrebte rennen freigeschaltte ist
function queryUserCanRace($race_id, $exp) {
    global $mysqli;

    $sql = "SELECT rc.id FROM races rc
            WHERE rc.exp_needed <= '" . mysqli_real_escape_string($mysqli, $exp) . "' AND rc.id = '" . mysqli_real_escape_string($mysqli, $race_id) . "'";
    $entry = querySQL($sql);

    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

    if (count($row) >= 1) {
        return true;
    } else
        return false;
}

function queryRacing($car_id, $race_id, $dur) {
    global $mysqli;
    $time_end = time() + $dur;
    mysqli_autocommit($mysqli, FALSE);

    $sql = "INSERT INTO races_run (user_id, car_id, race_id, time_end) 
            values ('" . $_SESSION["user_id"] . "', " . mysqli_real_escape_string($mysqli, $car_id) . ", '$race_id', '" . $time_end . "')";
    //echo $sql;
    $start_race = mysqli_query($mysqli, $sql
    );
    
    $spend = true;
    if ($start_race && $spend) {
        mysqli_commit($mysqli);
        $out = "race_started";
    } else {
        mysqli_rollback($mysqli);
        $out = "database_error";
    }
    mysqli_autocommit($mysqli, TRUE);
    return $out;
}

//checkt, ob rennen fertig ist
function queryRaceDone() {
    global $mysqli;
    $sql = "SELECT rc.ps as ps,
                    rc.reward as reward,
                    rc.exp as exp,
                    rr.id as id,
                    rr.time_end as time_end
            FROM races_run rr
            INNER JOIN races rc
                ON rr.race_id = rc.id
                WHERE rr.user_id = '" . $_SESSION["user_id"] . "'";
    $entry = querySQL($sql);

    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row;
        }
    } else
        return;
    if (!isset($data))
        return;
    //geht jedes laufende rennen des users durch
    foreach ($data as $race) {
        $time_to_end = $race["time_end"] - time();

        if ($time_to_end <= 0) {
            
            //Rennen ist fertig
            mysqli_autocommit($mysqli, FALSE);
            $id = $race["id"];
            $reward = $race["reward"];
            $exp = $race["exp"];

            $reward_granted = mysqli_query($mysqli, "UPDATE stats 
                SET money = money + '$reward', exp = exp + '$exp'
                WHERE id = '" . $_SESSION["user_id"] . "'"
            );
            $deleteRace = mysqli_query($mysqli, "DELETE
                FROM races_run
                WHERE id = '$id'"
            );
            if ($reward_granted && $deleteRace) {
                mysqli_commit($mysqli);
                queryNewMessage($_SESSION["user_id"], 0, "Race finished.", "You made ".dollar($reward).".");
                $out = "race_done";
            } else {
                mysqli_rollback($mysqli);
                $out = "database_error";
            }
            mysqli_autocommit($mysqli, TRUE);
            
        }
    }
}


function queryTuningTheCar($changeIDs, $garage_id) {
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    foreach ($changeIDs as $key => $id) { //Jedes geänderte Teil bearbeiten, $key: kruemmer, $id: 16
        //if old part is setted, nothing is going to happen
        if ($id == "none")
            continue;

        //remove old part from car
        $remove = mysqli_query($mysqli, "UPDATE storage SET garage_id = '0' WHERE user_id = '" . $_SESSION["user_id"] . "' AND part = '" . mysqli_real_escape_string($mysqli, $key) . "' AND garage_id = '" . mysqli_real_escape_string($mysqli, $garage_id) . "'");
        //add new part to car
        $add = mysqli_query($mysqli, "UPDATE storage SET garage_id = '" . mysqli_real_escape_string($mysqli, $garage_id) . "' WHERE user_id = '" . $_SESSION["user_id"] . "' AND id = '" . mysqli_real_escape_string($mysqli, $id) . "' AND garage_id = '0'");
        //echo $sql. "<br/>";
        if ($remove && $add) {
            // bla bla
        } else {
            mysqli_rollback($mysqli);
            return "database_error";
        }
    }

    mysqli_commit($mysqli);

    mysqli_autocommit($mysqli, TRUE);
    return "car_updated";
}

//Selling and Buying

function queryPartSell($str_id, $num) {
    global $mysqli;
    $sql = "UPDATE storage SET sell = '" . mysqli_real_escape_string($mysqli, $num) . "', sell_date = '" . time() . "' WHERE id = '" . mysqli_real_escape_string($mysqli, $str_id) . "' AND user_id = '" . $_SESSION["user_id"] . "' AND garage_id = '0'";
    $entry = querySQL($sql);

    if ($entry) {
        return "part_on_market";
    } else {
        return "part_not_found";
    }
}

function queryMarketParts($s, $getAll) {

    $max = 25;
    $start = $s * $max - $max;

    if ($s == 0)
        $limit = "";
    else
        $limit = " LIMIT $start, $max";

    $sql = "SELECT DISTINCT sr.id, sr.part_id, sr.part, sr.liga, sr.value, sr.sell, us.username, pa.kat
            FROM storage sr
            LEFT JOIN user us
                ON us.id = sr.user_id
            LEFT JOIN parts pa
                ON pa.part = sr.part
            WHERE sr.sell > 0
            ORDER BY sr.sell_date DESC";
    $entry = querySQL($sql . $limit);

    if ($getAll) {
        //Menge aller Seiten zurückgeben
        $menge = mysqli_num_rows($entry);
        $seiten = $menge / $max;
        return $seiten;
        
    } else {
        //Aktuelle Seite zurückgeben
        if ($entry) {
            while ($row = mysqli_fetch_assoc($entry)) {
                $data[] = $row;
            }
            if (isset($data))
                return $data;
        } else {
            return false;
        }
    }
}

function queryMarketPartData($id) {
    global $mysqli;
    $sql = "SELECT sr.user_id, sr.part_id, sr.part, sr.liga, sr.value, sr.sell, pa.kat
            FROM storage sr
            LEFT JOIN parts pa
                ON pa.id = sr.part_id
            WHERE sr.id = '" . mysqli_real_escape_string($mysqli, $id) . "'";
    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);
        return $row;
    } else {
        return false;
    }
}

function queryMarketPartCost($model) {
    global $mysqli;

    $sql = "SELECT sell FROM storage WHERE id = '" . mysqli_real_escape_string($mysqli, $model) . "' LIMIT 1";

    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return $row["sell"];
    } else {
        return false;
    }
}

function keepPart($price, $str_id) {
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    $remove = "UPDATE stats 
                SET money = money - '" . ($price / 10) . "'
                WHERE id = '" . $_SESSION["user_id"] . "'";
    $setMoney = querySQL($remove);

    $sql = "UPDATE storage SET sell = '0', sell_date = 0 WHERE id = '$str_id'";
    $setSell = querySQL($sql);

    if ($setMoney && $setSell) {
        mysqli_commit($mysqli);
        return "part_back";
    } else {
        mysqli_rollback($mysqli);
        return "database_error";
    }

    mysqli_autocommit($mysqli, TRUE);
}

function updatePart($price, $old_id, $str_id) {
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    $buyer = "UPDATE stats 
                SET money = money - '$price'
                WHERE id = '" . $_SESSION["user_id"] . "'";
    $seller = "UPDATE stats 
                SET money = money + '$price'
                WHERE id = '" . $old_id . "'";
    $setBuyer = querySQL($buyer);
    $setSeller = querySQL($seller);

    $sql = "UPDATE storage SET sell = '0', sell_date = 0, user_id = '" . $_SESSION["user_id"] . "' WHERE id = '$str_id'";
    $setSell = querySQL($sql);

    if ($setSell && $setBuyer && $setSeller) {
        queryNewMessage($old_id, 0, "Part sold on market", "Your part was sold on the market for " . dollar($price) . ".");
        mysqli_commit($mysqli);
        return "part_bought";
    } else {
        mysqli_rollback($mysqli);
        return "database_error";
    }

    mysqli_autocommit($mysqli, TRUE);
}

function queryMarketPartBuy($id) {
    global $mysqli;



    $check = "SELECT sell, user_id FROM storage WHERE id = '" . mysqli_real_escape_string($mysqli, $id) . "' AND sell > 0";
    $entry = querySQL($check);
    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

    if ($row["user_id"] == $_SESSION["user_id"]) {

        return keepPart($row["sell"], $id);
    } else if (count($row) >= 1) {

        return updatePart($row["sell"], $row["user_id"], $id);
    } else
        return "part_sold";
}

function areThereMessenges() {

    $sql = "SELECT id FROM faxes WHERE to_id = '" . $_SESSION["user_id"] . "' AND open = '0'";
    $entry = querySQL($sql);

    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

    if (count($row) >= 1) {
        return true;
    } else
        return false;
}

function queryMessages() {
    $sql = "SELECT fx.id, fx.from_id, fx.open, fx.date, fx.betreff, fx.message, us.username
            FROM faxes fx
            LEFT JOIN user us
                ON us.id = fx.from_id
            WHERE fx.to_id = " . $_SESSION["user_id"] . "
            ORDER BY fx.date DESC";
    $entry = querySQL($sql);

    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row;
        }
        if (isset($data))
            return $data;
    } else {
        return false;
    }
}

function queryMessageData($id) {
    global $mysqli;
    $sql = "SELECT fx.from_id, fx.date, fx.betreff, fx.message, fx.open, us.username, us.lang
            FROM faxes fx
            LEFT JOIN user us
                ON fx.from_id = us.id
            WHERE fx.id = '" . mysqli_real_escape_string($mysqli, $id) . "'";
    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);
        if ($row["open"] == 0)
            querySQL("UPDATE faxes SET open ='1' WHERE id = '" . mysqli_real_escape_string($mysqli, $id) . "'");
        return $row;
    } else {
        return false;
    }
}

function queryNewMessage($to_id, $from_id, $betreff, $message) {
    global $mysqli;
    $sql = "INSERT INTO faxes (to_id, from_id, betreff, message, date, open)
            VALUES ('" . mysqli_real_escape_string($mysqli, $to_id) . "', '" . mysqli_real_escape_string($mysqli, $from_id) . "', '" . mysqli_real_escape_string($mysqli, $betreff) . "', '" . mysqli_real_escape_string($mysqli, $message) . "', '" . time() . "', '0')";

    $entry = querySQL($sql);

    if ($entry) {
        return "message_sent";
    } else
        return "database_error";
}

function queryLigaChange() {
    $liga = getPlayerLiga();
    $exp = getPlayerExp();
    $up = 1;
    
    if($exp > 2000000)
        $up = 8;
    else if ($exp > 800000)
        $up = 7;
    else if ($exp > 200000)
        $up = 6;
    else if ($exp > 64000)
        $up = 5;
    else if ($exp > 10000)
        $up = 4;
    else if ($exp > 3000)
        $up = 3;
    else if ($exp > 350)
        $up = 2;
    
    if($up > $liga)
        upgradeLiga($up);
}

function upgradeLiga($liga) {
    querySQL("UPDATE stats SET liga = $liga WHERE id = '" . $_SESSION["user_id"] . "'");
}
