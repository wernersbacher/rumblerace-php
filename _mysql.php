<?php

//TODO
// Salt + in DB speichern

$config['sql_hostname'] = 'localhost';    //MySQL-Server
$config['sql_username'] = 'werner';        //Benutzername
$config['sql_password'] = 't4g23ww';        //Kennwort
$config['sql_database'] = 'werner_rr';        //Datenbank

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
            
            login($row["id"], $row["username"], $row["lang"]);
            $status = "ok_user";
        } else {
            $status = 'no_user_found';
        }
    } else {
        $status = 'no_correct_input';
    }

    return $status;
}

function storeLoginForUser($id, $token) {
    $sql = "INSERT INTO loggedin (user_id, token, created) VALUES ('$id', '$token', '".time()."') ON DUPLICATE KEY UPDATE token = '$token', created = '".time()."';";
    querySQL($sql);
}

function delete_session($id) {
    $sql = "UPDATE loggedin SET token = '' WHERE user_id = '$id'";
    querySQL($sql);
}

function getTokenByUserID($id) {
    global $mysqli;
    $sql = "SELECT * FROM loggedin WHERE user_id = '" . mysqli_real_escape_string($mysqli, $id) . "'";
    $entry = querySQL($sql);
    
    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return $row["token"];
    } else {
        return false;
    }
}

function countEmail($email) {
    global $mysqli;
    $sql = "SELECT id FROM user WHERE email = '" . mysqli_real_escape_string($mysqli, $email) . "'";
    $entry = querySQL($sql);
    
    $count = mysqli_num_rows($entry);
    
    return $count;
}

function updateEmail($email) {
    $sql = "UPDATE user SET email = '$email' WHERE id = '" . $_SESSION["user_id"] . "'";
    querySQL($sql);
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
    $addStats = mysqli_query($mysqli, "INSERT INTO stats (id, money, liga, sprit) VALUES ('" . $user_id . "', 6000, 1, 50)");
    $addSprit = mysqli_query($mysqli, "INSERT INTO sprit_upt (user_id, updated) VALUES ('" . $user_id . "', '".time()."')");
    $addCar = mysqli_query($mysqli, "INSERT INTO garage (user_id, car_id) VALUES ('" . $user_id . "', 'beamer_pole')");
    if ($addUser && $addStats && $addSprit && $addCar) {
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
    return queryPlayerByID($_SESSION["user_id"]);
}

function queryPlayerByID($id) {

    $sql = "SELECT * FROM stats, user WHERE stats.id = user.id AND stats.id = '" . $id . "' LIMIT 1";
    
    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return $row;
    } else {
        return false;
    }
}

function queryPlayerByMail($email) {
    $sql = "SELECT * FROM stats, user WHERE stats.id = user.id AND email = '$email' LIMIT 1";
    
    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return $row;
    } else {
        return false;
    }
}

function isTokenValid($email, $token) {
    $sql = "SELECT token_date, token FROM user WHERE email = '$email'";
    $entry = querySQL($sql);
    
    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        //Falls das Datum nicht älter als 24h her ist und der Token richtig ist.
        if($row["token_date"] + 24*60*60 > time() AND $token == $row["token"]) {
            return true;
        } else return false;
    } else {
        return false;
    }
    
}

function changePass($pass, $email) {
    $sql = "UPDATE user SET pass = '" . hash5($pass) . "' WHERE email = '$email'";
    querySQL($sql);
    
}

function queryAddBugreport($text, $id, $time) {
    querySQL("INSERT INTO bugs (user_id, text, time) values ('$id', '$text', '$time')");
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
    $sql = "SELECT gr.id as garage_id, gr.car_id, nc.title, nc.ps, nc.liga, nc.perf
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

function queryTuningPartsAll() {
    //Gibt nur die Namen zurück.
    $sql = "SELECT part FROM parts";
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
    $sql = "SELECT pa.part as part, pa.kat as kat
            FROM storage_run sr 
            INNER JOIN parts pa
                ON pa.id = sr.part_id
            WHERE sr.user_id = '" . $_SESSION["user_id"] . "'";
    $entry = querySQL($sql);


    if ($entry) {
        $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);
        return array($row["part"], $row["kat"]);
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
    $sql = "SELECT * FROM races WHERE liga = '" . mysqli_real_escape_string($mysqli, $liga) . "' ORDER BY exp_needed asc";
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
function queryUserCanRace2($race_id, $exp, $sprit) {
    global $mysqli;

    $sql = "SELECT rc.id FROM races rc
            WHERE rc.exp_needed <= '" . mysqli_real_escape_string($mysqli, $exp) . "' AND rc.sprit_needed <= '$sprit' AND rc.id = '" . mysqli_real_escape_string($mysqli, $race_id) . "'";
    $entry = querySQL($sql);

    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

    if (count($row) >= 1) {
        return true;
    } else
        return false;
}
function queryUserCanRace($race_id, $exp, $sprit) {
    global $mysqli;

    $sql = "SELECT * FROM races rc
            WHERE rc.id = '" . mysqli_real_escape_string($mysqli, $race_id) . "' LIMIT 1";
    $entry = querySQL($sql);

    //var_dump($row);
    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        if($row["exp_needed"] > $exp)
            return "exp";
        else if($row["sprit_needed"] > $sprit) {
            return "sprit";
        } else return true;
        
    } else {
        return false;
    }
}

//startet neues rennen
function queryRacing($car_id, $race_id, $dur, $sprit, $driver_id) {
    global $mysqli;
    $time_end = time() + $dur;
    mysqli_autocommit($mysqli, FALSE);

    $sql = "INSERT INTO races_run (user_id, car_id, race_id, time_end, driver_id) 
            values ('" . $_SESSION["user_id"] . "', " . mysqli_real_escape_string($mysqli, $car_id) . ", '$race_id', '" . $time_end . "', '$driver_id')";
    //echo $sql;
    $start_race = mysqli_query($mysqli, $sql
    );

    $spend = true;
    if ($start_race && $spend) {
        mysqli_commit($mysqli);
        $out = "race_started";
        //
        removeSprit($sprit);
    } else {
        mysqli_rollback($mysqli);
        $out = "database_error";
    }
    mysqli_autocommit($mysqli, TRUE);
    return $out;
}

function queryRunningRaces() {
    $sql = "SELECT rc.reward as reward,
                    rr.time_end as time_end,
                    rr.car_id as car_id,
                    rc.name as name,
                    rc.dur as duration,
                    rr.id as id
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
        return false;
    if (!isset($data))
        return false;
    else
        return $data;
}

//checkt, ob rennen fertig ist HINZUFÜGEN DER EXP ZUM FAHRER
function queryRaceDone() {
    global $mysqli, $l;
    $sql = "SELECT rc.ps as ps,
                    rc.reward as reward,
                    rc.exp as exp,
                    rr.id as id,
                    rr.time_end as time_end,
                    rr.car_id as car_id,
                    rc.name as name,
                    rr.driver_id
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

            //$driver = getDriverByID($race["driver_id"]);
            $id = $race["id"];
            $reward = calcReward($race["reward"], $race["ps"], $race["exp"], $race["car_id"], $race["driver_id"]);
            $exp = calcExpReward($race["exp"], $race["ps"], $race["exp"], $race["car_id"], $race["driver_id"]);

            $reward_granted = mysqli_query($mysqli, "UPDATE stats 
                SET money = money + '$reward', exp = exp + '$exp'
                WHERE id = '" . $_SESSION["user_id"] . "'"
            );
            $sql_deb = "UPDATE fahrer 
                SET skill = skill + '$exp'
                WHERE user_id = '" . $_SESSION["user_id"] . "' AND id = '".$race["driver_id"]."'";
            //var_dump($sql_deb);
            $driver_reward = mysqli_query($mysqli, $sql_deb
            );
            
            $deleteRace = mysqli_query($mysqli, "DELETE
                FROM races_run
                WHERE id = '$id'"
            );
            if ($reward_granted && $deleteRace && $driver_reward) {
                mysqli_commit($mysqli);
                queryNewMessage($_SESSION["user_id"], 0, getRaceName($race["name"]) . " finished.", "You made " . dollar($reward) . " and " . ep($exp) . "!");
                $out = "race_done";
            } else {
                mysqli_rollback($mysqli);
                $out = "database_error";
            }
            mysqli_autocommit($mysqli, TRUE);
        }
    }
     
}

function queryCancelRace($race_id) {
    global $mysqli;
    $sql = "DELETE
                FROM races_run
                WHERE id = '" . mysqli_real_escape_string($mysqli, $race_id) . "' AND user_id = '" . $_SESSION["user_id"] . "'";
    $entry = querySQL($sql);
    if ($entry) {
        return "race_canc";
    } else
        return "database_error";
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

function queryMarketParts($s, $getAll, $partFilter, $ligaFilter) {
    global $mysqli;
    $max = 25;
    $start = $s * $max - $max;

    if ($s == 0)
        $limit = "";
    else
        $limit = " LIMIT $start, $max";

    $sql = "SELECT DISTINCT sr.id, sr.part_id, sr.part, sr.liga, sr.value, sr.sell, us.username, pa.kat
            FROM storage sr
            INNER JOIN user us
                ON us.id = sr.user_id
            INNER  JOIN parts pa
                ON pa.part = sr.part
            WHERE sr.sell > 0 AND sr.part LIKE '" . mysqli_real_escape_string($mysqli, $partFilter) . "' AND sr.liga LIKE '" . mysqli_real_escape_string($mysqli, $ligaFilter) . "'
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

function queryDeleteSystem() {
    $sql = "DELETE FROM faxes WHERE from_id = '0' AND to_id = '" . $_SESSION["user_id"] . "'";
    querySQL($sql);
}

function queryReadAll() {
    $sql = "UPDATE faxes SET open ='1' WHERE to_id = '" . $_SESSION["user_id"] . "'";
    querySQL($sql);
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
            ORDER BY fx.date DESC
                LIMIT 100";
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

    if ($exp > 2000000)
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

    if ($up > $liga)
        upgradeLiga($up);
}

function upgradeLiga($liga) {
    querySQL("UPDATE stats SET liga = $liga WHERE id = '" . $_SESSION["user_id"] . "'");
    queryNewMessage($_SESSION["user_id"], 0, "New League", "Congratulations, you advanced to league $liga!");
}

function queryFabrikTeile() {
    $sql = "SELECT sp.id, usr.count, sp.title, sp.lit, sp.liga, sp.cost
            FROM sprit sp
            LEFT JOIN sprit_usr usr
            ON sp.id = usr.sprit_id AND usr.user_id = '" . $_SESSION["user_id"] . "' 
            ORDER BY liga, lit ASC";

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

function querySpritPartCost($teil_id) {
    global $mysqli;

    $sql = "SELECT usr.count, sp.cost
            FROM sprit sp
            LEFT JOIN sprit_usr usr
            ON sp.id = usr.sprit_id AND usr.user_id = '" . $_SESSION["user_id"] . "'
            WHERE sp.id = '" . mysqli_real_escape_string($mysqli, $teil_id) . "' LIMIT 1";

    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return calcCost($row["cost"],$row["count"]);
    } else {
        return false;
    }
}

function queryUserHasTeil($teil_id) {
    global $mysqli;

    $sql = "SELECT id FROM sprit_usr WHERE user_id = '" . $_SESSION["user_id"] . "' AND sprit_id = '" . mysqli_real_escape_string($mysqli, $teil_id) . "'";
    $entry = querySQL($sql);

    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

    if (count($row) >= 1) {
        return true;
    } else
        return false;
}

function querySpritTeilBuy($teil_id, $cost) {
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    if (queryUserHasTeil($teil_id)) { //falls schon einmal besessen
        $sql = "UPDATE sprit_usr SET count = count+1 WHERE user_id = '" . $_SESSION["user_id"] . "' AND sprit_id = '" . mysqli_real_escape_string($mysqli, $teil_id) . "'";
    } else { //Falls noch kein teil existiert
        $sql = "INSERT INTO sprit_usr 
            SET user_id = '" . $_SESSION["user_id"] . "', sprit_id = '" . mysqli_real_escape_string($mysqli, $teil_id) . "', count = '1'";
    }
    $addCar = mysqli_query($mysqli, $sql);
    $spend = mysqli_query($mysqli, "UPDATE stats
            SET money = money - $cost
            WHERE id = '" . $_SESSION["user_id"] . "'"
    );
    if ($addCar && $spend) {
        mysqli_commit($mysqli);
        $out = "teil_bought";
    } else {
        mysqli_rollback($mysqli);
        $out = "database_error";
    }
    mysqli_autocommit($mysqli, TRUE);
    return $out;
}

//Liest alle Spritteile des Users aus
function querySpritUser() {
    $sql = "SELECT *
            FROM sprit_usr usr
            LEFT JOIN sprit sp
            ON usr.sprit_id = sp.id
            WHERE usr.user_id = '" . $_SESSION["user_id"] . "'";
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

//Checkt, wann das letzte Mal Sprit gutgeschrieben wurde
function getLastSpritUpdate() {
    $sql = "SELECT updated
            FROM sprit_upt usr
            WHERE usr.user_id = '" . $_SESSION["user_id"] . "'";
    $entry = querySQL($sql);
    
    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return $row["updated"];
    } else {
        return false;
    }
}

//Addiert den Sprit des Users zum Konto
function querySpritAdd() {
    
    $sprit = calcNewSprit();
    
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    $addCar = mysqli_query($mysqli, "UPDATE stats
            SET sprit = $sprit
            WHERE id = '" . $_SESSION["user_id"] . "'");
    $spend = mysqli_query($mysqli, "UPDATE sprit_upt
            SET updated = '".time()."'
            WHERE id = '" . $_SESSION["user_id"] . "'"
    );
    if ($addCar && $spend) {
        mysqli_commit($mysqli);
        $out = "spit_added";
    } else {
        mysqli_rollback($mysqli);
        $out = "database_error";
    }
    mysqli_autocommit($mysqli, TRUE);
    return $out;
    
}

//Gibt Sprit aus
function removeSprit($sprit) {
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    $addCar = mysqli_query($mysqli, "UPDATE stats
            SET sprit = sprit - $sprit
            WHERE id = '" . $_SESSION["user_id"] . "'");
    $spend = mysqli_query($mysqli, "UPDATE sprit_upt
            SET updated = '".time()."'
            WHERE id = '" . $_SESSION["user_id"] . "'"
    );
    if ($addCar && $spend) {
        mysqli_commit($mysqli);
        $out = "spit_rem";
    } else {
        mysqli_rollback($mysqli);
        $out = "database_error";
    }
    mysqli_autocommit($mysqli, TRUE);
    return $out;
}

//Checkt, ob user diesen Fahrer schon besitzt
function queryUserHasNotDriverID($driver_id) {
    global $mysqli;

    $sql = "SELECT id FROM fahrer
            WHERE user_id = '" . $_SESSION["user_id"] . "' AND driver_id = '" . mysqli_real_escape_string($mysqli, $driver_id) . "'";
    $entry = querySQL($sql);

    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

    if (count($row) >= 1) {
        return false;
    } else
        return true;
}

function queryNewDriver($driver_id, $name, $skill, $liga, $kosten, $anteil) {
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    $sql1 = "INSERT INTO fahrer (driver_id, user_id, name, skill, liga, anteil) 
            values ('$driver_id', '" . $_SESSION["user_id"] . "', '$name', '$skill', '$liga', '$anteil')";
    //echo $sql1;
    
    $addCar = mysqli_query($mysqli, $sql1);
    $spend = mysqli_query($mysqli, "UPDATE stats
            SET money = money - $kosten
            WHERE id = '" . $_SESSION["user_id"] . "'"
    );
    
    if ($addCar && $spend) {
        mysqli_commit($mysqli);
        $out = "driver_added";
    } else {
        mysqli_rollback($mysqli);
        $out = "database_error2";
    }
    mysqli_autocommit($mysqli, TRUE);
    return $out;
}

function queryDriversIDs() {
    $sql = "SELECT driver_id
            FROM fahrer
            WHERE user_id = '" . $_SESSION["user_id"] . "'";
    $entry = querySQL($sql);
     if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row["driver_id"];
        }
        if (isset($data))
            return $data;
    } else {
        return false;
    }
}

function queryDrivers() {
    $sql = "SELECT *
            FROM fahrer
            WHERE user_id = '" . $_SESSION["user_id"] . "' ORDER BY liga DESC, skill DESC";
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

//checkt, ob der fahrer $driver_id dem user gehört und gerade KEIN rennen fährt
function queryDriverIsNotRacing($id) {
    global $mysqli;

    $sql = "SELECT fr.id FROM fahrer fr
            LEFT JOIN races_run rr
            ON fr.id = rr.driver_id
            WHERE fr.user_id = '" . $_SESSION["user_id"] . "' AND fr.id = '" . mysqli_real_escape_string($mysqli, $id) . "' AND rr.user_id IS NULL";
    $entry = querySQL($sql);

    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

    if (count($row) >= 1) {
        return true;
    } else
        return false;
}

function getDriverByID($id) {
    $sql = "SELECT *
            FROM fahrer
            WHERE user_id = '" . $_SESSION["user_id"] . "' AND id = '$id' LIMIT 1";
    $entry = querySQL($sql);
     if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row;
        }
        if (isset($data))
            return $data[0];
    } else {
        return false;
    }
}

function removeDriverByID($id) {
    global $mysqli;
    $sql = "DELETE
                FROM fahrer
                WHERE id = '" . mysqli_real_escape_string($mysqli, $id) . "'";
    $entry = querySQL($sql);
    
    if($entry)
        return "driver_fired";
    else return "database_error";
}

function changeDriverName($id, $name) {
    $sql = "UPDATE fahrer
            SET name = '$name'
            WHERE id = '$id'"; #

    querySQL($sql);
}

function saveToken($email, $now, $token) {
    $sql = "UPDATE user SET token = '$token', token_date = '$now' WHERE email = '$email'";
    querySQL($sql);
}