<?php

require "connect.php";
require "getclubs.php";

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

require "config.php";

if($debug==0){
error_reporting(0);
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript">
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

</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $klubnavn; ?> Dommerbordsplan</title>

<!-- Including the jQuery UI Human Theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/humanity/jquery-ui.css" type="text/css" media="all" />

<!-- Our own stylesheet -->
<link rel="stylesheet" type="text/css" href="styles.css" />

</head>

<body>

<h1>Konfiguration</h1>

<div id="main">

<?php require("menu.php"); ?>

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




<?php

?>
