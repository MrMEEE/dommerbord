<?php

require_once("connect.php");

$query = mysql_query("SELECT * FROM `config` WHERE `id`=1");

$row = mysql_fetch_assoc($query);

$klubnavn=$row['klubnavn'];

$klubpath=$row['klubpath'];

$klubids=explode(',',$row['klubid']);

//Klub ID.. Kan finde på: http://resultater.basket.dk/tms/Turneringer-og-resultater/Soegning.aspx
$klubid=$klubids[0];

//Klub Adresse, fx: www.bmsbasket.dk
$klubadresse=$row['klubadresse'];

$updatesurl=$row['updatesurl'];

$debug=$row['debug'];

if($debug!=1){
    error_reporting(E_ERROR);
}

$mobileaddress=$row['mobileaddress'];

?>