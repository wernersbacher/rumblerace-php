<?php
$l = "en";
$langArr = ["en" => 0, "de" => 1];

function put($key, $lang) {
    
    global $lg;
    global $langArr;
    
    if(array_key_exists($key, $lg)) { //Check ob ein Eintrag vorhanden ist
        if(array_key_exists($lang,$langArr)) { //Check ob übergebene SPrache existiert
            $val = $lg[$key][$langArr[$lang]];
        } else {
        $val = $key;
    }
    } else {
        $val = $key;
    }
    return $val;
}

$lg = array();

//Menü Links
$lg["office"] = ["Office", "Büro"];
$lg["garage"] = ["Garage", "Garage"];
$lg["upgrades"] = ["Upgrades", "Upgrades"];
$lg["stats"] = ["Stats", "Stats"];
$lg["market"] = ["Market", "Markt"];
$lg["special"] = ["Special", "Spezial"];
$lg["race"] = ["Race", "Rennen"];
$lg["help"] = ["Help", "Hilfe"];
$lg["logout"] = ["Logout", "Logout"];
$lg["options"] = ["Options", "Optionen"];

//Submenü Links
$lg["s_messages"] = ["SMS", "SMS"];
$lg["s_news"] = ["News", "News"];
$lg["s_cars"] = ["Cars", "Autos"];
$lg["s_tuner"] = ["Tuner", "Tuner"];
$lg["s_storage"] = ["Storage", "Lager"];
$lg["s_cardealer"] = ["Cardealer", "Autohändler"];
$lg["s_paddock"] = ["Paddock", "Fahrerlager"];
$lg["s_newbie"] = ["Newbie", "Newbie"];
$lg["s_faq"] = ["FAQs", "FAQs"];
$lg["s_carmarket"] = ["Car Market", "Automarkt"];
$lg["s_partmarket"] = ["Part Market", "Teilemarkt"];
$lg["s_endurance"] = ["Public Races", "Öffentliche Rennen"];
$lg["s_running"] = ["Running Races", "Laufende Rennen"];
$lg["s_racing"] = ["Races", "Rennen"];
$lg["s_mainstats"] = ["Main Statistics", "Hauptstatistiken"];
$lg["s_settings"] = ["Settings", "Einstellungen"];
$lg["s_logout"] = ["Confirm", "Bestätigen"];
$lg["s_upgrades"] = ["Upgrades", "Upgrades"];
$lg["s_achievements"] = ["Achievements", "Erfolge"];

//Tuning Kats
$lg["motor"] = ["Engine", "Motor"];
$lg["unit_motor"] = ["HP", "PS"];

$lg["auspuff"] = ["Exhaust", "Abgasanlage"];
$lg["unit_auspuff"] = ["HP", "PS"];

$lg["bremse"] = ["Chassis", "Fahrwerk"];
$lg["unit_bremse"] = ["Perf.", "Perf."];

$lg["turbo"] = ["Turbocharger", "Turbolader"];
$lg["unit_turbo"] = ["PS", "PS"];

$lg["schaltung"] = ["Gearshift", "Schaltung"];
$lg["unit_schaltung"] = ["Perf.", "Perf."];

//Tuning Teile
$lg["nockenwelle"] = ["Camshaft","Nockenwelle"];
$lg["schwungrad"] = ["Flywheel","Schwungrad"];
$lg["kruemmer"] = ["Manifold","Krümmer"];

$lg["sportesd"] = ["Rear Silencer","Endschalldämpfer"];
$lg["msd"] = ["Middle Silencer","Mittelschalldämpfer"];
$lg["katalysator"] = ["Catalyst","Katalysator"];

$lg["kolben"] = ["Forged Pistons","Schmiedekolben"];

$lg["ladeluftkuehler"] = ["Charge Air Cooler","Ladeluftkühler"];
$lg["turbocharger"] = ["Turbocharger","Turbolader Modul"];

$lg["gelochte"] = ["Perforated Brakes","Gelochte Bremsscheiben"];

$lg["getriebe"] = ["Transmission","Getriebe"];
$lg["rennkupplung"] = ["Racing Clutch","Rennkupplung"];
$lg["doppelkupplung"] = ["Dual Clutch","Doppelkupplung"];

//Tuning Beschreibungen
$lg["desc_motor"] = ["An enhanced engine is the key to speed.", "Der Motor ist der Schlüssel zur Geschwindigkeit."];
$lg["desc_auspuff"] = ["Not only the sound is better, also the engine won't waste much energy anymore.", 
    "Abgesehen von dem Sound, verschwendet der Motor nicht mehr so viel Kraft."];
$lg["desc_bremse"] = ["A strong engine needs strong brakes. Brake later and stay fast longer.", 
    "Ein starker Motor braucht auch gute Bremsen. Wenn du später bremst, bist du länger schnell."];
$lg["desc_turbo"] = ["Supercharge your engine with a Turbo. Be prepared for the kick-in.", 
    "Lade deinen Motor auf mit einem Turbo - aber bereite dich auf den Moment vor, in welchem er loslegt."];
$lg["desc_schaltung"] = ["Without a good transmission you can't bring the power to the street. Build it to enhance your perfomance.", 
    "Ohne ein gutes Getriebe kommt die Kraft nicht auf die Straße! Verbessere hiermit deine Perfomance."];

//Allgemeine Texte & Buttons
$lg["buy_now"] = ["Buy now", "Jetzt kaufen"];
$lg["tune_now"] = ["Tune now", "Jetzt tunen"];
$lg["race_now"] = ["Race now", "Jetzt fahren"];
$lg["hp"] = ["HP", "PS"];
$lg["perf"] = ["Perfomance", "Perfomance"];
$lg["liga"] = ["League", "Liga"];
$lg["build"] = ["Build", "Bauen"];
$lg["open_kat"] = ["See all parts", "Alle Teile ansehen"];
$lg["save_car"] = ["Save configuration", "Konfiguration speichern"];
$lg["back_overview"] = ["Back to overview", "Zurück zur Übersicht"];
$lg["time_left"] = ["left", "verbleibend"];
$lg["game_news"] = ["Get the latest game news!", "Alle neuen Informationen gibts hier!"];
$lg["coming_soon"] = ["This feature is not available yet. Please come back later!", "Dieses Feature wurde noch nicht eingebaut. Komm später wieder!"];
$lg["not_done"] = ["This feature is still in developing. Please come back later!", "Dieses Feature befindet sich noch in der Entwicklung. Komm später wieder!"];

//Register and Login Register for free!
$lg["register_free"] = ["Register for free!", "Registriere dich hier kostenlos!"];
$lg["please_login"] = ["Please login in order to play the game.", "Logge dich ein, um zu spielen."];
$lg["or_register"] = ["..or you register here, if you are new here.", "..oder registriere dich hier."];
$lg["username_exists"] = ["The username is already taken.", "Der ausgewählte Name ist leider bereits vergeben!"];
$lg["wrong_input_reg"] = ["Please check your input data.", "Bitte überprüfe deine eingebenen Daten!"];

//Garage/Cars/Cardealer Texte
$lg["car_bought"] = ["You bought the car.", "Du hast das Fahrzeug erfolgreich gekauft!"];
$lg["no_money"] = ["You have not enough money.", "Du hast nicht genug Geld."];
$lg["cd_shiny"] = ["Buy brand new cars. They are not tuned, but they have a shiny paint.", 
    "Hier kannst du neue Fahrzeuge kaufen. Sie sind nicht getunt, aber haben einen schönen neuen Lack."];
$lg["cd_your_cars"] = ["These are your cars. Install tuning parts to make them faster!", 
    "Hier siehst du deine Fahrzeuge. Bringe neue Teile an, um sie schneller zu machen!"];
$lg["tn_info"] = ["Research new car parts here. Every part has a minimum and a maximum possible value to reach, while better parts are rare. You will need parts of a better league to mount them in a high league car.",
        "Erforsche hier neue Fahrzeugteile! Jedes Teil hat einen Minimum- und ein Maximalwert, welches es erreichen kann. Besser Teile sind seltener. Außerdem benötigst du für Fahrzeuge höherer Ligen auch mindestens die Teile aus dieser Liga!"];
$lg["too_many_parts"] = ["You can't build that many parts at once.", "Du kannst nicht so viele Teile gleichzeitig bauen!"];
$lg["part_built"] = ["You part is getting build.", "Das Tuningteil wird nun gebaut!"];
$lg["st_info"] = ["All unmounted parts are in your storage.", "Alle nicht verwendeten Teile sind hier im Lager."];
$lg["no_parts_storage"] = ["No parts in storage yet.", "Keine Teile im Lager."];
$lg["car_tuning"] = ["Tune your car with parts you have already built. A part has to be in the same league as the car to build in.", 
    "Tune dein Fahrzeug mit Teilen, die du bereits gebaut hast. Das Teil muss mindestens in derselben Liga wie das Fahrzeug sein, damit es angezeigt wird."];
$lg["car_updated"] = ["Car configuration saved.", "Fahrzeugkonfiguration wurde gespeichert!"];
$lg["market_with"] = ["Be aware that withdrawing the part from the market later on will cost you 10% of the offered price.", "Beachte, dass das spätere Zurücknehmen vom Markt 10% des Verkaufspreises kostet!"];
$lg["market_sell"] = ["Type in the market price for your selected part.", "Gib an, für wie viel du das Teil verkaufen möchtest"];

//Market
$lg["part_on_market"] = ["The part is now getting sold on the market. You will get a SMS, if someone bought it!", "Das Teil wird nun auf dem Markt angeboten. Sobald es gekauft wird, erhälst du eine SMS."];
$lg["part_not_found"] = ["You can't sell this part.", "Du kannst dieses Teil nicht verkaufen."];
$lg["sell_check_input"] = ["Check your input.", "Überprüfe deine Angaben."];
$lg["part_sold"] = ["The part is already sold. Sorry!", "Das Teil wurde schon verkauft. Sorry!"];
$lg["part_bought"] = ["Part successfully bought.", "Du hast das Teil erfolgreich gekauft."];
$lg["partm_info"] = ["Buy rare items here. To sell your own, just go to your storage.", "Hier kannst du Teile kaufen. Um selbst welche anzubieten, besuche dein Lager."];
$lg["market_empty"] = ["There are no parts on the market.", "Auf dem Markt werden keine Teile angeboten."];
$lg["part_back"] = ["The part is now back in your storage.", "Das Teil befindet sich nun wieder in deinem Lager.."];
$lg["part_back_ques"] = ["Do you want to remove your part from the market?", "Möchtest du das Teil wieder ein dein Lager verschieben?"];
$lg["part_back_cost"] = ["It will cost you 10% of the offered price", "Es kostet dich 10% des Angebotpreises"];
$lg["you_wish"] = ["Do you like to buy the part", "Möchtest du das Teil"];
$lg["for_cost"] = ["for", "für einen Preis von"];
$lg["to_buy"] = ["?", "kaufen?"];
$lg["yes"] = ["Yes", "Ja"];
$lg["no"] = ["No", "Nein"];

//Racing
$lg["race_info"] = ["Drive races for money and go up a league!", "Fahre Rennen, um Geld zu bekommen und Ligen aufzusteigen!"];
$lg["pos_one"] = ["Pos 1", "Platz 1"];
$lg["reward"] = ["Reward", "Preisgeld"];
$lg["race_started"] = ["Race started. You will get a SMS when it's done!", "Rennen wurde gestartet. Du bekommst eine SMS, wenn es zuende ist!"];
$lg["desc_race"] = ["Drive some tracks to make money.", "Fahre Rennen, um etwas Geld zu verdienen!"];
$lg["cancel"] = ["Cancel", "Abbrechen"];
$lg["no_races_running"] = ["Currently no races running!", "Momentan sind keine Rennen am Laufen!"];
$lg["running_races"] = ["Check the races you are currently driving!", "Hier siehst du alle Rennen, die gerade Laufen!"];
$lg["race_canc"] = ["Race canceled!", "Rennen abgebrochen!"];

//Fehlerhinweise
$lg["garage_empty"] = ["Your garage is empty!", "Die Garage is leer!"];
$lg["page_no_content"] = ["The page you are looking for is currently not available.", "Die gesuchte Seite ist zur Zeit leider nicht verfügbar."];
$lg["database_error"] = ["Database Error", "Datenbank Fehler"];
$lg["noscript"] = ["Please activate Javascript to enjoy this game 100%.", 
    "Aktiviere bitte Javascript, um den das Spiel 100% genießen zu können."];

//Messages
$lg["messages_info"] = ["See all your SMS.", "Hier siehst du alle deine SMS."];
$lg["message_empty"] = ["You have no SMS.", "Du hast keine SMS."];
$lg["messages_write_back"] = ["Answer the SMS for more fun.", "Antworte der SMS, um mehr Spaß zu haben."];
$lg["check_mes_input"] = ["Check your input. Make sure the username does exists!", "Überprüfe bitte deine Angaben. Stell sicher, dass der Username existiert!"];
$lg["message_sent"] = ["The SMS was sent succesfully!", "Die SMS wurde erfolgreich verschickt!"];
$lg["absender"] = ["Sender", "Absender"];
$lg["betreff"] = ["Subject", "Betreff"];
$lg["message"] = ["SMS", "SMS"];
$lg["read"] = ["Read", "Lesen"];
$lg["readit"] = ["Read", "Gelesen"];
$lg["unread"] = ["Unread", "Ungelesen"];
$lg["new_mes"] = ["New SMS", "Neue SMS"];
$lg["send_mes"] = ["Send SMS", "SMS verschicken"];
$lg["from"] = ["from", "von"];
$lg["no_title"] = ["No Subject", "Kein Betreff"];
$lg["toggle_sys"] = ["Toggle System", "System an/aus"];
$lg["answer"] = ["Answer", "Antworten"];
$lg["delSys"] = ["Delete System SMS", "Lösche System SMS"];
$lg["sms_del"] = ["All system SMS were deleted.", "Alle System Nachrichten wurden gelöscht."];

//Einstellungen
$lg["opt_info"] = ["Change your profile settings here.", "Ändere hier deine Profilinformationen."];
$lg["settings_saved"] = ["Changes saved.", "Änderungen gespeichert."];
$lg["bad_settings"] = ["Check your input. Something went wrong. ", "Irgendetwas ist schief gelaufen. Überprüfe deine Eingaben."];

//Rennen Liga
$lg["beginner_race"] =  ["Beginner Race", "Anfänger Rennen"];
$lg["beginner_coupe"] =  ["Beginners Race Cup", "Anfänger Meisterschaft"];
$lg["beginner_endurance"] =  ["Beginner Endurance", "Anfänger Ausdauerrennen"];
$lg["beginner_endurance_cup"] =  ["Beginners Endurance Cup", "Anfänger Ausdauermeisterschaft"];

$lg["amateur_race"] =  ["Amateur Race", "Amateur Rennen"];
$lg["amateur_cup"] =  ["Amateur Cup", "Amateur Meisterschaft"];
$lg["amateur_end"] =  ["Amateur Endurance", "Amateur Ausdauerrennen"];
$lg["amateur_master"] =  ["Amateur Masters", "Amateur Meister"];