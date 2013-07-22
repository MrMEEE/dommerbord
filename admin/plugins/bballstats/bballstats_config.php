<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");
require("bballstats_common.php");

getThemeHeader();

?>

function ConfirmRemoveType(type){
 
 answer = confirm("Er du sikker på at du vil slette denne type??\n Alle stats af denne type vil blive slettet!!")
 
 if (answer !=0)
 {
   document.typer.fjerntype.value=type;
   typer.submit();
 }
 
}

function redigerStat(statname){
 
  var path = "bballstats_config_stat.php?statname=" + statname;
  mywindow = window.open(path,"mywindow","menubar=1,resizable=1,width=550,height=450");
}

function flytStat(statnavn,retning){

  document.stat.statnavn.value=statnavn;
  document.stat.movestat.value=retning;
  stat.submit();

}

<?php

getThemeTitle("Statistik Konfiguration");

require("../../menu.php");

require("bballstats_check_database.php");

$config = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballstats_config` WHERE `id`=1"));

$hold = explode(",",$config['hold']);

if(isset($_GET['add'])){

      if(!in_array($_GET['add'],$hold)){
            array_push($hold,$_GET['add']);
            $holdstr = implode(",",$hold);
            mysql_query("UPDATE `bballstats_config` SET `hold`='".$holdstr."' WHERE `id`='1'");
      }

}

if(isset($_GET['remove'])){

      if(in_array($_GET['remove'],$hold)){
            $hold = array_diff($hold,array($_GET['remove']));
            $holdstr = implode(",",$hold);
            if($holdstr == ","){
                  $holdstr = "";
            }
            mysql_query("UPDATE `bballstats_config` SET `hold`='".$holdstr."' WHERE `id`='1'");
      }
}

$stats = "";
$nostats = "";

$query = mysql_query("SELECT * FROM `calendars`");

while($row = mysql_fetch_assoc($query)){

      if(in_array($row['id'],$hold)){
            $stats .= '<a href="bballstats_config.php?remove='.$row['id'].'"><img width="15px" src="img/remove.png"></a> '.$row['team'].'<br>';
      }else{
            $nostats .= '<a href="bballstats_config.php?add='.$row['id'].'"><img width="15px" src="img/add.png"></a> '.$row['team'].'<br>';
      }

}

echo "<h3>Hold med statistik aktiveret:</h3> <br>".$stats."<br><br>";
echo "<h3>Hold uden statistik aktiveret:</h3> <br>".$nostats."<br><br>";

echo '<h3>Statstyper:</h3><br>';

if(isset($_POST['statnavn']) && ($_POST['statnavn'] != "") && isset($_POST['oldname']) && ($_POST['oldname'] != "")){
      
      if(isset($_POST['operation']) && ($_POST['operation'] != "")){
      
            $newname = "£".$_POST['operation']."£".$_POST['statnavn'];
            
      }else{
      
            $newname = $_POST['statnavn'];
      
      }
      mysql_query("ALTER TABLE bballstats_stats CHANGE `".$_POST['oldname']."` `".$newname."` int(11)");

}

if(isset($_POST['nytype'])){
      if($_POST['nytype'] != ""){
            if(mysql_num_rows(mysql_query("SHOW COLUMNS FROM `bballstats_stats` WHERE `Field`='".$_POST['nytype']."'"))){
                  $message='<font color="red">"'.$_POST['nytype'].'" findes allerede..</font><br><br>';
            }else{
                  mysql_query("ALTER TABLE `bballstats_stats` ADD `".$_POST['nytype']."` INT NOT NULL");
                  $message='<font color="green">"'.$_POST['nytype'].'" tilføjet...</font><br><br>';
            }
      }
}

$statsorder = statsorderlist();

if(($_POST['movestat'] != "") && ($_POST['statnavn'] != "")){
      $place = array_search($_POST['statnavn'],$statsorder);
      if($_POST['movestat'] == "up"){
            if($place == 1){
                  mysql_query("ALTER TABLE `bballstats_stats` MODIFY COLUMN `".$_POST['statnavn']."` int(11) AFTER `kampid`");
            }elseif($place > 1){
                  $newplace = $place - 2;
                  mysql_query("ALTER TABLE `bballstats_stats` MODIFY COLUMN `".$_POST['statnavn']."` int(11) AFTER `".$statsorder[$newplace]."`");
            }
      }elseif($_POST['movestat'] == "down"){
            if($place < (count($statsorder)-1)){
                  $newplace = $place + 1;
                  mysql_query("ALTER TABLE `bballstats_stats` MODIFY COLUMN `".$_POST['statnavn']."` int(11) AFTER `".$statsorder[$newplace]."`");
            }
      
      
      }
      $statsorder = statsorderlist();
}

if(isset($_POST['fjerntype'])){
      if($_POST['fjerntype'] != ""){
            mysql_query("ALTER TABLE `bballstats_stats` DROP `".$_POST['fjerntype']."`");
            $message='<font color="orange">"'.$_POST['fjerntype'].'" blev slettet...</font><br><br>';
      }
}

echo $message;

$query = mysql_query("SHOW COLUMNS FROM `bballstats_stats`");

while($stattype = mysql_fetch_assoc($query)){
      if(($stattype['Field']!="id") && ($stattype['Field']!="spiller") && ($stattype['Field']!="kampid")){
      echo '<a href="javascript:void(ConfirmRemoveType(\''.$stattype["Field"].'\'))"><img width="15px" src="img/remove.png"></a>
      <a href="javascript:void(redigerStat(\''.$stattype["Field"].'\'))">
      <img width="15px" src="img/edit.png"></a>';
      
      if(array_search($stattype['Field'],$statsorder) == 0){
            echo ' <a href="javascript:void(flytStat(\''.$stattype["Field"].'\',\'down\'))"><img width="15px" src="img/down.png"></a>    ';
      }elseif(array_search($stattype['Field'],$statsorder) == (count($statsorder)-1)){
            echo ' <a href="javascript:void(flytStat(\''.$stattype["Field"].'\',\'up\'))"><img width="15px" src="img/up.png"></a>    ';
      }else{
            echo ' <a href="javascript:void(flytStat(\''.$stattype["Field"].'\',\'down\'))"><img width="15px" src="img/down.png"></a>';
            echo '<a href="javascript:void(flytStat(\''.$stattype["Field"].'\',\'up\'))"><img width="15px" src="img/up.png"></a>';
      } 

      if(substr($stattype["Field"],0,2)=="£"){
            list($start,$operation,$statnavn)=split("£",$stattype["Field"]);
      }else{
            $statnavn=$stattype["Field"];
      } 
      echo ' '.$statnavn.'<br>';
      }
}

echo '<br><form method="post" name="typer" id="typer">
            <input type="text" style="width:100px;" id="nytype" name="nytype">
            <input type="hidden" id="fjerntype" name="fjerntype" value="">
            <input type="submit" id="opretny" name="opretny" value="Tilføj">    
      </form>';

?>

<form method="post" id="stat" name="stat">
<input id="statnavn" type="hidden" name="statnavn">
<input id="operation" type="hidden" name="operation">
<input name="oldname" id="oldname" type="hidden">
<input name="opdater" type="hidden">
<input name="movestat" type=hidden id="movestat">
</form>

<?php
getThemeBottom();

?>
