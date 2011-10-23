<?php

require "connect.php";

  $klubid = 23;

  //$dom = new DOMDocument();
  $url        = "http://resultater.basket.dk/tms/Turneringer-og-resultater/Forening-Holdoversigt.aspx?ForeningsId=$klubid";
  $input = @file_get_contents($url) or die("Could not access file: $url");
  $regexp = "PuljeId=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
  $regexp2 = "RaekkeId=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
  
  if(preg_match_all("/$regexp/siU", $input, $matches)) { 
  // $matches[2] = array of link addresses // $matches[3] = array of link text - including HTML code 
  }
  if(preg_match_all("/$regexp2/siU", $input, $matches2)) {
  
  }
  $i=0;
  foreach ($matches[2] as $urls){
   $name=$matches2[3][$i];
    if(mysql_num_rows(mysql_query("SELECT * FROM calendars WHERE address = 'http://resultater.basket.dk/tms/Turneringer-og-resultater/Pulje-Komplet-Kampprogram.aspx?PuljeId=$urls'"))){
    echo "$name already exists<br>";
    }
    else{
        mysql_query("INSERT into calendars (`address`, `team`) VALUES ('http://resultater.basket.dk/tms/Turneringer-og-resultater/Pulje-Komplet-Kampprogram.aspx?PuljeId=$urls', '$name')");
    
 print("Added: http://resultater.basket.dk/tms/Turneringer-og-resultater/Pulje-Komplet-Kampprogram.aspx?PuljeId=$urls, as: $name<br>");
 }
  $i=$i+1;
  }

$query = mysql_query("SELECT * FROM `calendars`");

// Filling the $todos array with new ToDo objects:

while($row = mysql_fetch_assoc($query)){
    echo $row['team'];
        echo ": ";
            echo $row['address'];
                echo "<br>";
                }
                  

?>
