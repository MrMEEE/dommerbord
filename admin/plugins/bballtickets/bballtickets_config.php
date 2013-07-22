<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");

getThemeHeader();

getThemeTitle("Billet Konfiguration");

require("../../menu.php");

require("bballtickets_check_database.php");

if(isset($_POST['template'])){

      mysql_query("UPDATE `bballtickets_config` SET `template`='".$_POST['template']."' WHERE `id`='1'");

}

$config = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_config` WHERE `id`=1"));

$hold = explode(",",$config['hold']);

if(isset($_GET['add'])){

      if(!in_array($_GET['add'],$hold)){
            array_push($hold,$_GET['add']);
            $holdstr = implode(",",$hold);
            mysql_query("UPDATE `bballtickets_config` SET `hold`='".$holdstr."' WHERE `id`='1'");
      }

}

if(isset($_GET['remove'])){

      if(in_array($_GET['remove'],$hold)){
            $hold = array_diff($hold,array($_GET['remove']));
            $holdstr = implode(",",$hold);
            if($holdstr == ","){
                  $holdstr = "";
            }
            mysql_query("UPDATE `bballtickets_config` SET `hold`='".$holdstr."' WHERE `id`='1'");
      }
}
$stats = "";
$nostats = "";

$query = mysql_query("SELECT * FROM `calendars`");

while($row = mysql_fetch_assoc($query)){

      if(in_array($row['id'],$hold)){
            $stats .= '<a href="bballtickets_config.php?remove='.$row['id'].'"><img width="15px" src="img/remove.png"></a> '.$row['team'].'<br>';
      }else{
            $nostats .= '<a href="bballtickets_config.php?add='.$row['id'].'"><img width="15px" src="img/add.png"></a> '.$row['team'].'<br>';
      }

}

echo "<h3>Kort Template:</h3><br>";
echo '<form method="post">';
echo '<select id="template" name="template" onchange="this.form.submit()">';
if(($config['template'] == "") && ($config['template'] == "")){
    $status = "selected";
}

echo '<option value="-" '.$status.'>--- Ingen template valgt ---</option>';

if ($handle = opendir('templates/')) {

    while (false !== ($entry = readdir($handle))) {
         if(($entry != ".") && ($entry != "..")){
               if($config['template'] == $entry){
                     $status = "selected";
               }
               echo '<option value"'.$entry.'" '.$status.'>'.$entry.'</option>';
         }
    }
}
echo "</select>";
echo "</form>";
echo "<br><br>";

echo "<h3>Hold med billetsystem aktiveret:</h3> <br>".$stats."<br><br>";
echo "<h3>Hold uden billetsystem aktiveret:</h3> <br>".$nostats."<br><br>";

?>

<?php
getThemeBottom();

?>
