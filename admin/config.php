<?php

require_once("connect.php");

$query = mysql_query("SELECT * FROM `config` WHERE `id`=1");

$row = mysql_fetch_assoc($query);

$klubnavn=$row['klubnavn'];

$klubpath=$row['klubpath'];

//Klub ID.. Kan finde på: http://resultater.basket.dk/tms/Turneringer-og-resultater/Soegning.aspx
$klubid=$row['klubid'];

//Klub Adresse, fx: www.bmsbasket.dk
$klubadresse=$row['klubadresse'];

$updatesurl=$row['updatesurl'];

$debug=$row['debug'];

?>