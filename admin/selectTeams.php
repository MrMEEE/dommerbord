<?php

require("connect.php");
require("theme.php");

if(isset($_POST['selectteams'])){


 $teams=$GLOBALS['_POST'];
 var_dump($GLOBALS['_POST']);
 array_pop($teams);
 array_pop($teams);
 $id=array_pop($teams);
 $teamstring="";
 foreach ($teams as $team){
   $teamstring .= $team;
   $teamstring .= ",";
 }
 
 
 switch($_POST['user']){ 
    case "1":
     $query = "UPDATE `users` SET `teams` = '$teamstring' WHERE `id` = ".$_POST['id']; 
     break;
    case "2":
     $query = "UPDATE `users` SET `refs` = '".$_POST['ref']."' WHERE `id` = ".$_POST['id'];
     break;
    default: 
     $query = "UPDATE `teams` SET `teamid` = '$teamstring' WHERE `id` = $id";
 }
 echo $query;
 mysql_query($query);
 echo "<SCRIPT LANGUAGE=\"javascript\">";
 echo "window.close();";
 echo "</SCRIPT>";
}

getThemeHeader();

$id = $_GET['id'];

switch($_GET['user']){
 case 1:
  getThemeTitle("Tilknyt Hold");
  echo 'Tilknyt hold til ' . $_GET["name"] . '<br><br>';
  $query = mysql_query("SELECT * FROM `users` WHERE `id` = $id");
  $team = mysql_fetch_assoc($query);
  $teamarray = explode(",", $team['teams']);
  $query = mysql_query("SELECT * FROM `calendars` ORDER by `team`");
  $rowname = "team";
  break;

 case 2:
  getThemeTitle("Tilknyt Dommer");
  echo 'Tilknyt Dommer til ' . $_GET["name"] . '<br><br>';
  $query = mysql_query("SELECT * FROM `users` WHERE `id` = $id");
  $team = mysql_fetch_assoc($query);
  $teamarray = explode(",", $team['refs']);
  $query = mysql_query("SELECT * FROM `teams` ORDER by `name`");
  $rowname = "name";
  break;
  
 default:
  getThemeTitle("Tilknyt Hold");
  echo 'Tilknyt hold til ' . $_GET["name"] . '<br><br>';
  $query = mysql_query("SELECT * FROM `teams` WHERE `id` = $id");
  $team = mysql_fetch_assoc($query);
  $teamarray = explode(",", $team['teamid']);
  $query = mysql_query("SELECT * FROM `calendars` ORDER by `team`");
  $rowname = "team";
}

echo '<form method=post name="selectteams" action="selectTeams.php">';

if($rowname == "name"){
    echo '<select name="ref">';
}

while($teams = mysql_fetch_assoc($query)){

if($teams["id"] != 9999){

if($rowname == "name"){
    echo '<option value="'.$teams["id"].'" ';
    if($team['refs'] == $teams["id"]){
       echo 'selected';
    }
    echo '>'.$teams[$rowname].'</option>';

}else{
echo '<font size="1"><input type="checkbox" name="'.$teams["id"].'" value="'.$teams["id"].'" ';

if(in_array($teams["id"], $teamarray)){
 echo 'checked';
}

echo '>'.$teams[$rowname].'</font><br>';


}

}
}

if($rowname == "name"){
    echo '</select><br>';
    echo '<br><input name="selectteams" type="submit" value="Vælg Dommer"><br><br>';
}else{
    echo '<br><input name="selectteams" type="submit" value="Vælg Hold"><br><br>';
}

echo '<input name="id" value="'.$id.'" type="hidden">';

echo '<input name="user" type="hidden" value="'.$_GET["user"].'">';

echo '</form><br>';

getThemeBottom();

?>


