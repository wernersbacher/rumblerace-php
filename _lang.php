<?php

function getBrowserLang() {
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    if ($lang == "de")
        return "de";
    else
        return "en";
}

$l = getBrowserLang();
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
$lg["garage"] = ["HQ", "HQ"];
$lg["factory"] = ["Factory", "Fabrik"];
$lg["trader"] = ["Shop", "Händler"];
$lg["drivers"] = ["Drivers", "Fahrer"];
$lg["sprit"] = ["Fuel", "Sprit"];
$lg["upgrades"] = ["Upgrades", "Upgrades"];
$lg["stats"] = ["Stats", "Stats"];
$lg["market"] = ["Market", "Markt"];
$lg["special"] = ["Special", "Spezial"];
$lg["race"] = ["Race", "Rennen"];
//$lg["help"] = ["Help", "Hilfe"];
$lg["world"] = ["World", "Welt"];
$lg["logout"] = ["Logout", "Logout"];
$lg["options"] = ["Options", "Optionen"];

//Submenü Links
$lg["s_messages"] = ["SMS", "SMS"];
$lg["s_news"] = ["News", "News"];
$lg["s_bonus"] = ["Bonus", "Bonus"];
$lg["s_cars"] = ["Cars", "Autos"];
$lg["s_tuner"] = ["Parts Production", "Teileproduktion"];
$lg["s_items"] = ["Items", "Items"];
$lg["s_storage"] = ["Storage", "Lager"];
$lg["s_sell"] = ["Sell Fuel", "Sprit verkaufen"];
$lg["s_produce"] = ["Producing", "Produktion"];
$lg["s_cardealer"] = ["Cardealer", "Autohändler"];
$lg["s_paddock"] = ["Paddock", "Fahrerlager"];
$lg["s_newbie"] = ["Technical", "Technisches"];
$lg["s_faq"] = ["FAQs", "FAQs"];
$lg["s_carmarket"] = ["Car Market", "Automarkt"];
$lg["s_partmarket"] = ["Part Market", "Teilemarkt"];
$lg["s_spritmarket"] = ["Fuel Market", "Spritmarkt"];
$lg["s_endurance"] = ["Public Races", "Öffentliche Rennen"];
$lg["s_running"] = ["Running Races", "Laufende Rennen"];
$lg["s_racing"] = ["Races", "Rennen"];
$lg["s_mainstats"] = ["Your stats", "Deine Statistiken"];
$lg["s_globalstats"] = ["Global stats", "Globale Statistiken"];
$lg["s_chat"] = ["Chat", "Chat"];
$lg["s_quests"] = ["Quests", "Aufträge"];
$lg["s_settings"] = ["Settings", "Einstellungen"];
$lg["s_logout"] = ["Confirm", "Bestätigen"];
$lg["s_upgrades"] = ["Upgrades", "Upgrades"];
$lg["s_achievements"] = ["Achievements", "Erfolge"];
$lg["s_sysDrivers"] = ["Hire Drivers", "Fahrer einstellen"];
$lg["s_paddock"] = ["Paddock", "Fahrerlager"];
$lg["s_profiles"] = ["Profiles", "Profile"];
$lg["s_secretary"] = ["Secretary", "Sekräterin"];

//Tuning Kats
$lg["motor"] = ["Engine", "Motor"];
$lg["unit_motor"] = ["HP", "PS"];

$lg["auspuff"] = ["Exhaust", "Abgasanlage"];
$lg["unit_auspuff"] = ["HP", "PS"];

$lg["bremse"] = ["Chassis", "Fahrwerk"];
$lg["unit_bremse"] = ["Perf.", "Perf."];

$lg["turbo"] = ["Turbocharger", "Turbolader"];
$lg["unit_turbo"] = ["HP", "PS"];

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
    "Ein starker Motor braucht auch gute Bremsen. Wenn Du später bremst, bist Du länger schnell."];
$lg["desc_turbo"] = ["Supercharge your engine with a Turbo. Be prepared for the kick-in.", 
    "Lade Deinen Motor auf mit einem Turbo - aber bereite dich auf den Moment vor, in welchem er loslegt."];
$lg["desc_schaltung"] = ["Without a good transmission you can't bring the power to the street. Build it to enhance your perfomance.", 
    "Ohne ein gutes Getriebe kommt die Kraft nicht auf die Straße! Verbessere hiermit Deine Perfomance."];

$lg["desc_nockenwelle"] = ["A sharp camshaft, made four your cars.", 
    "Eine scharfe Rennnockenwelle, abgestimmt auf Deine Autos."];
$lg["desc_kolben"] = ["These pistons were made very well (made with lots of love).", 
    "Diese Kolben sind eine Sonderanfertigung und mit besonders viel Liebe gemacht."];

$lg["desc_sportesd"] = ["Rear SILENCER? Not anymore!", 
    "SchallDÄMPFER? Jetzt nicht mehr."];
$lg["desc_kruemmer"] = ["We are not really sure what it does, but power is power.", 
    "Wir sind nicht sicher, was es eigentlich genau macht. Aber Leistung ist Leistung, oder?"];
$lg["desc_katalysator"] = ["Less backpressure. That will sure help.", 
    "Weniger Gegendruck ist nie schlecht."];

$lg["desc_gelochte"] = ["Better heat dissipation, better brakepower when it's wet.", 
    "Bessere Hitzeableitung, bessere Bremsleistung bei Nässe!"];

$lg["desc_turbocharger"] = ["Getting pretty serious. You can't get more power anywhere else.", 
    "Jetzt wirds ernst. Extreme Leistungssteigerung in allen Klassen!"];

$lg["desc_getriebe"] = ["Lighter parts and perfect built transmission.", 
    "Leichtere Bauteile und perfekt gebaut."];
$lg["desc_doppelkupplung"] = ["YOu never shifted that quick before.", 
    "Du hast noch nie so schnell geschaltet wie jetzt."];

//Allgemeine Texte & Buttons
$lg["buy_now"] = ["Buy now", "Jetzt kaufen"];
$lg["tune_now"] = ["Tune now", "Jetzt tunen"];
$lg["race_now"] = ["Race now", "Jetzt fahren"];
$lg["hp"] = ["HP", "PS"];
$lg["perf"] = ["Perfomance", "Perfomance"];
$lg["liga"] = ["Level", "Level"];
$lg["build"] = ["Build", "Bauen"];
$lg["open_kat"] = ["See all parts", "Alle Teile ansehen"];
$lg["save_car"] = ["Save configuration", "Konfiguration speichern"];
$lg["back_overview"] = ["Back to overview", "Zurück zur Übersicht"];
$lg["time_left"] = ["left", "verbleibend"];
$lg["game_news"] = ["Get the latest game news!", "Alle neuen Informationen gibts hier!"];
$lg["coming_soon"] = ["This feature is not available yet. Please come back later!", "Dieses Feature wurde noch nicht eingebaut. Komm später wieder!"];
$lg["not_done"] = ["This feature is still in developing. Please come back later!", "Dieses Feature befindet sich noch in der Entwicklung. Komm später wieder!"];
$lg["erfahrung"] = ["Experience", "Erfahrung"];
$lg["real_logout"] = ["If you really want to logout, click the button below.", "Um dich auszuloggen, klicke auf diesen Button."];
$lg["online_user"] = ["users are online", "User sind online"];


//Register and Login Register for free!
$lg["register_free"] = ["Register for free!", "Registriere dich hier kostenlos!"];
$lg["please_login"] = ["Please login in order to play the game.", "Logge dich ein, um zu spielen."];
$lg["or_register"] = ["..or you register here, if you are a new player.", "..oder registriere dich hier."];
$lg["or_login"] = ["Already have an account? Login here.", "Bereits registriert? Logge dich hier ein."];
$lg["username_exists"] = ["The username is already taken.", "Der ausgewählte Name ist leider bereits vergeben!"];
$lg["wrong_input_reg"] = ["Please check your input data.", "Bitte überprüfe Deine eingebenen Daten!"];
$lg["no_user_found"] = ["User or/and password was wrong. Please try again", "Der Nutzername und/oder das Passwort stimmt nicht. Probiere es nocheinmal."];
$lg["password_not_correct"] = ["The passwords you typed in are noe equal.", "Die Passwörter stimmen nicht überein!"];
$lg["user_too_short_long"] = ["Your username needs at least 3 characters and max. 13.", "Der Username muss mindestens 3 und höchstens 13 Zeichen haben."];
$lg["bad_user_char"] = ["You may only use these character for your username: A-Z a-z 0-9 _ . * - ", "Der Username darf nur aus diesen Zeichen bestehen: A-Z a-z 0-9 _ . * - "];
$lg["ok_reg"] = ["Registration was successfull.", "Die Registrierung war erfolgreich."];
$lg["attention_reg"] = ["You are just playing as a guest! If your cookies gets deleted, your account will be too. Register your account for free:", 
    "Du spielst nur als Gast! Dein Account ist nicht dauerhaft gespeichert. Registriere jetzt deine Account. Kostenlos!"];


//Guest Beschreibungen
$lg["play_as_guest"] = ["Just want to play as guest?", "Nur als Gast spielen?"];

//Einstellungen, Mails vergessen etc
$lg["add_email"] = ["none yet", "noch keine"];
$lg["resetpwd"] = ["Forgot password?", "Passwort vergessen?"];
$lg["link_expired"] = ["Sorry, the link expired.", "Leider ist der Link abgelaufen."];
$lg["type_new_pwd"] = ["Type in your new password.", "Gib jetzt Dein neues Passwort ein."];
$lg["pass_saved"] = ["Your new password got saved.", "Dein neues Passwort wurde gespeichert."];
$lg["mail_send_if_ex"] = ["Check your mailbox. Email was sent if it's exists.", "Falls die Adresse existiert, wurde die Email nun verschickt."];
$lg["reset_info"] = ["If you forgot or just want to change your password, type in your email address.", 
    "Wenn Du Dein Passwort vergessen hast oder es einfach ändern willst, gib hier Deine Email Adresse ein."];
$lg["betreff_mail_pwd"] = ["New password for Racing Inc.", "Neues Passwort für Racing Inc."];
$lg["text_mail_pwd"] = ["You requested a new password. If you didn't, just ignore this mail!\nOpen the link in your browser to change the password. Only valid for 24 hours.", 
    "Du hast ein neues Passwort angefordert. Falls Du das nicht hast, ignoriere diese Mail einfach!\nRufe den Link im Browser auf, um das Passwort zu ändern. Der Link ist 24 Stunden gültig."];
$lg["closing"] = ["Kind regards,\n Markus Wernersbach\nCEO of Racing Inc. - facethepace.com", "Mit freundlichen Grüßen,\nMarkus Wernersbach\nCEO of Racing Inc. - facethepace.com"];
$lg["use_pwd_forget"] = ["Use the 'Forgot password' function to change your password.", "Nutze die 'Passwort vergessen' Funktion, um Dein Passwort zu ändern."];

//Garage/Cars/Cardealer Texte
$lg["car_bought"] = ["You bought the car.", "Du hast das Fahrzeug erfolgreich gekauft!"];
$lg["no_money"] = ["You have not enough money.", "Du hast nicht genug Geld."];
$lg["cd_shiny"] = ["Buy brand new cars. They are not tuned, but they have a shiny paint.", 
    "Hier kannst Du neue Fahrzeuge kaufen. Sie sind nicht getunt, aber haben einen schönen neuen Lack."];
$lg["cd_your_cars"] = ["These are your cars. Install tuning parts to make them faster!", 
    "Hier siehst Du Deine Fahrzeuge. Bringe neue Teile an, um sie schneller zu machen!"];
$lg["tn_info"] = ["Research new car parts here. Every part has acceleration, speed, handling and durability values. The given values are the max values it can reach when producing! Higher values are much more rare. You will need parts of a better level to mount them in a high level cars.",
        "Erforsche hier neue Fahrzeugteile! Jedes Teil Beschleunigung, Topspeed, Handling und Zuverlässigkeit. Die angegegeben Werte sind maximale Werte, welche beim Bauen erreicht werden können. Höhere Werte sind viel seltener. Außerdem benötigst Du für Fahrzeuge höherer Ligen auch mindestens die Teile aus dieser Level!"];
$lg["too_many_parts"] = ["You can't build that many parts at once.", "Du kannst nicht so viele Teile gleichzeitig bauen!"];
$lg["part_built"] = ["You part is getting build.", "Das Tuningteil wird nun gebaut!"];
$lg["st_info"] = ["All unmounted parts are in your storage.", "Alle nicht verwendeten Teile sind hier im Lager."];
$lg["no_parts_storage"] = ["No parts in storage yet.", "Keine Teile im Lager."];
$lg["car_tuning"] = ["Tune your car with parts you have already built. A part has to be in the same level as the car to build in.", 
    "Tune Dein Fahrzeug mit Teilen, die Du bereits gebaut hast. Das Teil muss mindestens in derselben Level wie das Fahrzeug sein, damit es angezeigt wird."];
$lg["car_updated"] = ["Car configuration saved.", "Fahrzeugkonfiguration wurde gespeichert!"];
$lg["market_with"] = ["Be aware that withdrawing the part from the market later on will cost you 10% of the offered price.", "Beachte, dass das spätere Zurücknehmen vom Markt 10% des Verkaufspreises kostet!"];
$lg["market_sell"] = ["Type in the market price for your selected part.", "Gib an, für wie viel Du das Teil verkaufen möchtest"];
$lg["sell_it"] = ["Sell", "Verkaufen"];
$lg["part_trashed"] = ["Part was trashed", "Teil wurde entfernt"];
$lg["car_not_found"] = ["This car doesn't exist anymore.", "Dieses Auto gibt es nicht mehr."];
$lg["car_sold"] = ["The car was sold to the System!", "Dieses Auto wurde erfolgreich verkauft!"];
$lg["car_is_racing"] = ["This car is currently not available!", "Dieses Auto ist gerade nicht verfügbar!"];

//Car Attribute /teile / Hinweise (tooltips?)
$lg["tip_acc"] = ["Acceleration is important to go fast.", "Beschleunigung ist wichtig, um schnell vom Fleck zu kommen."];
$lg["tip_speed"] = ["Speed is also important. Who wonders?", "Geschwindigkeit ist auch wichtig!"];
$lg["tip_hand"] = ["Handling is important for curvy tracks, and doesn't help you at all when dragracing.", "Handling ist wichtig für kurvige Strecken, hilft dir aber bei Drag renne gar nicht."];
$lg["tip_dura"] = ["The higher the durability factor, the more it will stress your car. It is also important to have a good durability value when driving long races, so you can keep the pace up longer.", 
    "Umso höher der Robustheitsfaktor, desto mehr verlangt das Rennen dem Auto ab. Ein hoher Fahrzeug- & Teilewert erhöht ist außerdem wichtig, um bei langen Rennen die Pace aufrechtzuerhalten."];


//Market
$lg["part_on_market"] = ["The part is now getting sold on the market.", "Das Teil wird nun auf dem Markt angeboten."];
$lg["part_not_found"] = ["You can't sell this part.", "Du kannst dieses Teil nicht verkaufen."];
$lg["sell_check_input"] = ["Check your input.", "Überprüfe Deine Angaben."];
$lg["part_sold"] = ["The part is already sold. Sorry!", "Das Teil wurde schon verkauft. Sorry!"];
$lg["part_bought"] = ["Part successfully bought.", "Du hast das Teil erfolgreich gekauft."];
$lg["partm_info"] = ["Buy rare items here. To sell your own, just go to your storage.", "Hier kannst Du Teile kaufen. Um selbst welche anzubieten, besuche Dein Lager."];
$lg["market_empty"] = ["There is nothing on this market.", "Auf dem Markt wird zur Zeit nichts angeboten."];
$lg["part_back"] = ["The part is now back in your storage.", "Das Teil befindet sich nun wieder in Deinem Lager.."];
$lg["part_back_ques"] = ["Do you want to remove your part from the market?", "Möchtest Du das Teil wieder ein Dein Lager verschieben?"];
$lg["part_back_cost"] = ["It will cost you 10% of the offered price", "Es kostet dich 10% des Angebotpreises"];
$lg["you_wish"] = ["Do you like to buy the part", "Möchtest Du das Teil"];
$lg["for_cost"] = ["for", "für einen Preis von"];
$lg["to_buy"] = ["?", "kaufen?"];
$lg["yes"] = ["Yes", "Ja"];
$lg["no"] = ["No", "Nein"];
$lg["seller"] = ["Seller", "Verkäufer"];
$lg["part"] = ["Part", "Teil"];
$lg["power"] = ["Power", "Anteil"];
$lg["price"] = ["Price", "Preis"];
$lg["count"] = ["Amount", "Menge"];

//Sprit
$lg["sprit_not_found"] = ["The offer was not found!", "Das Angebot wurde nicht gefunden!"];
$lg["buy"] = ["Buy", "Kaufen"];
$lg["sprit_sold"] = ["The offer doesn't exist anymore. You were too slow :(", "Das Angebot ist weg! Du warst zu langsam :("];
$lg["sprit_partly_sold"] = ["The amount you wish is not available anymore.", "Diese Menge ist nicht mehr verfügbar!"];
$lg["sprit_bought"] = ["You bought some fuel!", "Du hast erfolgreich Sprit eingekauft!"];
$lg["sprit_back"] = ["The sprit is now back in your tank.", "Der Sprit wurde wieder vom Markt genommen."];
$lg["sell"] = ["Sell", "Verkaufen"];
$lg["sell_check_input"] = ["You have to type in something!", "Du musst schon etwas eingeben!"];
$lg["sell_not_enough_sprit"] = ["You don't have enough fuel!", "So viel Sprit kannst du nicht verkaufen!"];
$lg["sprit_selling"] = ["The fuel was put on the market.", "Der Sprit wird nun am Markt angeboten."];
$lg["type_in_sprit"] = ["Type in the amount of fuel you want to sell and the price/ℓ.", "Gib die Menge des Sprits und die Kosten/ℓ an."];
$lg["sprit_back_ok"] = ["You got your fuel back.", "Der Sprit ist jetzt wieder in deinem Inventar."];
$lg["sprit_back"] = ["Do you want to get your sprit back from the market? If you don't have space for it, it will get trashed.",
    "Willst du deinen Sprit wiederhaben? Wenn du kein Platz dafür hast, wird er weggeworfen."];

$lg["sell_sprit"] = ["Sell your fuel to other players. To buy fuel from other players, <a href='?page=market&sub=spritmarket'>open the market place</a>.", 
    "Verkaufe hier deinen Sprit. Um den Sprit von anderen Spielern zu kaufen, <a href='?page=market&sub=spritmarket'>öffne den Marktplatz</a>."];

$lg["sprit_market"] = ["Buy fuel on the global market here. To sell your own fuel, <a href='?page=sprit&sub=sell'>go to 'Fuel'</a>.", 
    "Kaufe hier Sprit auf dem globalen Markt. Um dein eigenen Sprit zu verkaufen, <a href='?page=sprit&sub=sell'>geh zu 'Sprit'</a>."];

//Racing
$lg["race_info"] = ["Drive races for money and go up a level! The more horsepower, the higher the profit. The more performance, the more experience. You need a driver and car which has at least the same level as the race. The car is 3 times more important than the driver.", 
    "Fahre Rennen, um Geld zu bekommen und Ligen aufzusteigen! Je mehr PS, desto höher der Gewinn. Je mehr Performance, desto mehr EP. Du brauchst mindestens ein Auto und ein Fahrer aus der Level, in welchem auch das Rennen ist! Ein gutes Auto 3x so wichtig, wie ein guter Fahrer."];
$lg["pos_one"] = ["Pos 1", "Platz 1"];
$lg["reward"] = ["Reward", "Preisgeld"];
$lg["race_started"] = ["Race started!", "Rennen wurde gestartet. "];
$lg["desc_race"] = ["Drive some tracks to make money.", "Fahre Rennen, um etwas Geld zu verdienen!"];
$lg["cancel"] = ["Cancel", "Abbrechen"];
$lg["no_races_running"] = ["Currently no races running!", "Momentan sind keine Rennen am Laufen!"];
$lg["running_races"] = ["Check the races you are currently driving!", "Hier siehst Du alle Rennen, die gerade Laufen!"];
$lg["race_canc"] = ["Race canceled!", "Rennen abgebrochen!"];

//Fehlerhinweise
$lg["garage_full_1"] = ["You still got place for", "Du hast noch Platz für"];
$lg["garage_full_2"] = ["more car(s).", "Auto(s)."];
$lg["garage_more_space"] = ["Upgrade the garage or sell cars for more space.", "Upgrade die Garage oder verkaufe Autos für mehr Platz!"];
$lg["garage_empty"] = ["Your garage is empty!", "Die Garage is leer!"];
$lg["garage_full"] = ["Your garage is full!", "Deine Garage ist leider voll!"];
$lg["page_no_content"] = ["The page you are looking for is currently not available.", "Die gesuchte Seite ist zur Zeit leider nicht verfügbar."];
$lg["database_error"] = ["Database Error", "Datenbank Fehler"];
$lg["noscript"] = ["Please activate Javascript to enjoy this game 100%.", 
    "Aktiviere bitte Javascript, um den das Spiel 100% genießen zu können."];

//Messages
$lg["messages_info"] = ["See all your SMS.", "Hier siehst Du alle Deine SMS."];
$lg["message_empty"] = ["You have no SMS.", "Du hast keine SMS."];
$lg["messages_write_back"] = ["Answer the SMS for more fun.", "Antworte der SMS, um mehr Spaß zu haben."];
$lg["check_mes_input"] = ["Check your input. Make sure the username does exists!", "Überprüfe bitte Deine Angaben. Stell sicher, dass der Username existiert!"];
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
$lg["delOld"] = ["Delete 30 day old SMS", "Lösche 30 Tage alte SMS"];
$lg["readAll"] = ["Mark All As Read", "Alles Als Gelesen"];
$lg["sms_read"] = ["All SMS were marked as read.", "Alles Nachrichten wurden als gelesen markiert."];
$lg["sms_del"] = ["All system SMS were deleted.", "Alle System Nachrichten wurden gelöscht."];

//Sprit und so
$lg["arbeiter"] = ["Worker", "Arbeiter"]; 
$lg["machines"] = ["More Machines", "Mehr Maschinen"]; 
$lg["pur"] = ["Higher Purity Petrol", "Höhere Reinheit"]; 
$lg["chef"] = ["More Bosses", "Mehr Chefs!"]; 
$lg["marketing"] = ["Motivation Marketing", "Motivations Werbung"]; 
$lg["manager"] = ["Top Manager", "Top Manager"]; 
$lg["invest"] = ["Investors", "Investoren"]; 
$lg["place"] = ["New Building", "Neues Gebäude"]; 
$lg["sprit_prod_sum"] = ["Current production rate", "Aktuelle Produktionrate"]; 

$lg["produce_gas"] = ["Produce your own fuel here! Buy upgrade to produce faster.", 
    "Hier kannst Du Deinen eigenen Sprit produzieren! Kauf Upgrades, um die Produktion zu beschleunigen."];
$lg["teil_bought"] = ["You bought the upgrade.", 
    "Du hast das Upgrade erfolgreich gekauft!"];

//Einstellungen
$lg["opt_info"] = ["Change your profile settings here.", "Ändere hier Deine Profilinformationen."];
$lg["settings_saved"] = ["Changes saved.", "Änderungen gespeichert."];
$lg["bad_settings"] = ["Check your input. Something went wrong. ", "Irgendetwas ist schief gelaufen. Überprüfe Deine Eingaben."];

//Rennen Level
$lg["beginner"] =  ["Beginner", "Anfänger"];
$lg["amateur"] =  ["Amateur", "Amateur"];
$lg["pro"] =  ["Pro", "Pro"];
$lg["exp"] =  ["Advanced", "Fortgeschritten"];
$lg["med"] =  ["Medium", "Medium"];
$lg["int"] =  ["International", "Internationale"];
$lg["eli"] =  ["Elite", "Elite"];
$lg["black"] =  ["⚡ BLACKS", "⚡ BLACKS"];


$lg["race"] =  ["Race", "Rennen"];
$lg["cup"] =  ["Race Cup", "Meisterschaft"];
$lg["drag"] =  ["Drag", "Drag"];
$lg["end"] =  ["EnDurance", "Ausdauerrennen"];
$lg["master"] =  ["Masters", "Meister"];

//Fahrer Texte
$lg["driver_info"] = ["Get a few good drivers! The offer scrambles every day. They cost some money and have a racing share. You will also see the highest level they can race.", 
    "Hol dir ein paar gute Fahrer! Das Angebot wechselt jeden Tag. Sie kosten anfangs einen Batzen Geld, und danach möchten Sie immer ein Teil des Gewinnes haben! Außerdem siehst Du die Level, in welcher sie höchstens fahren können."];
$lg["get_driver"] = ["Hire driver", "Fahrer anheuern"];
$lg["driver_added"] = ["You hired the driver!", "Du hast den Fahrer erfolgreich angeheuert."];
$lg["driver_sum"] = ["Check all your drivers here.", "Verwalte hier Deine Fahrer."];
$lg["open_driver"] = ["Manage Driver", "Fahrer verwalten"];
$lg["fire_driver"] = ["Dismiss Driver", "Fahrer entlassen"];
$lg["driver_fired"] = ["The driver stopped his career and is now an 'expert' on TV.", "Der Fahrer wurde entlassen. Und es ward nie wieder etwas von ihm gehört"];
$lg["no_driver"] = ["You did not hire any driver yet. <a href='?page=drivers&sub=sysDrivers'>Do it now</a>", "Bisher hast du noch keinen Fahrer eingestellt. <a href='?page=drivers&sub=sysDrivers'>Tu es jetzt</a>"];
$lg["anteil"] = ["Stake", "Anteil"];
$lg["kostenpunkt"] = ["Upgrade cost", "Upgradekosten"];
$lg["upgrade_driver"] = ["Upgrade to next level", "Upgrade zur nächsten Level"];
$lg["driver_upgraded"] = ["The driver advanced to the next level!", "Der Fahrer wurde ein Level höher eingestuft!"];

//Bonus Seite
$lg["bonus_info"] = ["Get some extra money or gas.", "Hol dir immer etwas Geld oder Sprit zusätzlich."];
$lg["bonus_accepted"] = ["The bonus was credited to your account.", "Der Bonus wurde dir gutgeschrieben."];
$lg["can_take_bonus"] = ["Get your hourly bonus now. Decide which one you choose.", "Hol dir jetzt deinen stündlichen Bonus. Wähle einen davon aus."];
$lg["cant_take_bonus"] = ["Time to wait for next bonus:", "Zeit bis zum nächsten Bonus:"];

//Stats seite
$lg["global_stats"] = ["Check the best players", "Check die besten Spieler"];

//Itemsseite
$lg["items_info"] = ["Manage your items here. Activate or sell them!","Verwalte hier deine Items. Aktiviere oder verkaufe sie!"];
$lg["activate"] = ["Activate","Aktivieren"];
$lg["item_activated"] = ["Item got activated successful!","Das Item wurde erfolgreich aktiviert!"];
$lg["item_error"] = ["We can't activate your item. Do you have enough space for your rewards?","Das Item konnte nicht aktiviert werden. Hast du noch genug Platz übrig?"];
$lg["no_items"] = ["Looks like you don't have any items.","Leider hast du keine Items!"];

$lg["art_car"] = ["Car","Auto"];
$lg["rar_common"] = ["Common","Standard"];
$lg["rar_rare"] = ["Rare","Selten"];
$lg["rar_legend"] = ["Legendary","Legendär"];

$lg["car_items"] = ["Car Items","Auto Items"];

$lg["itSellable"] = ["Sellable","Handelbar"];
$lg["itArt"] = ["Typ","Typ"];
$lg["itRar"] = ["Rarity","Seltenheit"];
$lg["itLiga"] = ["level","Level"];
$lg["itCount"] = ["Quantity","Anzahl"];

//++++++Item Beschreibungen Texte

$lg["car_fig_title"] = ["Santini Figurati","Santini Figurati"];
$lg["car_fig_desc"] = ["Free Santini. Yoou need place in your garage to activate.","Kostenloser Santini! Du brauchst Platz in der Garage zum Aktivieren."];

//++++++ Items Ende

//Upgrades
$lg["point_bought"] = ["You bought an upgrade point.", "Du hast einen Upgradepunkt gekauft!"];
$lg["used_points"] = ["Used upgrade points:", "Eingesetzte Upgradepunkte:"];
$lg["unused_points"] = ["Not used upgrade points:", "Ungenutzte Upgradepunkte:"];
$lg["buy_another_point"] = ["Buy another point for", "Kaufe einen Punkt für"];
$lg["up_bought"] = ["Upgrade successfully bought.", "Du hast ein Upgrade gekauft!"];
$lg["upgrade_error"] = ["Please report this error. #uperr1", "Bitte melde diesen Fehler. #uperr1"];
$lg["it-costs"] = ["Costs", "Kosten"];
$lg["effect"] = ["Effect", "Effekt"];

//Upgrade Units

$lg["cars"] = [" car(s)/upgrade", " Auto(s)/Upgrade"];
$lg["per"] = ["%/upgrade", "%/Upgrade"];
$lg["lit"] = [" max sprit/upgrade", " maximaler Sprit/Upgrade"];

//+++++Upgrade Beschreibungen

//Garage
$lg["garage_space-title"] = ["Tidy up", "Aufräumen"];
$lg["garage_space"] = ["Tidy your grarage, there's some wasted room left!", "Räum' deine Garage auf, es ist noch verschwendeter Platz da!"];

$lg["garage_space_2-title"] = ["Expand", "Erweiterung"];
$lg["garage_space_2"] = ["Expand your garage.", "Erweitere deine Garage"];

//Mechaniker
$lg["mechanics-title"] = ["Mechanics", "Mechaniker"];
$lg["mechanics"] = ["Reduce the time you need for building tuning parts.", "Reduziere die Dauer, die Tuningteile zum Bauen benötigen."];

$lg["mechanics_2-title"] = ["Education", "Weiterbildung"];
$lg["mechanics_2"] = ["Educate your mechanics with a pro-traing!", "Bilde deine Mechaniker weiter mit einem Pro-Training!"];

$lg["mechanics_3-title"] = ["Education #2", "Weiterbildung #2"];
$lg["mechanics_3"] = ["It's not over yet!", "Es ist noch nicht vorbei!"];

$lg["mechanics_4-title"] = ["NAVY SEAL?", "NAVY SEAL?"];
$lg["mechanics_4"] = ["It's the NAVY SEAL training of mechanics, formula 1 experiences!", "Extrem hartes Training: Formel 1 Erfahrungen!"];

//max. Sprit
$lg["sprit_max-title"] = ["Fuel Canister", "Kanister"];
$lg["sprit_max"] = ["Some jerrycans. Simple.", "Ein paar Benzinkanister zum Befüllen."];

$lg["sprit_max_2-title"] = ["veerry big Fuel Canister", "Grooße Kanister"];
$lg["sprit_max_2"] = ["Some more. Nothing fancy.", "Ein paar mehr Kanister schaden nie, vor allem, wenn sie groß sind"];

$lg["sprit_max_3-title"] = ["Tank", "Tank"];
$lg["sprit_max_3"] = ["Has some more volume.", "Ein großer Tank. Achtung, ist groß und hässlich"];

$lg["sprit_max_4-title"] = ["Fuel Canister", "Tank (im Boden)"];
$lg["sprit_max_4"] = ["A really big tank this time (dont worry, it hasn't a gun)", "Noch ein Tank, diesmal im Boden. Da ist mehr Platz"];


//profile ++++++++++++++
$lg["profile_info"] = ["Find other players and get in touch with them.", "Finde andere User und schreibe Ihnen!"];
$lg["profile"] = ["Profile", "Profil"];
$lg["write_msg"] = ["Message", "Nachricht"];
$lg["no_profile_found"] = ["No user found :(", "Kein Nutzer gefunden :("];
$lg["search"] = ["Search", "Suchen"];

$lg["avg_pos"] = ["Average Race Position", "Durchschnittsplatzierung"];
$lg["races_run"] = ["Races Run", "Gefahrene Rennen"];
$lg["last_online"] = ["Last Online", "Zuletzt online"];
$lg["reg_date"] = ["Registration Date", "Registrationsdatum"];
$lg["profile_money"] = ["Bank cash", "Barvermögen"];
$lg["sum_price"] = ["Price Money Won", "Gesamtverdienst"];

// LOGS +++++++++++++
$lg["log_race_done"] = ["Race finished!", "Rennen beendet!"];
$lg["log_new_level"] = ["Level Up!", "Level Up!"];
$lg["log_part_sold"] = ["Part sold", "Teil verkauft"];
$lg["log_sprit_sold"] = ["Sprit sold", "Sprit verkauft"];

$lg["log_race_fin"] = ["Your race is finished. Your position:", "Das Rennen wurde beendet. Deine Position:"];
$lg["log_reward"] = ["Your reward", "Deine Belohnung"];

$lg["log_advanced"] = ["You advanced to level", "Du bist jetzt Level"];
$lg["log_sold_for"] = ["sold for", "verkauft für"];
$lg["log_sprit_sold_sprintf"] = ['You sold %1$s for %2$s (= %3$s).', 'Du hast %1$s für %2$s verkauft (= %3$s).'];

$lg["log_welcome"] = ['WELCOME!', 'WILLKOMMEN!'];
$lg["log_welcome_msg"] = ['Welcome to Racing Inc! Have fun playing!', 'Willkommen bei Racing Inc! Viel Spaß beim Spielen!'];