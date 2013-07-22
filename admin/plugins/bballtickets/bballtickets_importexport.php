<?php

require("../../connect.php");
require("../../config.php");
require("bballtickets_functions.php");

if(isset($_POST['clientid'])){
  
  if(isset($_POST['checkindata'])){
  
      $clientquery = mysql_query("SELECT * FROM `bballtickets_clients` WHERE `clientid`='".$_POST['clientid']."'");
      $client = mysql_fetch_assoc($clientquery);
      if(($client['clientpass'] == $_POST['clientpass']) && ($client['approved'] == 1)){
          $query = "INSERT INTO `bballtickets_checkins` (`game`,`code`,`status`,`seatgroup`) VALUES ".$_POST['checkindata'];
      
          if(mysql_query($query)){
               echo 0;
          }else{
               echo 2;
          }
          
      }else{
      
          echo "1";
           
      }
  
  }else{
  
  /*Return Codes
  
  1 - Client not approved
  2 - Client sent wrong password
  
  */
  $clientquery = mysql_query("SELECT * FROM `bballtickets_clients` WHERE `clientid`='".$_POST['clientid']."'");
  if(!mysql_num_rows($clientquery)){
      if($_POST['clientid'] != ""){
          mysql_query("INSERT INTO `bballtickets_clients` (`clientid`,`clientpass`,`clientname`,`approved`) VALUES ('".$_POST['clientid']."','".$_POST['clientpass']."','".$_POST['clientname']."','0')");
      }
      $clientquery = mysql_query("SELECT * FROM `bballtickets_clients` WHERE `clientid`='".$_POST['clientid']."'");
  }
  
  $client = mysql_fetch_assoc($clientquery);
  
  if($client['clientpass'] == $_POST['clientpass']){
        if($client['clientname'] != $_POST['clientname']){
              mysql_query("UPDATE `bballtickets_clients` SET `clientname`='".$_POST['clientname']."' WHERE `clientid`='".$_POST['clientid']."'");
        }
        if($client['approved'] == 1){
              genExport();
        }else{
              echo "1 - Not Approved";
        }
            
  }else{
        echo "2 - Wrong Password";      
  }
 }
  
}else{

if(isset($_POST['action'])){
     switch ($_POST['action']) {
     case "unapprove":
          mysql_query("UPDATE `bballtickets_clients` SET `approved`=0 WHERE `clientid`='".$_POST['client']."'");
          break;
     case "approve":
          mysql_query("UPDATE `bballtickets_clients` SET `approved`=1 WHERE `clientid`='".$_POST['client']."'");
          break;
     case "remove":
          mysql_query("DELETE FROM `bballtickets_clients` WHERE `clientid`='".$_POST['client']."'");
          break;
     }
}

require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");

getThemeHeader();

?>
function approve(clientid){

    document.clients.client.value=clientid;
    document.clients.action.value="approve";
    document.clients.submit();
}
function unapprove(clientid){

    document.clients.client.value=clientid;
    document.clients.action.value="unapprove";
    document.clients.submit();
}
function remove(clientid){

 answer = confirm("Er du sikker på at du vil fjerne denne klient??")

 if(answer !=0){
    document.clients.client.value=clientid;
    document.clients.action.value="remove";
    document.clients.submit();
 }

}

<?php
getThemeTitle("Import/Eksport");

require("../../menu.php");

require("bballtickets_check_database.php");

$clientquery = mysql_query("SELECT * FROM `bballtickets_clients`");

while($client = mysql_fetch_assoc($clientquery)){

      if($client['approved']){
            $approved .= '<a href="javascript:void(0)" onclick="unapprove(\''.$client['clientid'].'\');"><img width="15px" src="img/remove.png"></a> '.$client['clientname']."<br>";
      }else{
            $notapproved .= '<a href="javascript:void(0)" onclick="approve(\''.$client['clientid'].'\');"><img width="15px" src="img/add.png"></a><a href="javascript:void(0)" onclick="remove(\''.$client['clientid'].'\');"><img width="15px" src="img/remove.png"></a> '.$client['clientname']."<br>";
      }

}

echo '<h3>Godkendte Klienter</h3><br>'.$approved.'<br>';

echo '<h3>Ikke Godkendte Klienter</h3><br>'.$notapproved.'<br>';

echo '<br><br><a href="bballtickets_importexport_genexport.php">Eksport Data Manuelt</a>';

echo '<form method="post" name="clients">
      <input type="hidden" name="action">
      <input type="hidden" name="client">
      </form>';

getThemeBottom();

}
