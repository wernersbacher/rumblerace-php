<?php

function getTutorialBox() {
    global $l,$tutorial;
    
    $output = "<div id='tutorialInfoBox'>
                        <h2>
                          <img src='img/tut.png' class='tutImg' />  ".put("TUT_HEADER",$l)." 
                        </h2>
                        <div class='tutorialTextBlock'>
                        ".put($tutorial->getState(),$l)."
                        </div>



                    </div>";
    return $output;
}

class Tutorial {

    private $user_state;
    private $user_id;
    
    private $tut_states = [
        0 => "TUT_STATE_BUYCAR",
        10 => "TUT_STATE_BUYDRIVER",
        20 => "TUT_STATE_DRIVE",
        30 => "TUT_STATE_SPRIT",
        40 => "TUT_STATE_PARTS",
        50 => "TUT_STATE_STORAGE",
        60 => "TUT_STATE_EQUIP",
        80 => "TUT_STATE_END"
    ];
    
    private $state_keys;
    private $mysql;
     
    public function __construct($user, $mysqle) {
        
        $this->user_id = $user;
        $this->mysql = $mysqle;
        
        $this->user_state = $this->loadState();
        
        $this->state_keys = array_keys($this->tut_states);
        $this->steps = count($this->tut_states)-1;
        
    }

    public function getSteps() {
        return $this->steps;
    }
    
    public function getState() {
        return $this->user_state;
    }
    
    public function loadState() {
        
        $sql = "SELECT tut_state FROM user_tut WHERE user_id = " . mysqli_real_escape_string($this->mysql, $this->user_id)."";
       
        $entry = querySQL($sql);

        $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

        if (__count($row) >= 1 && $this->isState($row["tut_state"])) {
            //Falls User gefunden wurde

            $this->user_state = $row["tut_state"];
        } else {
            $this->user_state = $this->tut_states[0];
            $this->saveStateDB();
        }
        
        return $this->user_state;
    }
    
    public function tickOff($st) {
        //Falls ungleich, kein abhaken möglich
        if($st !== $this->user_state)
            return false;
        
        $currPrio = array_search($this->user_state, $this->tut_states);
        $currKey = array_search($currPrio, $this->state_keys);
        $nextKey = $currKey+1;
        if(!array_key_exists($nextKey, $this->state_keys))
                return false;
        
        $nextPrio = $this->state_keys[$nextKey];
        
        $newState = $this->tut_states[$nextPrio];
        
        $this->setState($newState);
        $this->saveStateDB();
        
        //Tutorial zuende
        if(end($this->tut_states) == $newState) {
            logGeneric("tut_done");
        }
        
        return true;
        
    }
    
    public function setState($state) {
        $this->user_state = $state;
    }

    public function saveStateDB() {
        $save = $this->user_state;
        
        $sql = "INSERT INTO user_tut (user_id, tut_state) VALUES ('$this->user_id','$save')
        ON DUPLICATE KEY UPDATE tut_state = '$save'";
        querySQL($sql);
    }

    /*
     * Checks if user is at least at $st tutorial state
     */
    public function isAtState($st) {
        return (array_search($st, $this->tut_states) <= array_search($this->user_state, $this->tut_states));
    }
    
    public function isDone() {
        return ($this->user_state == "TUT_STATE_END") ? true : false;
    }
    
    private function isState($st)  {
        return in_array($st, $this->tut_states);
    }
}


$tutorial = new Tutorial($_SESSION["user_id"], $mysqli);