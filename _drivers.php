<?php

class Driver {
    public $kosten;
    public $anteil;
    public $skill;
    public $name;
    public $maxLiga;
    public $nr;
    
    function __construct($i) {
        $liga = getPlayerLiga();
        if($liga < 3) $liga = 3;
        $id = $_SESSION["user_id"];
        $maxLiga = getExpRand(1, $liga, $i+$id);
        if($maxLiga <1) $maxLiga = 1;
        $this->maxLiga = $maxLiga;
        $this->kosten = getExpRand(3000, 10000, $i+$id)*pow($this->maxLiga, 2);
        $this->anteil = 20-getExpRand(5, 15, $i+$id);
        $this->skill = getExpRand(20, 1000, $i+$id);
        $this->id = date("dmY").$i;
        $this->nr = $i;
        $this->name = 'Driver-ID#'.rand(999, 99999);
    }
}

/*
class Legend extends Driver {
    
}*/

//Erstellt 5 neue System Fahrer
for($i = 1; $i <=5; $i++) {
    $drivers[$i] = new Driver($i);
}
