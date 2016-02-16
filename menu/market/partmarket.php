<?php

//Checken ob user genug geld hat!

$output = outputTut("partm_info", $l);

$output .= "<div id='market'>";

if ($mode == "buy" && isset($get["id"])) {
    $str_id = $get["id"];
    $str_data = queryMarketPartData($str_id);

    $output .= "<div class='textCenter'>";
    if ($str_data) {
        $part = $str_data["part"];
        $value = $str_data["value"];
        $liga = $str_data["liga"];
        $price = $str_data["sell"];
        $kat = $str_data["kat"];
        $user_id = $str_data["user_id"];
        if ($price > 0) {

            if ($user_id == $_SESSION["user_id"]) {
                //Löschen des eigenen Elements

                $output .= "<br/><b>" . put($part, $l) . " ($value " . put("unit_" . $kat, $l) . ") (" . put("liga", $l) . " $liga)</b><br/>";
                $output .= put("part_back_ques", $l)."<br/>";
                $output .= put("part_back_cost", $l).": ";
                $output .= "<b>(" . dollar($price / 10) . ")</b><br/>";
            } else {
                //Kaufen des Elements

                $output .= put("you_wish", $l);
                $output .= "<br/><b>" . put($part, $l) . " ($value " . put("unit_" . $kat, $l) . ") (" . put("liga", $l) . " $liga)</b><br/>";
                $output .= put("for_cost", $l);
                $output .= "<br/><b>" . dollar($price) . "</b><br/>";
                $output .= put("to_buy", $l);
            }

            $output .= "<form method='post' action='?page=market&sub=partmarket'>";
            $output .= "<input type='hidden' name='id' value='$str_id'> ";
            $output .= "<input type='submit' name='canceled' value='" . put("no", $l) . "'> ";
            $output .= "<input type='submit' name='confirmed' value='" . put("yes", $l) . "'>";
            $output .= "</form>";
        } else
            $output .= put("part_sold", $l);
    } else
        $output .= put("part_sold", $l);


    $output .= "</div>";
} else {

    function marketPartBuy($str_id) {

        $money = getPlayerMoney();
        $price = queryMarketPartCost($str_id);
        if ($money >= $price) {
            return queryMarketPartBuy($str_id);
        } else {
            return "no_money";
        }
    }

    if (isset($post['confirmed'])) {
        $str_id = $post["id"];

        $buy = marketPartBuy($str_id);

        $output .= "<span class='dealInfoText $buy'>";
        $output .= put($buy, $l);
        $output .= "</span>";
    }


    $output .= "Filter: bla bla";
    $output .= "<table style='font-size:13px;' class='tableRed selling noclick'>
                <tr>
                  <th>Verkäufer</th>
                  <th>Teil</th>
                  <th>Leistung</th>
                  <th>Preis</th>
                </tr>";

    //Blätterseite abfragen
    $menge = ceil(queryMarketParts(0, true));
    if (isset($get["s"]) AND $get["s"] <= $menge)
        $s = $get["s"];
    else
        $s = 1;
    //Aktuelle Seite auslesen
    $partMarket = queryMarketParts($s, false);
    if ($partMarket)
        foreach ($partMarket as $item) {

            $link = "?page=market&sub=partmarket&mode=buy&id=" . $item["id"];

            $output .= "<tr>";
            $output .= "<td class='partSeller'><a href='$link'>" . $item["username"] . "</a></td>
                <td class='partTitle'><a href='$link'>" . put($item["part"], $l) . " (" . $item["liga"] . ")</a></td>
                <td class='partPerf'><a href='$link'>" . $item["value"] . " " . put("unit_" . $item["kat"], $l) . "</a></td>
                <td><a href='$link'>" . dollar($item["sell"]) . "</a></td>";
            $output .= "</tr>";
        } else {
        $output .= "<tr><td colspan='4'>" . put("market_empty", $l) . "</td></tr>";
    }

    $output .= "</table>";

    //Anzahl der Seiten ausgegeben
    if ($menge > 1)
        for ($a = 0; $a < $menge; $a++) {
            $b = $a + 1;

            //Wenn der User sich auf dieser Seite befindet, keinen Link ausgeben 
            if ($s == $b) {
                $output .= "  <b>$b</b> ";
            }

            //Aus dieser Seite ist der User nicht, also einen Link ausgeben 
            else {
                $output .= "  <a href=\"?page=market&sub=partmarket&s=$b\">$b</a> ";
            }
        }
}

$output .= "</div>";

/*


$seite = $_GET["seite"];  //Abfrage auf welcher Seite man ist 

//Wenn man keine Seite angegeben hat, ist man automatisch auf Seite 1 
if(!isset($seite)) 
   { 
   $seite = 1; 
   } 

//Verbindung zu Datenbank aufbauen 

$link = mysql_connect("localhost","Username","Passwort") or die ("Keine Verbindung moeglich"); 
mysql_select_db("Datenbank") or die ("Die Datenbank existiert nicht"); 


//Einträge pro Seite: Hier 15 pro Seite 
$eintraege_pro_seite = 15; 

//Ausrechen welche Spalte man zuerst ausgeben muss: 

$start = $seite * $eintraege_pro_seite - $eintraege_pro_seite; 


//Tabelle Abfragen 
//Tabelle hei&szlig;t hier einfach: Tabelle 
$abfrage = "SELECT * FROM Tabelle LIMIT $start, $eintraege_pro_seite"; 
$ergebnis = mysql_query($abfrage); 
while($row = mysql_fetch_object($ergebnis)) 
    { 
   echo $row->id."<br>"; // Hier die Ausgabe der Einträge 
   } 


//Jetzt kommt das "Inhaltsverzeichnis", 
//sprich dort steht jetzt: Seite: 1 2 3 4 5 


//Wieviele Einträge gibt es überhaupt 

//Wichtig! Hier muss die gleiche Abfrage sein, wie bei der Ausgabe der Daten 
//also der gleiche Text wie in der Variable $abfrage, blo&szlig; das hier das LIMIT fehlt 
//Sonst funktioniert die Blätterfunktion nicht richtig, 
//und hier kann nur 1 Feld abgefragt werden, also id 

$result = mysql_query("SELECT id FROM Tabelle"); 
$menge = mysql_num_rows($result); 

//Errechnen wieviele Seiten es geben wird 
$wieviel_seiten = $menge / $eintraege_pro_seite; 

//Ausgabe der Seitenlinks: 
echo "<div align=\"center\">"; 
echo "<b>Seite:</b> "; 


//Ausgabe der Links zu den Seiten 
for($a=0; $a < $wieviel_seiten; $a++) 
   { 
   $b = $a + 1; 

   //Wenn der User sich auf dieser Seite befindet, keinen Link ausgeben 
   if($seite == $b) 
      { 
      echo "  <b>$b</b> "; 
      } 

   //Aus dieser Seite ist der User nicht, also einen Link ausgeben 
   else 
      { 
      echo "  <a href=\"?seite=$b\">$b</a> "; 
      } 


   } 
echo "</div>"; 

*/