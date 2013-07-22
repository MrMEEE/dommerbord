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
    loopSelected('accessarr','access');
    loopSelected('grouparr','group');
    if ((document.type.name.value=="") || (document.type.name.value=="Udfyld Navn")){
         document.type.name.value="Udfyld Navn";
    }else if(document.type.grouparr.value==""){ 
             document.getElementById('grouparr').focus();
    }else{
         if(document.type.unlimited.checked == 1){
              opener.document.type.seats.value="unlimited";
         }else{
              opener.document.type.seats.value=document.type.seats.value;
         }
         opener.document.type.name.value=document.type.name.value;
         opener.document.type.group.value=document.type.group.value;
         opener.document.type.typeid.value=document.type.typeid.value;
         opener.document.type.expires.value=document.type.expires.value.substr(6,4)+"-"+document.type.expires.value.substr(3,2)+"-"+document.type.expires.value.substr(0,2);
         opener.document.type.access.value=document.type.access.value;
         opener.document.type.action.value=document.type.action.value;
         opener.document.type.submit();
         window.close();
    }

}

function formfocus() {
   document.getElementById('name').focus();
}

function isNumberKey(evt){
     var charCode = (evt.which) ? evt.which : event.keyCode
     if (charCode > 31 && (charCode < 48 || charCode > 57))
          return false;
     return true;
}

window.onload = formfocus;

$(function() {
     $( "#expires" ).datepicker();
     $( "#expires" ).datepicker( "option", "dateFormat", "dd/mm/yy" );
     $( "#expires" ).datepicker( "hide" )
});

function isLimited(){
     if(document.type.unlimited.checked == 1){
          document.type.seats.disabled = true;
     }
     if(document.type.unlimited.checked == 0){
          
          $("#seats").removeAttr('disabled');
     }
}

function loopSelected(array,tofield)
{
  var txtSelectedValuesObj = document.getElementById(tofield);
  var selectedArray = new Array();
  var selObj = document.getElementById(array);
  var i;
  var count = 0;
  for (i=0; i<selObj.options.length; i++) {
    if (selObj.options[i].selected) {
      selectedArray[count] = selObj.options[i].value;
      count++;
    }
  }
  txtSelectedValuesObj.value = selectedArray;
}



<?php

getThemeTitle("Billettype");

echo '<script src="js/jquery-ui-1.8.23.custom.min.js"></script>';

if($_GET['typeid'] == "-1"){
     $operation = "Opret";
}else{
     $operation = "Opdater";
     $type = mysql_fetch_assoc(mysql_query("SELECT * FROM bballtickets_tickettypes WHERE id='".$_GET['typeid']."'"));
}


?>


<table>
<tr>
<form method="post" id="type" name="type" action="javascript:loadinparent()">
<td style='line-height:2;' VALIGN="top">
Navn: <br>
Antal Personer: <br>
Udløbsdato: <br>
Pladsgrupper: <br>
Adgang til: <br>
</td>
<td VALIGN="top" style='line-height:2;' align="right">
<input id="name" type="text" name="name" value="<?php echo $type['name'] ?>"><br>
Ubegrænset:<input id="unlimited" type="checkbox" name="unlimited" onchange="isLimited()"
<?php
if($type['seats'] == "unlimited"){
     echo " checked";
}
?>
>
<input id="seats" onkeypress="return isNumberKey(event)" type="text" name="seats" 
<?php
if($type['seats'] == "unlimited"){
     echo " disabled>";
}else{
     echo 'value="'.$type['seats'].'">';
}
?>
<br>
<input id="expires" name="expires" type="text"><br>
<select width="400" style="width: 400px" id="grouparr" name="grouparr" size="10" multiple>

<?php
$selected = explode(",",$type['group']);
$query = "SELECT * FROM `bballtickets_seatgroups` ORDER BY court";
$res   = mysql_query($query);
while($row = mysql_fetch_assoc($res)){
     echo "<option value='".$row['id']."' ";
     if(in_array($row['id'],$selected)){
          echo "selected";
     }
     $court=mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_courts` WHERE id='".$row['court']."'"));
     echo ">".$row['name']." i ".$court['name']."</option>";
}

?>
</select><br>

<select width="400" style="width: 400px" id="accessarr" name="accessarr" size="10" multiple>
<option value='all'
<?php
$selected = explode(",",$type['access']);
if(in_array("all",$selected)){
     echo " selected";
}
?>
>-- Alle kampe --</option>
<option value='free'
<?php
if(in_array("free",$selected)){
     echo " selected";
}
?>
>-- Valgfrie kamp(e) --</option>
<option value='counter'
<?php
if(in_array("counter",$selected)){
     echo " selected";
}
?>
>-- Løssalg --</option>

<?php

$config = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_config` WHERE id='1'"));
$teams = explode(",",$config['hold']);

foreach($teams as $teamid){
     if($teamid != ""){
          $teaminfo = mysql_fetch_assoc(mysql_query("SELECT * FROM `calendars` WHERE id='".$teamid."'"));
          $games = mysql_query("SELECT * FROM `games` WHERE team='".$teamid."' AND homegame='1' ORDER BY date");
          while($game = mysql_fetch_assoc($games)){
               $opponent = explode(">",$game['text']);
               echo "<option value='".$game['id']."' ";
               if(in_array($game['id'],$selected)){
                    echo "selected";
               }
               echo ">".$game['date']." - ".$teaminfo['team']." ".$opponent[1]."</option>";
          }
     }
}

?>
</select><br>

<input type="hidden" id="typeid" name="typeid" value="<?php echo $_GET['typeid'] ?>">

<input type="hidden" id="action" name="action" value="<?php 

if($_GET['typeid'] == "-1"){
     echo 'create';
}else{
     echo 'update';
}

?>">

<input type="hidden" id="access" name="access">
<input type="hidden" id="group" name="group">
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
<input id="typeid" name="typeid" type="hidden">
</form>

<?php

getThemeBottom();

?>
