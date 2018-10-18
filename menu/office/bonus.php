<?php

$boni = array(
    "1" => "500", //money
    "2" => "20", //sprit
    "3" => "15" //exp
);

function calcBonus($val) {
    $liga = getPlayerLiga();
    return $val * $liga;
}

function obtainReward($num) {
    global $boni;
    $val = calcBonus($boni[$num]);
    switch ($num) {
        case 1:
            addMoney($val);
            break;
        case 2:
            addSprit($val);
            break;
        case 3:
            addExp($val);
            break;
    }
    updateLastBonus();
}

$output = outputTut("bonus_info", $l);

$bonus = getBonusData();

if (isset($post["reward"]) && $post["reward"] > 0 && $post["reward"] < 4 && $bonus["last"] + 60 * 60 < time()) {
    $rew = $post["reward"];
    obtainReward($rew);

    $output .= "<span class='dealInfoText green'>";
    $output .= put("bonus_accepted", $l);
    $output .= "</span>";
}

$bonus = getBonusData(); //get again after obtaining bonus
if (!$bonus OR $bonus["last"] + 60 * 60 < time()) { //Kein Eintrag angelegt oder länger her
    $status = "can_take_bonus";
    $disabled = "";
    $sec = 0;
    $time = "";
} else {
    $status = "cant_take_bonus";
    $disabled = "disabled";
    $sec = $_config["bonus"]["wait"] - time() + $bonus["last"];
    $time = formatSeconds($sec);
}


$output .= "
        
        <div class='settings center'>
        <div>" . put($status, $l) . " <span id='bonus_timer' data-left='$sec'>" . $time . "</span></div>
        
        <div class='flexBonus' id='bonus_btn_container'>
            <div>
                <form action='?page=office&sub=bonus&action=get' method='post'>
                <input type='hidden' name='reward' value='1' />
                <input class='bonus_button' $disabled type='submit' name='send' value='" . dollar(calcBonus($boni[1])) . "' />
                </form>
            </div>
            <div>
                <form action='?page=office&sub=bonus&action=get' method='post'>
                <input type='hidden' name='reward' value='2' />
                <input class='bonus_button' $disabled type='submit' name='send' value='" . gas(calcBonus($boni[2])) . "' />
                </form>
            </div>
            <div>
                <form action='?page=office&sub=bonus&action=get' method='post'>
                <input type='hidden' name='reward' value='3' />
                <input class='bonus_button' $disabled type='submit' name='send' value='" . ep(calcBonus($boni[3])) . "' />
                </form>
            </div>
        </div>

        </div>

        ";
