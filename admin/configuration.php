<?php

require("connect.php");
require("commonFunctions.php");
require("checkLogin.php");
require("checkAdmin.php");
require("theme.php");

if (checkAdmin($_SESSION['username'])){ 
if(isset($_GET["klubnavn"])){
    $klubnavn = $_GET['klubnavn'];
    mysql_query("UPDATE config SET klubnavn='$klubnavn' WHERE id = 1");
}

if(isset($_GET["klubid"])){
    $klubid=$_GET['klubid'];
    mysql_query("UPDATE `config` set `klubid`='$klubid' WHERE id = 1");                                                                              
}

if(isset($_GET["klubpath"])){
    $klubpath=$_GET['klubpath'];
    mysql_query("UPDATE `config` set `klubpath`='$klubpath' WHERE id = 1");                                                                              
}

if(isset($_GET["klubadresse"])){
    $klubadresse=$_GET['klubadresse'];
    mysql_query("UPDATE `config` set `klubadresse`='$klubadresse' WHERE id = 1");                                                                              
}
    
if(isset($_GET["debug"])){
    $debug=$_GET['debug'];
    mysql_query("UPDATE `config` set `debug`='$debug' WHERE id = 1");                                                                              
}

if(isset($_GET["updatesurl"])){
    $updatesurl=$_GET['updatesurl'];
    mysql_query("UPDATE `config` set `updatesurl`='$updatesurl' WHERE id = 1");
}
        
}

if(isset($_GET["updatedatabase"])){
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
function FormSubmitDebug(el) {
document.debuglist.action = 'configuration.php?debug=' + el.value;
document.debuglist.submit();
}
function FormSubmitAddress() {
document.klubadressepost.action = 'configuration.php?klubadresse=' + document.klubadressepost.klubadresse.value;
document.klubadressepost.submit();
}
function FormSubmitPath() {
document.klubpathpost.action = 'configuration.php?klubpath=' + document.klubpathpost.klubpath.value;
document.klubpathpost.submit();
}
function FormSubmitUpdatesUrl() {
document.updatesurlpost.action = 'configuration.php?updatesurl=' + document.updatesurlpost.updatesurl.value;
document.updatesurlpost.submit();
}

<?php

getThemeTitle("Konfiguration");

if ( ($klubadresse != "") && ($klubpath != "") && ($klubnavn != "") ){

 require("menu.php"); 

}else{

echo '<br><br><font color="red">Sæt venligst alle indstillinger!!!</font><br><br>';

}

if(isset($_GET["updatedatabase"])){

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
<form method="post" name="clublist">
    <select name="clubselect" onChange="FormSubmitClub(this)">
       <?php echo $clublist; ?>
    </select>
</form>

<br>

<form method="post" name="klubadressepost">
Adresse på side:<br>
<input type="text" name="klubadresse" value="<?php echo $klubadresse; ?>"><br>
<input type="submit" value="Sæt" onClick="FormSubmitAddress()">
</form>

<br>

<form method="post" name="klubpathpost">
Installationssti:<br>
<input type="text" name="klubpath" value="<?php echo $klubpath; ?>"><br>
<input type="submit" value="Sæt" onClick="FormSubmitPath()">
</form>

<br>

Debug:
<form method="post" name="debuglist">
    <select name="debugselect" onChange="FormSubmitDebug(this)">
       <?php echo $debuglist; ?>
    </select>
</form>

<br>
<form method="post" name="updatesurlpost">
URL til Opdateringer:<br>
<input type="text" name="updatesurl" value="<?php echo $updatesurl; ?>"><br>
<input type="submit" value="Sæt" onClick="FormSubmitUpdatesUrl()">
</form>


<?php

getThemeBottom();

?>
