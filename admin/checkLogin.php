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
            

//start the session
session_start();

//check to make sure the session variable is registered
//if(session_is_registered('username')){
if (isset($_SESSION['username'])){

//the session variable is registered, the user is allowed to see anything that follows

}
else{

//the session variable isn't registered, send them back to the login page
header( "Location: login.php" );
}

?> 