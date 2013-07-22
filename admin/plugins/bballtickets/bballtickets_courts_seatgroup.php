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

    if ((document.seatgroup.name.value=="") || (document.seatgroup.name.value=="Udfyld Navn")){
         document.seatgroup.name.value="Udfyld Navn";
    }else if(document.seatgroup.court.value==""){
         document.getElementById('court').focus();
    }else{
         opener.document.seatgroup.name.value=document.seatgroup.name.value;
         opener.document.seatgroup.seats.value=document.seatgroup.seats.value;
         opener.document.seatgroup.court.value=document.seatgroup.court.value;
         opener.document.seatgroup.priority.value=document.seatgroup.priority.value;
         opener.document.seatgroup.seatgroupid.value=document.seatgroup.seatgroupid.value;
         opener.document.seatgroup.submit();
         window.close();
    }

}

function formfocus() {
   document.getElementById('name').focus();
}

window.onload = formfocus;

$(document).ready(function()
{
  $("#court").change(function()
  {
    var courtid = $(this).val();
    if(courtid != '')  
     {
      $.ajax
      ({
         type: "POST",
         url: "bballtickets_courts_seatgroup_seats.php",
         data: "courtid="+ courtid,
         success: function(option)
         {
           $("#seats").html(option);
         }
      });
     }
     else
     {
       $("#seats").html("<option value=''>-- Ingen bane valgt --</option>");
     }
    return false;
  });
});

function isNumberKey(evt){
     var charCode = (evt.which) ? evt.which : event.keyCode
     if (charCode > 31 && (charCode < 48 || charCode > 57))
          return false;
     return true;
}

<?php

getThemeTitle("Bane");


if($_GET['seatgroupid'] == "-1"){
     $operation = "Opret";
     $court = "Unknown";
}else{
     $operation = "Opdater";
     $seatgroup = mysql_fetch_assoc(mysql_query("SELECT * FROM bballtickets_seatgroups WHERE id='".$_GET['seatgroupid']."'"));
     $court = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_courts` WHERE `id`=".$seatgroup['court'].""));
}


?>


<table>
<tr>
<form method="post" id="seatgroup" name="seatgroup" action="javascript:loadinparent()">
<td style='line-height:2;' VALIGN="top">
Gruppe Navn: <br>
Bane: <br>
Antal Pladser: <br>
Prioritet: <br>
</td>
<td VALIGN="top" style='line-height:2;' align="right">
<input id="name" type="text" name="name" value="<?php echo $seatgroup['name'] ?>"><br>
<?php

$query = "SELECT * FROM `bballtickets_courts`";
$res   = mysql_query($query)

?>
<select id="court" name="court" style="">

<?php

echo "<option value=''>-- Vælg Bane --</option>";
while($row = mysql_fetch_array($res)){
     echo "<option value='".$row['id']."' ";
     
     if($row['name'] == $court['name']){
           echo "selected";
           
     }
     echo ">".ucfirst($row['name'])."</option>";
}
?>
</select><br>
<select id="seats" name="seats">
<?php
  if($seatgroupid!="-1"){
       $allocated = mysql_fetch_assoc(mysql_query("SELECT sum(seats) FROM bballtickets_seatgroups WHERE court='".$court['id']."'"));
       $freeseats = $court['seats'] - $allocated['sum(seats)'];
       for ($i = 0; $i <= $freeseats; $i++){
            echo "<option value='".$i."' ";
            if($i == $seatgroup['seats']){
                   echo "selected";
            }
            echo ">".$i."</option>";
       }
       $court = "Unknown";
  }else{
       echo '<option value="">-- Ingen bane valgt --</option>';
  }
?>
</select><br>
<input id="priority" onkeypress="return isNumberKey(event)" type="text" name="priority" value="<?php echo $seatgroup['priority'] ?>"><br>
<input type="hidden" id="seatgroupid" name="seatgroupid" value="<?php echo $_GET['seatgroupid'] ?>">
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
<input id="courtid" name="courtid" type="hidden">
</form>

<?php

getThemeBottom();

?>
