<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");

getThemeHeader();

?>

function removeCourt(courtid){
 
 answer = confirm("Er du sikker på at du vil slette denne bane??")
 
 if (answer !=0)
 {
   document.court.courtid.value=courtid;
   document.court.action.value="remove";
   document.court.submit();
 }
 
}

function removeSeatGroup(seatgroupid){
 
 answer = confirm("Er du sikker på at du vil slette denne plads-gruppe??")
 
 if (answer !=0)
 {
   document.seatgroup.seatgroupid.value=seatgroupid;
   document.seatgroup.action.value="remove";
   document.seatgroup.submit();
 }
 
}

function editCourt(courtid){
 
   var path = "bballtickets_courts_info.php?courtid=" + courtid;
   mywindow = window.open(path,"mywindow","menubar=1,resizable=1,width=1000,height=650");
   
}

function editSeatGroup(seatgroupid){
 
    var path = "bballtickets_courts_seatgroup.php?seatgroupid=" + seatgroupid;
    mywindow = window.open(path,"mywindow","menubar=1,resizable=1,width=1000,height=650");
          
}

<?php

getThemeTitle("Billet - Baner/Pladser");

require("../../menu.php");

require("bballtickets_check_database.php");

if(isset($_POST['courtid'])){

      if($_POST['action'] == "remove"){
             $query = "DELETE FROM bballtickets_courts WHERE id='".$_POST['courtid']."'";
      }elseif($_POST['courtid']=="-1"){
             $query = "INSERT INTO bballtickets_courts (`name`,`address`,`seats`) VALUES ('".$_POST['name']."','".$_POST['address']."','".$_POST['seats']."')";
      }else{
             $query = "UPDATE bballtickets_courts SET `name`='".$_POST['name']."',`address`='".$_POST['address']."',`seats`= '".$_POST['seats']."' WHERE id = '".$_POST['courtid']."'";
      }
      mysql_query($query);
}

if(isset($_POST['seatgroupid'])){

      if($_POST['action'] == "remove"){
              $query = "DELETE FROM bballtickets_seatgroups WHERE id='".$_POST['seatgroupid']."'";
      }elseif($_POST['seatgroupid']=="-1"){
              $query = "INSERT INTO bballtickets_seatgroups (`name`,`court`,`seats`,`priority`) VALUES ('".$_POST['name']."','".$_POST['court']."','".$_POST['seats']."','".$_POST['priority']."')";
      }else{
              $query = "UPDATE bballtickets_seatgroups SET `name`='".$_POST['name']."',`court`='".$_POST['court']."',`seats`= '".$_POST['seats']."',`priority`='".$_POST['priority']."' WHERE id = '".$_POST['seatgroupid']."'";
      }
      mysql_query($query);
}

$query = mysql_query("SELECT * FROM `bballtickets_courts`");

while($row = mysql_fetch_assoc($query)){

      $allocated = mysql_fetch_assoc(mysql_query("SELECT sum(seats) FROM bballtickets_seatgroups WHERE court='".$row['id']."'"));
      if($allocated["sum(seats)"] == ""){
            $allocseats = 0;
      }else{
            $allocseats = $allocated["sum(seats)"];
      }
      $courts .= '<a href="javascript:void(removeCourt(\''.$row["id"].'\'))"><img width="15px" src="img/remove.png"></a>
      <a href="javascript:void(editCourt(\''.$row["id"].'\'))">
      <img width="15px" src="img/edit.png"></a> '.$row["name"].' - '.$row["seats"].' pladser, '.$allocseats.' allokeret i plads-grupper<br>';
      $seatgroups = mysql_query("SELECT * FROM `bballtickets_seatgroups` WHERE `court`='".$row['id']."'"); 
      while($seatgroup = mysql_fetch_assoc($seatgroups)){
      
      $courts .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(removeSeatGroup(\''.$seatgroup["id"].'\'))"><img width="15px" src="img/remove.png"></a>
      <a href="javascript:void(editSeatGroup(\''.$seatgroup["id"].'\'))">
      <img width="15px" src="img/edit.png"></a> '.$seatgroup["name"].' - '.$seatgroup["seats"].' pladser<br>';
      
      }

}

echo "<h3>Baner:</h3> <br>".$courts."<br><br>";

?>

<form method="post" name="court">
  <input type="hidden" id="courtid" name="courtid" value="">
  <input type="hidden" id="action" name="action" value="">
  <input type="hidden" id="name" name="name" value="">
  <input type="hidden" id="seats" name="seats" value="">
  <input type="hidden" id="address" name="address" value="">
</form>

<form method="post" name="seatgroup">
  <input type="hidden" id="seatgroupid" name="seatgroupid" value="">
  <input type="hidden" id="action" name="action" value="">
  <input type="hidden" id="name" name="name" value="">
  <input type="hidden" id="seats" name="seats" value="">
  <input type="hidden" id="court" name="court" value="">
  <input type="hidden" id="priority" name="priority" value="">
</form>

<?php

echo '<a href="javascript:void(0)" onclick="editCourt(-1);"><img width="25px" src="img/add.png"></a> <font size="3">Tilføj Bane</font><br>';

echo '<a href="javascript:void(0)" onclick="editSeatGroup(-1);"><img width="25px" src="img/add.png"></a> <font size="3">Tilføj Plads-gruppe</font>';

getThemeBottom();

?>
