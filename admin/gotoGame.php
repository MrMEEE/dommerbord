<?php

require_once("commonFunctions.php");

if(isset($_GET['gameID'])){
  $basketdkid=getGame($_GET['gameID']);
  header( 'Location: http://resultater.basket.dk/tms/Turneringer-og-resultater/Kamp-Information.aspx?KampId='.$basketdkid );    
}



?>