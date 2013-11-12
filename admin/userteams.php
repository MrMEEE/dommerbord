<?php

require("connect.php");
require("config.php");
require("checkConfig.php");
require("checkLogin.php");
require_once("commonFunctions.php");
require("theme.php");

getThemeHeader();

?>

<?php 

getThemeTitle("Bruger Info");

require("menu.php"); 

$user = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `name`='".$_SESSION['username']."'")); 

$teams = explode(",",$user['teams']);

echo "<h3>Navn i Dommerplanen</h3>";
if($user['refs']=="" || $user['refs']=="9999"){
    echo "Bruger ikke sat<br><br>";
}else{
    $refuser = mysql_fetch_assoc(mysql_query("SELECT * FROM `teams` WHERE `id`='".$user['refs']."'"));
    echo $refuser['name']."<br><br>";
}


echo "<h3>Mine Hold</h3>";

foreach($teams as $team){

  if($team != ""){
      $teaminfo = mysql_fetch_assoc(mysql_query("SELECT * FROM `calendars` WHERE `id`='".$team."'"));;
      echo $teaminfo['team']."<br>";
  }

}

getThemeBottom();

?>                            

