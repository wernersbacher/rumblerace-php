<?php

class Tutorial {

    private $user_state;
    private $user_id;
    
    private $tut_states = [
        "TUT_STATE_BUYCAR",
        "TUT_STATE_BUYDRIVER",
        "TUT_STATE_DRIVE",
        "TUT_STATE_SPRIT",
        "TUT_STATE_PARTS",
        "TUT_STATE_STORAGE",
        "TUT_STATE_EQUIP",
        "TUT_STATE_END"
    ];
    private $mysql;
     
    public function __construct($user, $mysqle) {
        echo $user;
        $this->$user_id = $user;
        $this->$mysql = $mysqle;
        
       // $this->$user_state = $this->getState();
    }
/*
    public function getState() {
        
        $sql = "SELECT tut_state WHERE user_id = '" . mysqli_real_escape_string($this->$mysql, $this->$user_id)."'";
       
        $entry = querySQL($sql);

        $row = mysqli_fetch_array($entry, MYSQLI_ASSOC);

        if (__count($row) >= 1) {
            //Falls User gefunden wurde

            return $row["tut_state"];
        } else {
            return $this->$tut_states[0];
        }
        
        return $this->$state;
    }*/
    
    public function setState($state) {
        $this->$user_state = $state;
    }

    public function saveStateDB() {
        $save = $this->$user_state;

        $sql = "INSERT INTO user_tut (user_id, tut_state) VALUES ('$this->$user_id','$save')
        ON DUPLICATE KEY UPDATE tut_state = '$save'";
        querySQL($sql);
    }

}


$tutorial = new Tutorial($_SESSION["user_id"], $mysqli);