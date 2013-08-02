<?php

function checkAdmin($username){

$user=mysql_query("SELECT * FROM users WHERE name = '$username'");

if(mysql_num_rows($user)){

  $user = mysql_fetch_assoc($user);

  $admin = $user['admin'];

}else{

  $admin = 0;

}

return $admin;

}


?>