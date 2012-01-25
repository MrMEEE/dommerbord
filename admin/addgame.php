<?php

require("config.php");
require("connect.php");
require("checkLogin.php");
require("checkAdmin.php");
require("todo.class.php");
require("commonFunctions.php");
require("theme.php");

$message = "";
$color = "white";

$date = "00/00-0000";
$time = "00:00:00";
$description = "Beskrivelse af Kampen";

if (isset($_POST['date'])){
 if(($_POST['date'] != "00/00-0000") && ($_POST['date'] != "")){
  if(($_POST['time'] != "00:00:00") && ($_POST['time'] != "")){
   if(($_POST['description'] != "Beskrivelse af Kampen") && ($_POST['description'] != "")){
    $date = convertDate($_POST['date']);
    echo $date;
    mysql_query("INSERT INTO games SET text='".$_POST['description']."',time='".$_POST['time']."',position = 9999999,status = 1, date='$date'");
    $message = "Kamp Tilføjet";
    $color = "green";
   }else{
    $message = "Angiv venligst en beskrivelse!";
    $color = "red";
   }
  }else{
   $message = "Angiv venligst tidspunktet!";
   $color = "red";
  }
 }else{
  $message = "Angiv venligst datoen!";
  $color = "red";
 }
 $date = $_POST['date'];
 $time = $_POST['time'];
 $description = $_POST['description'];
}

getThemeHeader();
getThemeTitle("Tilføj Kamp");

require("menu.php"); 
require("gamemenu.php"); 
echo '<font color="'.$color.'">'.$message.'</font>'; 

?>

<form method="post">
<table>
<tr><td>Dato:</td><td><input type="text" name="date" value="<?php echo $date ?>"></td></tr>
<tr><td>Tidspunkt:</td><td><input type="text" name="time" value="<?php echo $time ?>"></td></tr>
<tr><td>Beskrivelse:</td><td><input type="text" name="description" value="<?php echo $description ?>"></td></tr>
<tr><td rowspan=2><input name="addgame" type="submit" value="Tilføj"></td></tr>
</table>
</form><br><br>

<?php
getThemeBottom();

?>