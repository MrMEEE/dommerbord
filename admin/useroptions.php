<?php

require("connect.php");
require("config.php");
require("checkConfig.php");
require("checkLogin.php");
require("theme.php");
require_once("commonFunctions.php");

$error="";

if((isset($_POST["changepasswd"]))){

  switch(userVerifyPassword($_POST["passwd1"],$_POST["passwd2"])){
      case "0":
            $error="De to adgangskodefelter er ikke ens!!";
      break;
      case "1":
            $error="Indtast venligst adgangskoden i begge felter!!";
      break;
      default:
            $error = userChangePassword($_POST["changepasswd"],$_POST["passwd1"]);
      break;    
  }
}

getThemeHeader();

?>

function openWindow(userid){
  
  var path = "changePasswd.php?id=" + userid; 
  window.open(path,"mywindow","menubar=1,resizable=1,width=350,height=250");

}



<?php 

getThemeTitle("Bruger konfiguration");

require("menu.php"); 

echo '<font color="red">'.$error.'</font>';

echo '<form method=post name="changepasswd">
<input type="hidden" name="passwd1">
<input type="hidden" name="passwd2">
<input type="hidden" name="changepasswd">
</form>';

$user = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `name`='".$_SESSION['username']."'"));

echo "<h3>Brugernavn</h3>";

echo $user['name']."<br><br>";

echo '<a href="javascript:openWindow('.$user['id'].')">Skift Adgangskode</a>';

getThemeBottom();

?>                            

