<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");

getThemeHeader();

?>

function removeTicketType(typeid){
 
 answer = confirm("Er du sikker på at du vil slette denne billettype??")
 
 if (answer !=0)
 {
   document.type.typeid.value=typeid;
   document.type.action.value="remove";
   document.type.submit();
 }
 
}

function editTicketType(typeid){
 
   var path = "bballtickets_tickettypes_info.php?typeid=" + typeid;
   mywindow = window.open(path,"mywindow","menubar=1,resizable=1,width=500,height=480");
   
}

function copyTicketType(typeid,typename){

 var newname = prompt("Indtast navn til kopien","Kopi af "+typename);
 
 if (newname==null || newname==""){
 
   alert("Det nye navn må ikke være tomt.");
 
 }else{
 
   document.type.typeid.value=typeid;
   document.type.typename.value=newname;
   document.type.action.value="copy";
   document.type.submit();
 
 }

}

<?php

getThemeTitle("Billettyper");

require("../../menu.php");

require("bballtickets_check_database.php");

if(isset($_POST['typeid'])){
      switch($_POST['action']){
             case "remove" :
                    $query = "DELETE FROM bballtickets_tickettypes WHERE id='".$_POST['typeid']."'";
             break;
             case "create" :
                    $query = "INSERT INTO bballtickets_tickettypes (`name`,`group`,`seats`,`expires`,`access`) VALUES ('".$_POST['name']."','".$_POST['group']."','".$_POST['seats']."','".$_POST['expires']."','".$_POST['access']."')";
             break;
             case "copy" : 
                    $source = mysql_fetch_assoc(mysql_query("SELECT * FROM bballtickets_tickettypes WHERE `id`='".$_POST['typeid']."'"));
                    $query = "INSERT INTO bballtickets_tickettypes (`name`,`group`,`seats`,`expires`,`access`) VALUES ('".$_POST['typename']."','".$source['group']."','".$source['seats']."','".$source['expires']."','".$source['access']."')";
             break;
             default:
                    $query = "UPDATE bballtickets_tickettypes SET `name`='".$_POST['name']."',`group`='".$_POST['group']."',`seats`= '".$_POST['seats']."',`expires`= '".$_POST['expires']."',`access`= '".$_POST['access']."' WHERE id = '".$_POST['typeid']."'";
             break; 
      }
      mysql_query($query);
}

$query = mysql_query("SELECT * FROM `bballtickets_tickettypes` WHERE `expires` > CURDATE() OR `expires`=0000-00-00");

while($row = mysql_fetch_assoc($query)){
      
      if($row["seats"]== "unlimited"){
           $seats = "et ubegrænset antal";
      }else{
           $seats = $row["seats"];
      }
      
      $types .= '<a href="javascript:void(removeTicketType(\''.$row["id"].'\'))" title="Slet Billettype"><img width="15px" src="img/remove.png"></a>
      <a href="javascript:void(copyTicketType(\''.$row["id"].'\',\''.$row["name"].'\'))" title="Kopier Billettype">
      <img width="15px" src="img/copy.png"></a>
      <a href="javascript:void(editTicketType(\''.$row["id"].'\'))" title="Rediger Billettype">
      <img width="15px" src="img/edit.png"></a> '.$row["name"].' - Giver adgang for '.$seats.' person(er)<br>';

}

$query = mysql_query("SELECT * FROM `bballtickets_tickettypes` WHERE `expires` < CURDATE() AND `expires` != '0000-00-00'");

while($row = mysql_fetch_assoc($query)){
      
      if($row["seats"]== "unlimited"){
           $seats = "et ubegrænset antal";
      }else{
           $seats = $row["seats"];
      }
      
      $oldtypes .= '<a href="javascript:void(removeTicketType(\''.$row["id"].'\'))" title="Slet Billettype"><img width="15px" src="img/remove.png"></a>
      <a href="javascript:void(copyTicketType(\''.$row["id"].'\',\''.$row["name"].'\'))" title="Kopier Billettype">
      <img width="15px" src="img/copy.png"></a>
      <a href="javascript:void(editTicketType(\''.$row["id"].'\'))" title="Rediger Billettype">
      <img width="15px" src="img/edit.png"></a> '.$row["name"].' - Giver adgang for '.$seats.' person(er)<br>';

}


echo "<h3>Billettyper:</h3> <br>".$types."<br><br>";

?>

<form method="post" name="type">
  <input type="hidden" id="typeid" name="typeid" value="">
  <input type="hidden" id="typename" name="typename" value="">
  <input type="hidden" id="action" name="action" value="">
  <input type="hidden" id="name" name="name" value="">
  <input type="hidden" id="seats" name="seats" value="">
  <input type="hidden" id="group" name="group" value="">
  <input type="hidden" id="expires" name="expires" value="">
  <input type="hidden" id="access" name="access" value="">
</form>


<?php

echo '<a href="javascript:void(0)" onclick="editTicketType(-1);" title="Tilføj Billettype"><img width="25px" src="img/add.png"></a> <font size="3">Tilføj Billettype</font><br><br>';

echo "<h3>Udløbne Billettyper:</h3> <br>".$oldtypes."<br><br>";

getThemeBottom();

?>
