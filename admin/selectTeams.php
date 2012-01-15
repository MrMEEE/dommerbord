<?php

require("connect.php");

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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<SCRIPT LANGUAGE="javascript">
</SCRIPT>
<!-- Including the jQuery UI Human Theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/humanity/jquery-ui.css" type="text/css" media="all" />

<!-- Our own stylesheet -->
<link rel="stylesheet" type="text/css" href="styles.css" />

</head>

<body>
<h1>Tilknyt Hold</h1>


       
<?php

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
echo '</body>';

?>


