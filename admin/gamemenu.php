<?php

function getGameSite() {

 $site = $_SERVER["REQUEST_URI"];
 /*if ( substr_count($uri, "?") > 0 ){
   $site = substr($uri,0,stripos($uri,"?"));
 }else{
   $site = $uri;
 }*/
 
 return strrchr($site, "/");
 
}

$link = "Alle Kommende Kampe";
$url = '<a href="http://' . $klubadresse . $klubpath . '/admin/">';

if (getGameSite() != "/"){

$link = $url . $link . "</a>";

}


echo "$link | ";

$link = "Utildelte Kampe";
$url = '<a href="http://' . $klubadresse . $klubpath . '/admin/index.php?view=unassigned">';  

if (getGameSite() != "/index.php?view=unassigned"){

$link = $url . $link . "</a>";

} 
  
echo "$link | ";

$link = "Flyttede Kampe";
$url = '<a href="http://' . $klubadresse . $klubpath . '/admin/index.php?view=moved">';

if (getGameSite() != "/index.php?view=moved"){ 

$link = $url . $link . "</a>";

} 
 
echo "$link | ";

$link = "Aflyste Kampe";
$url = '<a href="http://' . $klubadresse . $klubpath . '/admin/index.php?view=cancelled">';

if (getGameSite() != "/index.php?view=cancelled"){ 

$link = $url . $link . "</a>";

} 
 
echo "$link | ";

$link = "Alle Sæsonens Kampe";
$url = '<a href="http://' . $klubadresse . $klubpath . '/admin/index.php?view=all">';

if (getGameSite() != "/index.php?view=all"){

$link = $url . $link . "</a>";  

}
 
echo $link;
 

?>
<br><br>



