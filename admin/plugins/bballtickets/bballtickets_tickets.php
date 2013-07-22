<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");
require("bballtickets_functions.php");

getThemeHeader();

?>

function removeTicket(ticketid){
 
 answer = confirm("Er du sikker på at du vil slette denne billet/kort??")
 
 if (answer !=0)
 {
   document.ticket.ticketid.value=ticketid;
   document.ticket.action.value="remove";
   document.ticket.submit();
 }
 
}

function printTicket(ticketid){

 var path = "bballtickets_tickets_print.php?ticketid=" + ticketid;
 mywindow = window.open(path,"mywindow","menubar=1,resizable=1,width=500,height=480");

}

function editTicket(ticketid){
 
   var path = "bballtickets_tickets_info.php?ticketid=" + ticketid;
   mywindow = window.open(path,"mywindow","menubar=1,resizable=1,width=500,height=480");
   
}

function viewBarcode(barcodeid){

   var path = "bballtickets_tickets_barcode.php?id=" + barcodeid;
   mywindow = window.open(path,"mywindow","menubar=1,resizable=1,width=500,height=150");

}

function goBatch(){
   
   document.ticketlist.submit();

}

<?php

getThemeTitle("Billet/Kort");

if($_POST['action'] == "barcodelist"){

foreach($_POST['checkbox'] as $checkbox){
    echo '<img src="barcodes/'.$checkbox.'.jpg"><br>';
    echo $checkbox . '<br>';
}

}else{

require("../../menu.php");

require("bballtickets_check_database.php");

$config = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_config` WHERE `id`=1"));

if(isset($_POST['ticketid'])){
      if($_POST['action'] == "remove"){
             $query = "DELETE FROM bballtickets_tickets WHERE id='".$_POST['ticketid']."'";
      }elseif($_POST['ticketid']=="-1"){
             $query = "INSERT INTO bballtickets_tickets (`name`,`type`,`suspended`) VALUES ('".$_POST['name']."','".$_POST['type']."','0')";
      }else{
             $query = "UPDATE bballtickets_tickets SET `name`='".$_POST['name']."',`type`='".$_POST['type']."',`suspended`= '".$_POST['suspended']."' WHERE id = '".$_POST['ticketid']."'";
      }
      mysql_query($query);
      
}

$query = mysql_query("SELECT * FROM `bballtickets_tickets`");

echo '<form name=ticketlist method="post" action="bballtickets_tickets.php" target="_blank">';

while($row = mysql_fetch_assoc($query)){
      $type = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_tickettypes` WHERE id='".$row['type']."'"));
      $barcodeid = str_pad((int) $row['type'],"4","0",STR_PAD_LEFT).str_pad((int) $row['id'],"10","0",STR_PAD_LEFT);
      $tickets .= '<input type="checkbox" value="'.$barcodeid.'" name="checkbox[]">
      <a href="javascript:void(removeTicket(\''.$row["id"].'\'))"><img width="15px" src="img/remove.png" title="Slet Kort/Billet"></a>
      <a href="javascript:void(printTicket(\''.$row["id"].'\'))" title="Print Kort/Billet">
      <img width="15px" src="img/card.png"></a>
      <a href="javascript:void(viewBarcode(\''.$barcodeid.'\'))" title="Vis Stregkode">
      <img width="15px" src="img/barcode.jpg"></a>
      <a href="javascript:void(editTicket(\''.$row["id"].'\'))"> <img width="15px" src="img/edit.png" title="Rediger Kort/Billet"></a> '.$barcodeid." - ".$row["name"].' - '.$type["name"].'<br>';

}


echo "<h3>Billetter/Kort:</h3> <br>".$tickets."<br>";

echo '<select name="action" onChange="goBatch()">
       <option value="-" selected>Vælg operation</option>
       <option value="barcodelist">Vis stregkoder</option>
      </select>';

echo '</form><br><br>';

?>

<form method="post" name="ticket">
  <input type="hidden" id="ticketid" name="ticketid" value="">
  <input type="hidden" id="action" name="action" value="">
  <input type="hidden" id="name" name="name" value="">
  <input type="hidden" id="type" name="type" value="">
  <input type="hidden" id="suspended" name="suspended" value="">
</form>


<?php

echo '<a href="javascript:void(0)" onclick="editTicket(-1);"><img width="25px" src="img/add.png"></a> <font size="3">Tilføj Billet/Kort</font>';

getThemeBottom();

}

?>
