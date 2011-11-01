<?php
//start the session
session_start();

//check to make sure the session variable is registered
if(session_is_registered('username')){

//session variable is registered, the user is ready to logout
session_unset();
session_destroy();
}

//the session variable isn't registered, the user shouldn't even be on this page
header( "Location: login.php" );
?> 