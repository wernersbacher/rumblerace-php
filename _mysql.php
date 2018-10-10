<?php
require_once '_mysql_login.php';
// *************************************************************************
// *************************************************************************
// *************************************************************************



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

        if (__count($row) >= 1) {
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
    $sql = "INSERT INTO loggedin (user_id, token, created) VALUES ('$id', '$token', '" . time() . "') ON DUPLICATE KEY UPDATE token = '$token', created = '" . time() . "';";
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
    $lang = getBrowserLang();

    mysqli_autocommit($mysqli, FALSE);

    $addUser = mysqli_query($mysqli, "INSERT INTO user (username, pass, email, lang, regdate)
                VALUES ('" . mysqli_real_escape_string($mysqli, $user) . "', "
            . "'" . mysqli_real_escape_string($mysqli, hash5($pass)) . "', "
            . "'" . mysqli_real_escape_string($mysqli, $email) . "', '$lang', '" . time() . "')");
    $user_id = mysqli_insert_id($mysqli);
    $addStats = mysqli_query($mysqli, "INSERT INTO stats (id, money, liga, sprit) VALUES ('" . $user_id . "', ".$_config["vars"]["startMoney"].", 1, ".$_config["vars"]["startSprit"].")");
    $addSprit = mysqli_query($mysqli, "INSERT INTO sprit_upt (user_id, updated) VALUES ('" . $user_id . "', '" . time() . "')");
    $addCar = mysqli_query($mysqli, "INSERT INTO garage (user_id, car_id) VALUES ('" . $user_id . "', 'beamer_pole')");
    $addDriver = mysqli_query($mysqli, "INSERT INTO fahrer (user_id, driver_id, name, skill, liga, anteil) VALUES ('$user_id', '$user_id+d', 'Markus Werner', 150, 1, 5)");
    $addBonus = mysqli_query($mysqli, "INSERT INTO bonus (user_id, last, invested) VALUES ('$user_id', 0, 0)");
    if ($addUser && $addStats && $addSprit && $addCar && $addDriver && $addBonus) {
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

function queryGuestRegister($oldUser, $user, $pass) {
    global $mysqli;
    $sql = "UPDATE user SET username = '" . mysqli_real_escape_string($mysqli, $user) . "', pass = '" . hash5($pass) . "' 
        WHERE username = '" . mysqli_real_escape_string($mysqli, $oldUser) . "'
        ";
    $entry = querySQL($sql);

    if ($entry)
        return "ok_reg";
    else
        return "database_error";
}

function setOnline() {
    global $mysqli;
    $mysqli->query("UPDATE user SET activeTime = '" . time() . "' WHERE id = '" . $_SESSION["user_id"] . "'");
}

function onlineUser() {
    global $mysqli;
    $result = $mysqli->query("SELECT COUNT(*) FROM user WHERE activeTime + 300 > '" . time() . "' ");
    $row = $result->fetch_row();
    return $row[0];
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
    $pl = queryPlayerByID($_SESSION["user_id"]);
    return $pl;
}

function queryPlayerByID($id) {

    $sql = "SELECT * FROM stats, user WHERE stats.id = user.id AND stats.id = '" . $id . "' LIMIT 1";
    return getColumn($sql);
}

function queryPlayerByMail($email) {
    $sql = "SELECT * FROM stats, user WHERE stats.id = user.id AND email = '$email' LIMIT 1";

    return getColumn($sql);
}

function isTokenValid($email, $token) {
    $sql = "SELECT token_date, token FROM user WHERE email = '$email'";
    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        //Falls das Datum nicht älter als 24h her ist und der Token richtig ist.
        if ($row["token_date"] + 24 * 60 * 60 > time() AND $token == $row["token"]) {
            return true;
        } else
            return false;
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

    $sql = "SELECT * FROM new_cars WHERE liga <= $liga ORDER BY liga ASC, preis ASC, acc ASC";

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
    $sql = "SELECT nc.id, gr.id as garage_id, gr.car_id, nc.title, nc.liga as carLiga, nc.name, preis, nc.acc, nc.speed, nc.hand, nc.dura
            FROM garage gr
                INNER JOIN new_cars nc 
                    ON gr.car_id = nc.name 
            WHERE gr.user_id = '" . $_SESSION["user_id"] . "' AND gr.sell = '0'
            ORDER BY nc.liga DESC, nc.acc, nc.speed, nc.hand DESC";

    return getArray($sql);
}

function queryPlayerCarID($id) {
    global $mysqli;
    $sql = "SELECT gr.id as garage_id, gr.car_id, nc.title, nc.liga, preis, nc.acc, nc.speed, nc.hand, nc.dura
            FROM garage gr
                INNER JOIN new_cars nc 
                    ON gr.car_id = nc.name 
            WHERE gr.user_id = '" . $_SESSION["user_id"] . "' AND gr.id = '" . mysqli_real_escape_string($mysqli, $id) . "'";

    return getColumn($sql);
}

function queryPlayerPartsID($id) {
    global $mysqli;
    $sql = "SELECT sr.part, sr.liga, pa.kat, sr.acc, sr.speed, sr.hand, sr.dura
            FROM storage sr
                INNER JOIN parts pa
                    ON pa.part = sr.part
            WHERE user_id = '" . $_SESSION["user_id"] . "' AND garage_id = '" . mysqli_real_escape_string($mysqli, $id) . "'";
    $entry = querySQL($sql);

    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[$row["part"]] = array("kat" => $row["kat"], "liga" => $row["liga"], "acc" => $row["acc"], "speed" => $row["speed"], "hand" => $row["hand"], "dura" => $row["dura"]);
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
    $sql = "SELECT DISTINCT kat FROM parts";
    $entry = querySQL($sql);

    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[] = $row["kat"];
        }
        return ($data);
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

    return getArray($sql);
}

function queryPartData($part, $liga) {
    global $mysqli;
    $sql = "SELECT id, preis, duration FROM parts WHERE part = '" . mysqli_real_escape_string($mysqli, $part) . "' AND liga = '" . mysqli_real_escape_string($mysqli, $liga) . "'";

    return getColumn($sql);
}

function isPartRunning() {
    $sql = "SELECT pa.part as part, pa.kat as kat, sr.time_end as end, sr.dur as dur
            FROM storage_run sr 
            INNER JOIN parts pa
                ON pa.id = sr.part_id
            WHERE sr.user_id = '" . $_SESSION["user_id"] . "'";
    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);
        return $row;
    } else {
        return false;
    }
}

function queryPartBuy($part_id, $price, $dur) {
    global $mysqli;
    $time_end = time() + $dur;
    mysqli_autocommit($mysqli, FALSE);

    $addPart = mysqli_query($mysqli, "INSERT INTO storage_run (user_id, part_id, time_end, dur) 
            values ('" . $_SESSION["user_id"] . "', '" . mysqli_real_escape_string($mysqli, $part_id) . "', '" . $time_end . "', '$dur')"
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
    $sql = "SELECT sr.time_end as time_end, sr.dur as saved_dur, pa.duration as duration FROM storage_run sr
            LEFT JOIN parts pa
                ON pa.id = sr.part_id
                WHERE pa.part = '" . mysqli_real_escape_string($mysqli, $part) . "' AND sr.user_id = '" . $_SESSION["user_id"] . "'";

    return getColumn($sql);
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
                pa.acc, pa.speed, pa.hand, pa.dura,
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
            /* $value = getValue($part["worst"], $part["best"]); DEL */
            $values = getValues(array("acc" => $part["acc"], "speed" => $part["speed"], "hand" => $part["hand"], "dura" => $part["dura"]));

            $addBuiltPart = mysqli_query($mysqli, "INSERT INTO storage (user_id, part_id, liga, part, acc, speed, hand, dura) 
                values ('" . $_SESSION["user_id"] . "', '" . mysqli_real_escape_string($mysqli, $part_id) . "',  '" . $part["liga"] . "', '" . $part["part"] . "', '" . $values["acc"] . "', '" . $values["speed"] . "', '" . $values["hand"] . "', '" . $values["dura"] . "')"
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
    $sql = "SELECT sr.id as id, pa.liga as liga, pa.part as part, pa.kat as kat, sr.garage_id, sr.acc, sr.speed, sr.hand, sr.dura
            FROM storage sr
            LEFT JOIN parts pa
                ON pa.id = sr.part_id
            WHERE sr.user_id = '" . $_SESSION["user_id"] . "' AND sr.sell = 0
            ORDER BY part ASC, value DESC";

    return getArray($sql);
}

//checkt, ob user ein auto mit der $id besitrzt.
function queryUserHasCarID($id) {
    global $mysqli;

    $sql = "SELECT id FROM garage WHERE user_id = '" . $_SESSION["user_id"] . "' AND id = '" . mysqli_real_escape_string($mysqli, $id) . "' AND sell = '0'";
    $entry = querySQL($sql);

    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

    if (__count($row) >= 1) {
        return true;
    } else
        return false;
}

function queryRaces($liga) {
    global $mysqli;
    $sql = "SELECT * FROM races WHERE liga = '" . mysqli_real_escape_string($mysqli, $liga) . "' ORDER BY exp_needed asc";

    return getArray($sql);
}

function queryRaceData($race_id) {
    global $mysqli;
    $sql = "SELECT * FROM races WHERE id = '" . mysqli_real_escape_string($mysqli, $race_id) . "'";

    return getColumn($sql);
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

    if (__count($row) >= 1) {
        return true;
    } else
        return false;
}

//checkt, ob das angestrebte rennen freigeschaltte ist

function queryUserCanRace($race_id, $exp, $sprit) {
    global $mysqli;

    $sql = "SELECT * FROM races rc
            WHERE rc.id = '" . mysqli_real_escape_string($mysqli, $race_id) . "' LIMIT 1";
    $entry = querySQL($sql);

    //var_dump($row);
    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        $liga = intval($row["liga"]);
        if (expToLiga($liga) * $row["exp_needed"] * getLigaQuot() > $exp) {
            return "exp";
        } else if ($row["sprit_needed"] > $sprit) {
            return "sprit";
        } else
            return true;
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
                    rr.id as id,
                    rr.driver_id
            FROM races_run rr
            INNER JOIN races rc
                ON rr.race_id = rc.id
            WHERE rr.user_id = '" . $_SESSION["user_id"] . "'";

    return getArray($sql);
}

//checkt, ob rennen fertig ist HINZUFÜGEN DER EXP ZUM FAHRER
function queryRaceDone() {
    global $mysqli, $l;
    $sql = "SELECT rc.perf_needed as pn,
                    rc.macc as macc,
                    rc.mspeed as mspeed,
                    rc.mhand as mhand,
                    rc.mdura as mdura,
                    rc.sprit_needed as sprit_needed,
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

            $id = $race["id"];
            $rewardMulti = calcRewardMulti($race["pn"], $race["macc"], $race["mspeed"], $race["mhand"], $race["mdura"], $race["exp"], $race["car_id"], $race["driver_id"]);
            
            $reward = calcDollarReward($race["sprit_needed"]) * $rewardMulti;
            $exp = $race["exp"] * $rewardMulti;
            
            $reward_granted = mysqli_query($mysqli, "UPDATE stats 
                SET money = money + '$reward', exp = exp + '$exp'
                WHERE id = '" . $_SESSION["user_id"] . "'"
            );
            $sql_deb = "UPDATE fahrer 
                SET skill = skill + '$exp'
                WHERE user_id = '" . $_SESSION["user_id"] . "' AND id = '" . $race["driver_id"] . "'";
            
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

function querySpritSell($price, $amount) {
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    $remove = "UPDATE stats 
                SET sprit = sprit - $amount
                WHERE id = '" . $_SESSION["user_id"] . "'";
    $setSprit = querySQL($remove);

    $sql = "INSERT INTO sprit_market (user_id, price, amount, timestamp) VALUES ('" . $_SESSION["user_id"] . "', '$price', '$amount', '" . time() . "')
        ON DUPLICATE KEY UPDATE amount=amount+$amount
        ";
    $setSell = querySQL($sql);

    if ($setSprit && $setSell) {
        mysqli_commit($mysqli);
        return "sprit_selling";
    } else {
        mysqli_rollback($mysqli);
        return "database_error";
    }

    mysqli_autocommit($mysqli, TRUE);
}

function removeItem($storage_id) {
    global $mysqli;
    $sql = "DELETE FROM storage WHERE user_id = '" . $_SESSION["user_id"] . "' AND id ='" . mysqli_real_escape_string($mysqli, $storage_id) . "'";
    querySQL($sql);
    if (mysqli_affected_rows($mysqli) > 0)
        return true;
    else
        return false;
}

function queryMarketParts($s, $getAll, $partFilter, $ligaFilter) {
    global $mysqli;
    $max = 25;
    $start = $s * $max - $max;

    if ($s == 0)
        $limit = "";
    else
        $limit = " LIMIT $start, $max";

    $sql = "SELECT DISTINCT sr.id, sr.part_id, sr.part, sr.liga, sr.sell, us.username, pa.kat, sr.acc, sr.speed, sr.hand, sr.dura
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

function queryMarketSprit($s, $getAll) {
    global $mysqli;
    $max = 25;
    $start = $s * $max - $max;

    if ($s == 0)
        $limit = "";
    else
        $limit = " LIMIT $start, $max";

    $sql = "SELECT sm.id as sm_id, user_id, amount, price, username FROM sprit_market sm, user us WHERE user_id = us.id ORDER BY price ASC, amount DESC";
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
    $sql = "SELECT sr.user_id, sr.part_id, sr.part, sr.liga, sr.sell, pa.kat, sr.acc, sr.speed, sr.hand, sr.dura
            FROM storage sr
            LEFT JOIN parts pa
                ON pa.id = sr.part_id
            WHERE sr.id = '" . mysqli_real_escape_string($mysqli, $id) . "'";

    return getColumn($sql);
}

function queryMarketSpritData($id) {
    global $mysqli;
    $sql = "SELECT *
            FROM sprit_market
            WHERE id = '" . mysqli_real_escape_string($mysqli, $id) . "'";

    return getColumn($sql);
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

function queryMarketSpritBuy($id, $amount) {
    global $mysqli;

    $check = "SELECT * FROM sprit_market WHERE id = '" . mysqli_real_escape_string($mysqli, $id) . "'";
    $entry = querySQL($check);
    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

    if ($row["user_id"] == $_SESSION["user_id"]) {
        return keepSprit($id, $row["amount"]);
    } else if ($amount > $row["amount"]) {
        return "sprit_partly_sold";
    } else if (__count($row) >= 1) {
        return updateSprit($id, $row["price"], $amount, $row["user_id"], $row["amount"]);
    } else
        return "sprit_sold";
}

function updateSprit($str_id, $price, $amount, $seller_id, $rest) {
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);
    $cost = $amount * $price;

    //Geld bewegen
    $buyer = "UPDATE stats 
                SET money = money - '$cost'
                WHERE id = '" . $_SESSION["user_id"] . "'";
    $seller = "UPDATE stats 
                SET money = money + '$cost'
                WHERE id = '" . $seller_id . "'";
    $setBuyer = querySQL($buyer);
    $setSeller = querySQL($seller);

    //Sprit aus dem Angebot nehmen
    if ($rest - $amount > 0)  //Wenn was übrig bleibt, bleibt Gebot bestehen
        $sql = "UPDATE sprit_market SET amount = amount - $amount WHERE id = '$str_id'";
    else //falls nichts mehr übrig ist, angebot löschen
        $sql = "DELETE FROM sprit_market WHERE id = '$str_id'";
    $removeSprit = querySQL($sql);

    //Sprit zum Konto hinzufügen
    $addSprit = "UPDATE stats SET sprit = sprit + $amount WHERE id = '" . $_SESSION["user_id"] . "'";
    $entrySprit = querySQL($addSprit);

    if ($removeSprit && $setBuyer && $setSeller && $entrySprit) {
        queryNewMessage($seller_id, 0, "Sprit sold on market", "You sold sprit on the market for " . dollar($cost) . ".");
        mysqli_commit($mysqli);
        return "sprit_bought";
    } else {
        mysqli_rollback($mysqli);
        return "database_error";
    }

    mysqli_autocommit($mysqli, TRUE);
}

function queryMarketSpritCost($id, $amount) {
    $data = queryMarketSpritData($id);
    return $data["price"] * $amount;
}

function keepSprit($id, $amount) {
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    $remove = "UPDATE stats 
                SET sprit = sprit + $amount
                WHERE id = '" . $_SESSION["user_id"] . "'";
    $setSprit = querySQL($remove);

    $sql = "DELETE FROM sprit_market WHERE id = '$id'";
    $setSell = querySQL($sql);

    if ($setSprit && $setSell) {
        mysqli_commit($mysqli);
        return "sprit_back_ok";
    } else {
        mysqli_rollback($mysqli);
        return "database_error";
    }

    mysqli_autocommit($mysqli, TRUE);
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
    } else if (__count($row) >= 1) {

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

    if (__count($row) >= 1) {
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

    return getArray($sql);
}

function queryMessageData($id) {
    global $mysqli;
    $sql = "SELECT fx.from_id, fx.date, fx.betreff, fx.message, fx.open, us.username, us.lang
            FROM faxes fx
            LEFT JOIN user us
                ON fx.from_id = us.id
            WHERE fx.id = '" . mysqli_real_escape_string($mysqli, $id) . "'";

    return getColumn($sql);
}

function markAsRead($id) {
    $sql = "UPDATE faxes SET open =1 WHERE id = '$id'";
    querySQL($sql);
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

    return getArray($sql);
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
        return calcCost($row["cost"], $row["count"]);
    } else {
        return false;
    }
}

function queryUserHasTeil($teil_id) {
    global $mysqli;

    $sql = "SELECT id FROM sprit_usr WHERE user_id = '" . $_SESSION["user_id"] . "' AND sprit_id = '" . mysqli_real_escape_string($mysqli, $teil_id) . "'";
    $entry = querySQL($sql);

    $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

    if (__count($row) >= 1) {
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

    $ssql = "UPDATE sprit_upt
            SET updated = '" . time() . "'
            WHERE user_id = '" . $_SESSION["user_id"] . "'";
    $spend = mysqli_query($mysqli, $ssql
    );
    if ($addCar && $spend) {
        mysqli_commit($mysqli);
        $out = "sprit_added";
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
            SET updated = '" . time() . "'
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

    if (__count($row) >= 1) {
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

    return getArray($sql);
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

    if (__count($row) >= 1) {
        return true;
    } else
        return false;
}

function getDriverByID($id) {
    global $mysqli;
    $sql = "SELECT *
            FROM fahrer
            WHERE user_id = '" . $_SESSION["user_id"] . "' AND id = '" . mysqli_real_escape_string($mysqli, $id) . "' LIMIT 1";
    return getColumn($sql);
}

function removeDriverByID($id) {
    global $mysqli;
    $sql = "DELETE
                FROM fahrer
                WHERE id = '" . mysqli_real_escape_string($mysqli, $id) . "'";
    $entry = querySQL($sql);

    if ($entry)
        return "driver_fired";
    else
        return "database_error";
}

function changeDriverName($id, $name) {
    $sql = "UPDATE fahrer
            SET name = '$name'
            WHERE id = '$id'"; #

    querySQL($sql);
}

function upgradeDriver($driver_id, $cost) {
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    $upgrade = mysqli_query($mysqli, "UPDATE fahrer SET liga = liga + 1, skill = skill *2, anteil = anteil + 1 WHERE id = $driver_id"
    );
    $spend = mysqli_query($mysqli, "UPDATE stats
            SET money = money - $cost
            WHERE id = '" . $_SESSION["user_id"] . "'"
    );
    if ($upgrade && $spend) {
        mysqli_commit($mysqli);
        $out = "driver_upgraded";
    } else {
        mysqli_rollback($mysqli);
        $out = "database_error";
    }
    mysqli_autocommit($mysqli, TRUE);
    return $out;
}

function saveToken($email, $now, $token) {
    $sql = "UPDATE user SET token = '$token', token_date = '$now' WHERE email = '$email'";
    querySQL($sql);
}

function getBonusData() {
    $sql = "SELECT * FROM bonus WHERE user_id = '" . $_SESSION["user_id"] . "'";

    return getColumn($sql);
}

function updateLastBonus() {
    $sql = "INSERT INTO bonus (user_id, last, invested) VALUES ('" . $_SESSION["user_id"] . "', '" . time() . "', '0') ON DUPLICATE KEY UPDATE last = '" . time() . "'";

    querySQL($sql);
}

function isThereBonus() {
    $sql = "SELECT user_id FROM bonus WHERE user_id = '" . $_SESSION["user_id"] . "' AND last + 3600 < " . time();

    $entry = querySQL($sql);

    $count = mysqli_num_rows($entry);

    return $count;
}

function addMoney($val) {
    $sql = "UPDATE stats SET money = money+$val WHERE id = '" . $_SESSION["user_id"] . "'";
    querySQL($sql);
}

function addSprit($val) {
    $sql = "UPDATE stats SET sprit = sprit+$val WHERE id = '" . $_SESSION["user_id"] . "'";
    querySQL($sql);
}

function addExp($val) {
    $sql = "UPDATE stats SET exp = exp+$val WHERE id = '" . $_SESSION["user_id"] . "'";
    querySQL($sql);
}

function mostMoney() {
    $sql = "SELECT money, liga, username FROM user, stats WHERE stats.id = user.id ORDER BY money DESC LIMIT 15";
    return getArray($sql);
}

function mostExp() {
    $sql = "SELECT exp, liga, username FROM user, stats WHERE stats.id = user.id ORDER BY exp DESC LIMIT 15";
    return getArray($sql);
}

function toggleAds($status) {
    global $mysqli;
    $sql = "UPDATE user SET ads = $status WHERE id = '" . $_SESSION["user_id"] . "'";
    $mysqli->query($sql);
}

function getSwitches() {
    $sql = "SELECT ads FROM user WHERE id = '" . $_SESSION["user_id"] . "'";
    return getColumn($sql);
}

function sellCarSystem($garage_id) {
    global $mysqli;
    $money = carSellPrice(queryPlayerCarID($garage_id)["preis"]);
    $sql = "DELETE FROM garage WHERE user_id = '" . $_SESSION["user_id"] . "' AND id ='" . mysqli_real_escape_string($mysqli, $garage_id) . "'";
    querySQL($sql);
    if (mysqli_affected_rows($mysqli) > 0) {
        resetTuningParts($garage_id);

        addMoney($money); //Add money
        return "car_sold";
    } else
        return "car_not_found";
}

function resetTuningParts($garage_id) {
    $sql = "UPDATE storage SET garage_id = 0 WHERE garage_id = $garage_id";
    querySQL($sql);
}

//Upgrades
function getUserUpgrades() {
    $sql = "SELECT * FROM upgrades up 
            LEFT JOIN upgrades_user uu
            ON up.id = uu.up_id AND uu.user_id = " . $_SESSION["user_id"];
    $entry = querySQL($sql);
    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            if (is_null($row["ups"]))
                $data[$row["name"]] = false;
            else
                $data[$row["name"]] = array("ups" => $row["ups"], "effect" => $row["effect"]);
        }
        if (isset($data))
            return $data;
    } else {
        return false;
    }
}

function getAllUpgradePoints() {
    $sql = "SELECT SUM(ups) AS ups FROM upgrades_user WHERE user_id = '" . $_SESSION["user_id"] . "'";
    $entry = querySQL($sql);

    if ($entry) {
        $row = mysqli_fetch_assoc($entry);
        return $row["ups"];
    } else {
        return false;
    }
}

function buyUpgradePoint($cost) {
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    $upgrade = mysqli_query($mysqli, "UPDATE stats SET uppoints = uppoints + 1 WHERE id = '" . $_SESSION["user_id"] . "'"
    );
    $spend = mysqli_query($mysqli, "UPDATE stats
            SET money = money - $cost
            WHERE id = '" . $_SESSION["user_id"] . "'"
    );

    if ($upgrade && $spend) {
        mysqli_commit($mysqli);
        $out = "point_bought";
    } else {
        mysqli_rollback($mysqli);
        $out = "database_error";
    }
    mysqli_autocommit($mysqli, TRUE);
    return $out;
}

/*
 * this_id: ID dieses Upgrades
 * name: Name des Upgrades
 * chain: Kette des Upgrades
 * thisMax: Maximale Anzahl die gekauft werden kann
 * thisCost: Kosten für Upgrade
 * hasPre_id: Die ID des Upgrades (this_id), NOT NEEDED
 * pre_id: ID des benötigten Upgrades
 * needed: Anzahl benötigt vom vorherigen Upgrade
 */

function getUpgradeTree() { //gibt alle updates aus, zusammen mit den anforderungen und wie viele der user evtl schon hat
    $sql = "SELECT upu.ups as userUps,
        up.id AS this_id, 
        up.name,
        up.chain,
        uu.unit,
        up.effect,
        up.max AS thisMax, 
        up.points AS thisCost, 
        upt.up_id AS hasPre_id, 
        upt.pre_id AS pre_id, 
        upt.needed
        FROM upgrades up
                LEFT JOIN upgrades_units uu
                ON up.chain = uu.chain
                LEFT JOIN upgrades_tree upt
                ON up.id = upt.up_id
                LEFT JOIN upgrades_user upu
                ON up.id = upu.up_id AND upu.user_id = " . $_SESSION["user_id"];
    $entry = querySQL($sql);
    if ($entry) {
        while ($row = mysqli_fetch_assoc($entry)) {
            $data[$row["this_id"]] = $row;
        }
        if (isset($data))
            return $data;
    } else {
        return false;
    }
}

function upgradeById($up_id, $cost) { //set the upgrade to the db and remove free upgrade points from user
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    //Insert upgrade, if its there just upgrade it +1
    $upgrade = mysqli_query($mysqli, "INSERT INTO upgrades_user (user_id, up_id, ups) VALUES ('" . $_SESSION["user_id"] . "', '$up_id', '1') ON DUPLICATE KEY UPDATE ups = ups + 1"
    );

    $spend = mysqli_query($mysqli, "UPDATE stats
            SET uppoints = uppoints - $cost
            WHERE id = '" . $_SESSION["user_id"] . "'"
    );
    if ($upgrade && $spend) {
        mysqli_commit($mysqli);
        $out = "up_bought";
    } else {
        mysqli_rollback($mysqli);
        $out = "database_error";
    }
    mysqli_autocommit($mysqli, TRUE);
    return $out;
}

//Chat System

function saveToDB($user, $msg) {
    global $mysqli;
    mysqli_autocommit($mysqli, FALSE);

    $addMsg = mysqli_query($mysqli, "INSERT INTO chat_msg (user, msg, timestamp) 
            values ('" . $user . "', '" . mysqli_real_escape_string($mysqli, $msg) . "', '" . time() . "')"
    );
    $updateStat = mysqli_query($mysqli, "UPDATE stats
            SET chat_count = chat_count+1
            WHERE id = '" . $_SESSION["user_id"] . "'"
    );
    if ($addMsg && $updateStat) {
        mysqli_commit($mysqli);
        $out = "msg_saved";
    } else {
        mysqli_rollback($mysqli);
        $out = "database_error";
    }
    mysqli_autocommit($mysqli, TRUE);
    return $out;
}

function loadFromDB($limit) {
    $sql = "SELECT * FROM chat_msg LIMIT $limit";

    return getArray($sql);
}

//Items
function getUserItems() {
    $sql = "SELECT * FROM items WHERE user_id = '" . $_SESSION["user_id"] . "'";
    return getArray($sql);
}

function userItemCount($item_id) {
    global $mysqli;
    $sql = "SELECT count FROM items WHERE item_id = '" . mysqli_real_escape_string($mysqli, $item_id) . "' AND user_id = '" . $_SESSION["user_id"] . "'";
    $col = getColumn($sql);
    return $col;
}

function lowerItemCount($id) {
    $sql = "UPDATE items SET count = count-1 WHERE item_id = '$id' AND user_id = '" . $_SESSION["user_id"] . "'";
    querySQL($sql);
}

//Item Functions
function addCar($car_id) {
    $sql = "INSERT INTO garage (user_id, car_id) VALUES ('" . $_SESSION["user_id"] . "', '$car_id')";
    return querySQL($sql);
}
