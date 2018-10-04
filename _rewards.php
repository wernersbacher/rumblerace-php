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
    return rewardCar("santini_figurati"); //gibt true zurück wenn die Aktivierung erfolgreich war
});

/*
 * Gewinne Ende
 */

//Rewardfunktionen
function rewardCar($car_id) {
    if (getFreeGarageSlots() > 0 && addCar($car_id))
        return true;
    else
        return false;
}

//Hilfsfunktionen
function activateItem($id) {
    global $_rewards;
    $result = ($_rewards[$id]->activate)();
    if($result) {
        lowerItemCount($id);
        return "item_activated";
    } else return "item_error";
    
}
