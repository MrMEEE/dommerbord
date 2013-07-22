<?php

require("connect.php");
require("commonFunctions.php");
require("checkLogin.php");
require("checkAdmin.php");
require("theme.php");

if (checkAdmin($_SESSION['username'])){ 

if(isset($_POST["clubselect0"])){
    $clubinfo = explode(":",$_POST["clubselect0"]);
    $clubids = $clubinfo[0];
    $clubname = $clubinfo[1];
    $i = 1;
    while(isset($_POST["clubselect".$i])){
       if($_POST["clubselect".$i] != ":"){
          $clubinfo = explode(":",$_POST["clubselect".$i]);
          $clubids .= ",".$clubinfo[0];
       }
       $i++;
    }
    
    mysql_query("UPDATE config SET klubnavn='".$clubname."' WHERE id = 1");
    mysql_query("UPDATE `config` set `klubid`='".$clubids."' WHERE id = 1");
    
}

if(isset($_POST["clubpath"])){
    mysql_query("UPDATE `config` set `klubpath`='".$_POST['clubpath']."' WHERE id = 1");                                                                              
}

if(isset($_POST["clubaddress"])){
    mysql_query("UPDATE `config` set `klubadresse`='".$_POST['clubaddress']."' WHERE id = 1");
}

if(isset($_POST["mobileaddress"])){
    mysql_query("UPDATE `config` set `mobileaddress`='".$_POST['mobileaddress']."' WHERE id = 1");
}
    
if(isset($_POST["debug"])){
    mysql_query("UPDATE `config` set `debug`='".$_POST['debug']."' WHERE id = 1");                                                                              
}

if(isset($_POST["updatesurl"])){
    mysql_query("UPDATE `config` set `updatesurl`='".$_POST['updatesurl']."' WHERE id = 1");
}
        
}

if(isset($_POST["updatedatabase"])){
  $sql = explode(';', file_get_contents ('sql/dommerbord.sql'));
  $n = count ($sql) - 1;
  for ($i = 0; $i < $n; $i++) {
    $query = $sql[$i];
    $result = mysql_query ($query) or die ('<p>Query: <br><tt>' . $query . '</tt><br>failed. MySQL error: ' . mysql_error());
  }
}
require "config.php";

if($debug==0){
error_reporting(0);
}

if (mysql_num_rows(mysql_query("SELECT * FROM config"))==0){

 mysql_query("INSERT INTO `config` (`id`,`debug`) VALUES ('1','0')");
 mysql_query("INSERT INTO `games` (`id`,`date`) VALUES ('1000000','0000-00-00')");
 mysql_query("UPDATE config SET updatesurl='http://www.dommerplan.dk/updates/' WHERE id = 1");
} 

getThemeHeader();

?>

function FormSubmitClub(el) {
var clubinfo = el.value.split(':');
document.clublist.action = 'configuration.php?klubid=' + clubinfo[0] + '&klubnavn=' + clubinfo[1];
document.clublist.submit();
return;
}

<?php

getThemeTitle("Konfiguration");

if ( ($klubadresse != "") && ($klubpath != "") && ($klubnavn != "") ){

 require("menu.php"); 

}else{

echo '<br><br><font color="red">Sæt venligst alle indstillinger!!!</font><br><br>';

}

if(isset($_POST["updatedatabase"])){

echo '<br><font color="red">Database Opdateret</font><br><br>';

}

?>

<?php

list ($clubs,$ids) = getClubs();

if(mysql_num_rows(mysql_query("SELECT klubid FROM config WHERE id = 1 AND klubid = ''"))){
   $clublist.= "<option value=\":\" selected>Ingen klub valgt</option>";
}else{
   $clublist.= "<option value=\":\">Ingen klub valgt</option>";
}
for ($i = 0; $i < count($clubs); $i++){
   if($ids[$i]==$klubid){
      $clublist.= "<option value=\"".$ids[$i].":".$clubs[$i]."\" selected>".$clubs[$i]."</option>";
   }else{
      $clublist.= "<option value=\"".$ids[$i].":".$clubs[$i]."\">".$clubs[$i]."</option>";
   }
}

if ($debug == 0){
   $debuglist.="<option value=\"1\">Til</option>";
   $debuglist.="<option value=\"0\" selected>Fra</option>";
}else {
   $debuglist.="<option value=\"1\" selected>Til</option>";                      
   $debuglist.="<option value=\"0\">Fra</option>";
}


?>

Vælg Klub:
<form method="post" name="clublist" action="configuration.php">
    <select name="clubselect0">
       <?php echo $clublist; ?>
    </select>
<br>
<br>Søsterklubber:<br>
<?php 
for ($i = 1; $i <= count($klubids)-1; $i++){
if($klubids[$i]==""){
   $clublist = "<option value=\":\" selected>Ingen klub valgt/Fjern Søsterklub</option>";
}else{
   $clublist = "<option value=\":\">Ingen klub valgt/Fjern Søsterklub</option>";
}
   
for ($k = 0; $k < count($clubs); $k++){
   if($ids[$k]==$klubids[$i]){
      $clublist.= "<option value=\"".$ids[$k].":".$clubs[$k]."\" selected>".$clubs[$k]."</option>";
   }else{
      $clublist.= "<option value=\"".$ids[$k].":".$clubs[$k]."\">".$clubs[$k]."</option>";
   }
}

echo '<select name="clubselect'.$i.'">
          '.$clublist.' 
      </select><br>';

}

if(isset($_GET['addsisterclub'])){

$clublist = "<option value=\":\" selected>Ingen klub valgt/Fjern Søsterklub</option>";

for ($k = 0; $k < count($clubs); $k++){
   $clublist.= "<option value=\"".$ids[$k].":".$clubs[$k]."\">".$clubs[$k]."</option>";
}

echo '<select name="clubselect'.$i.'">
          '.$clublist.' 
      </select><br>';

}

echo '<br><a href="configuration.php?addsisterclub"><img width="15px" src="img/add.png"></a>Tilføj Søsterklub<br>';
?>
<br>
<br>Adresse på side:<br>
<input type="text" name="clubaddress" value="<?php echo $klubadresse; ?>"><br>

<br>
<br>Adresse på Mobilside:<br>
<input type="text" name="mobileaddress" value="<?php echo $mobileaddress; ?>"><br>

<br>

<br>Installationssti:<br>
<input type="text" name="clubpath" value="<?php echo $klubpath; ?>"><br>

<br>

<br>Debug:
    <select name="debug" onChange="FormSubmitDebug(this)">
       <?php echo $debuglist; ?>
    </select>

<br>
<br>URL til Opdateringer:<br>
<input type="text" name="updatesurl" value="<?php echo $updatesurl; ?>"><br>
<br><input type="submit" value="Gem Indstillinger">
</form>


<?php

getThemeBottom();

?>
