<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");


getThemeHeader();

?>

function loadinparent(id){

    if ((document.ticket.name.value=="") || (document.ticket.name.value=="Udfyld Navn/Beskrivelse")){
         document.ticket.name.value="Udfyld Navn/Beskrivelse";
    }else{
         opener.document.ticket.name.value=document.ticket.name.value;
         opener.document.ticket.type.value=document.ticket.type.value;
         if (document.ticket.suspended.checked==true)
              opener.document.ticket.suspended.value="1";
         else
              opener.document.ticket.suspended.value="0";
         opener.document.ticket.ticketid.value=document.ticket.ticketid.value;
         opener.document.ticket.submit();
         window.close();
    }

}

function formfocus() {
   document.getElementById('name').focus();
}

window.onload = formfocus;

<?php

getThemeTitle("Bane");


if($_GET['ticketid'] == "-1"){
     $operation = "Opret";
}else{
     $operation = "Opdater";
     $ticket = mysql_fetch_assoc(mysql_query("SELECT * FROM bballtickets_tickets WHERE id='".$_GET['ticketid']."'"));
}

?>


<table>
<tr>
<form method="post" id="ticket" name="ticket" action="javascript:loadinparent()">
<td style='line-height:2;' VALIGN="top">
Navn/Beskrivelse: <br>
Billet-/Korttype: <br>
Deaktiveret: <br>
</td>
<td VALIGN="top" style='line-height:2;' align="right">
<input id="name" type="text" name="name" value="<?php echo $ticket['name'] ?>"><br>
<select id="type" name="type">
<option value=''>-- Vælg Billettype --</option>
<?php
$query = "SELECT * FROM `bballtickets_tickettypes` ORDER BY `group`";
$res   = mysql_query($query);
while($row = mysql_fetch_assoc($res)){
     echo "<option value='".$row['id']."' ";
     if($row['id'] == $ticket['type']){
          echo "selected";
     }
     $group=mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_seatgroups` WHERE id='".$row['group']."'"));
     $court=mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_courts` WHERE id='".$group['court']."'"));
     echo ">".$row['name']." - ".$court['name']."</option>";
}
?>
</select><br>
<input type="checkbox" id="suspended" name="suspended" 
<?php

if($ticket['suspended'] == "1"){
     echo " checked";
}

?>
><br>
<input type="hidden" id="ticketid" name="ticketid" value="<?php echo $_GET['ticketid'] ?>">
<input name="update" type="submit" value="<?php echo $operation ?>">
</td>
</form>
</td>
<td width="10px">
</td>
<td VALIGN="top" align="right">

</td>
</tr>
</table>
<br>

<form method="post" name="info">
<input id="ticketid" name="ticketid" type="hidden">
</form>

<?php

getThemeBottom();

?>
