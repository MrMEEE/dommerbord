<?php

$install_path=$_SERVER['DOCUMENT_ROOT'].$klubpath;

if(!file_exists("$install_path/admin/connect.php")){
 ob_start();
 header( "Location: setup.php" );
 ob_flush();
}

require("$install_path/admin/config.php");
require("$install_path/admin/connect.php");

$config=mysql_fetch_assoc(mysql_query("SELECT * FROM config WHERE id = '1'"));
if (($config['klubadresse']=="") || ($config['klubpath']=="") || ($config['klubnavn']=="")){
 ob_start();
 header( "Location: http://' . $klubadresse . $klubpath . '/admin/configuration.php" );
 ob_flush();
}

?>