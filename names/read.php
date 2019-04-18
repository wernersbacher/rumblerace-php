<?php

include("../_mysql_names.php");

$dirs = array_filter(glob('*'), 'is_dir');

foreach ($dirs as $lang) {
    echo "$lang<br/>";
    
    $first_arr =  array();
    $last_arr = array();
    

    if ($handle = opendir("./$lang")) {
        while (false !== ($file = readdir($handle))) {
            if($file == "." OR $file =="..")
                continue;
            
            echo "- $file<br/>";
            
            $lines = file("./$lang/$file", FILE_IGNORE_NEW_LINES);
            foreach($lines as $name) {
                $teile = explode(" ", $name);
                $first =  $teile[0];
                $last = $teile[1];
                
                array_push($first_arr, "('".$first."')");
                array_push($last_arr, "('".$last."')");
                
                echo "- - $first:$last<br/>";
            }
            unlink("./$lang/$file");
            
        }
        
    closedir($handle);
    
    $sqlf = "INSERT INTO " .$lang."_first (name) values 
        ".implode(",", $first_arr)."
         ON DUPLICATE KEY UPDATE name=name";
    $sqll = "INSERT INTO " .$lang."_last (name) values 
        ".implode(",", $last_arr)."
         ON DUPLICATE KEY UPDATE name=name";
    //echo $sqlf;
    
    queryNamesSQL($sqlf);
    queryNamesSQL($sqll);
    
    }
}



