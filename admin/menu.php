<?php

function getSite() {

 $uri = $_SERVER["REQUEST_URI"];
 if ( substr_count($uri, "?") > 0 ){
   $site = substr($uri,0,stripos($uri,"?"));
 }else{
   $site = $uri;
 }
 
 return strrchr($site, "/");
 
}

$link = "Dommerplan";
$url = '<a href="http://' . $klubadresse . $klubpath . '/admin/">';

if (getSite() != "/"){

$link = $url . $link . "</a>";

}

echo "$link | ";

$link = "Opdater Kampprogram";
$url = '<a href="http://' . $klubadresse . $klubpath . '/sync.php">';  

if (getSite() != "/sync.php"){

$link = $url . $link . "</a>";

} 
  
echo "$link | ";

$link = "Tilføj/Vis Hold/Personer";
$url = '<a href="http://' . $klubadresse . $klubpath . '/admin/people.php">';

if (getSite() != "/people.php"){ 

$link = $url . $link . "</a>";

} 
 
echo "$link | ";

$link = "Tilføj/Vis alle klubbens hold";
$url = '<a href="http://' . $klubadresse . $klubpath . '/admin/addallsources.php">';

if (getSite() != "/addallsources.php"){ 

$link = $url . $link . "</a>";

} 
 
echo "$link | ";

$link = "Konfiguration";
$url = '<a href="http://' . $klubadresse . $klubpath . '/admin/configuration.php">';

if (getSite() != "/configuration.php"){

$link = $url . $link . "</a>";  

}
 
echo $link;
 

?>
<br><br>



