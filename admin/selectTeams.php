<?php

require("connect.php");
require("theme.php");

if(isset($_POST['selectteams'])){
 $teams=$GLOBALS['_POST'];
 array_pop($teams);
 $id=array_pop($teams);
 $teamstring="";
 foreach ($teams as $team){
   $teamstring .= $team;
   $teamstring .= ",";
 }
 $query = "UPDATE `teams` SET `teamid` = '$teamstring' WHERE `id` = $id";
 mysql_query($query);
 echo "<SCRIPT LANGUAGE=\"javascript\">";
 echo "window.close();";
 echo "</SCRIPT>";

}

getThemeHeader();
getThemeTitle("Tilknyt Hold");

echo 'Tilknyt hold til '.$_GET["name"].'<br><br>';

$id = $_GET['id'];

$query = mysql_query("SELECT * FROM `teams` WHERE `id` = $id");

$team = mysql_fetch_assoc($query);

$teamarray = explode(",", $team['teamid']);

$query = mysql_query("SELECT * FROM `calendars` ORDER by `team`");

echo '<form method=post name="selectteams" action="selectTeams.php">';

while($teams = mysql_fetch_assoc($query)){

echo '<font size="1"><input type="checkbox" name="'.$teams["id"].'" value="'.$teams["id"].'" ';

if(in_array($teams["id"], $teamarray)){
 echo 'checked';
}

echo '>'.$teams['team'].'</font><br>';

}
echo '<input name="id" value="'.$_GET["id"].'" type="hidden">';
echo '<br><input name="selectteams" type="submit" value="Vælg Hold"><br><br>';

echo '</form><br>';

getThemeBottom();

?>


