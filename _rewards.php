<?php

class Rewards {
    
    function __construct($id, $sellable, $art, $rar, $liga, $activate) {
        $this->id = $id; //Name als eindeutiger String (Name und Beschreibung in _lang.php)
        $this->sellable = $sellable; //Item verkäuflich?
        $this->art = $art; //Art des Gewinnes (Auto, Geld, Teil, Bonus)
        $this->rar = $rar; //Rarität (common, rare, legend)
        $this->liga = $liga; //Liga (für Autos, Teile, bzw um das Level einzuschätzen)
        
        
        $this->activate = $activate; //Aktivierungsfunktion
    }
    
    //Standardwerte ALLER Gewinne
    public $id;
    public $sellable;
    public $art;
    public $rar;
    public $liga;
    
    //Standardfunktionen ALLER Gewinne
    public $activate;
    
}

//Globale Variablen
$_rewards;

//Packt alle Quests in ein Array
function ladeGewinn($id, $sellable, $art, $rar, $liga, $activate) {

    global $_rewards;
    $_rewards[$id] = new Rewards($id, $sellable, $art, $rar, $liga, $activate);
    
}

/*
 * Alle Gewinne im Spiel
 */

ladeGewinn("car_fig", true, "car", "common", 1, function() {
    //Wenn das Item aktiviert wird, passiert das:
    return rewardItem("car","santini_figurati"); //gibt true zurück wenn die Aktivierung erfolgreich war
});

/*
 * Gewinne Ende
 */
