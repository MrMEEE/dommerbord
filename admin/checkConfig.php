<?php

if(!file_exists("connect.php")){
 ob_start();
 header( "Location: setup.php" );
 ob_flush();
}

require("config.php");
require("connect.php");

$config=mysql_fetch_assoc(mysql_query("SELECT * FROM config WHERE id = '1'"));
if (($config['klubadresse']=="") || ($config['klubpath']=="") || ($config['klubnavn']=="")){
 ob_start();
 header( "Location: configuration.php" );
 ob_flush();
}

?>