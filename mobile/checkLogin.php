<?php

require_once("../admin/config.php");

//start the session
session_start();

//check to make sure the session variable is registered
//if(session_is_registered('username')){
if (isset($_SESSION['username'])){

//the session variable is registered, the user is allowed to see anything that follows

}
else{

//the session variable isn't registered, send them back to the login page
if($mobileaddress!=""){
        header( "Location: http://$mobileaddress/login.php" );
}else{
        header( "Location: login.php" );
}
}

?> 