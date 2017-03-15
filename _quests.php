<?php

class quests {
    
    function __construct($id, $title, $desc, $reward, $prereq, $progress) {
        $this->id = $id;
        $this->title = $title;
        $this->desc = $desc;
        $this->reward = $reward;
        $this->prereq = $prereq;
        $this->progress = $progress;
    }
    
    //Standardwerte ALLER Quests
    public $id;
    public $title;
    public $desc;
    
    //Standardfunktionen ALLER Quests
    public $reward;
    public $progress;
    
}

class scriptedquest extends quests {
    //noch unbenutzt
}

//Globale Variablen
$quest_arr;

//Packt alle Quests in ein Array
function createQuest($id, $title, $desc, $reward, $prereq, $progress) {

    global $quest_arr, $quest_tmp;
    if(!in_array($id, $quest_tmp["completed"])) //Nur nicht-abgeschlossene Quests laden
        $quest_arr[$id] = new quests($id, $title, $desc, $reward, $prereq, $progress);
    
}

/*
 * Alle Quests im Spiel
 * Es gibt folgende Typen
 * - hardcoded, sind fest vorangelegt
 * - random, können immer mal kommen
 */

createQuest("testq", "Testquest", "dat rhyme", "keiner", function() {
    global $quest_tmp;
    if(in_array("testp", $quest_tmp["completed"]))
        return true;
     else return false;
}, function() {
    if(true) {
        echo "Erfolg";
    } else {
        echo "nicht fertig";
    }
});

/*
 * Quest Definition ende
 */


// Only for Testing
$quest_tmp = ["completed" => ["testp"], "running" => ["testx"]];

//Überprüft, ob Quests freigegeben sind
foreach($quest_arr as $id => $quest) {
    if(($quest->prereq)())
        echo "User kann freischalten!";
    else echo "Nicht möglich";
}