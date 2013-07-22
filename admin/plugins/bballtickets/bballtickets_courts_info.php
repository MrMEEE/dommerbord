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

    if ((document.court.name.value=="") || (document.court.name.value=="Udfyld Navn")){
         document.court.name.value="Udfyld Navn";
    }else{
         opener.document.court.name.value=document.court.name.value;
         opener.document.court.seats.value=document.court.seats.value;
         opener.document.court.address.value=document.court.address.value;
         opener.document.court.courtid.value=document.court.courtid.value;
         opener.document.court.submit();
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

<?php

getThemeTitle("Bane");


if($_GET['courtid'] == "-1"){
     $operation = "Opret";
}else{
     $operation = "Opdater";
     $court = mysql_fetch_assoc(mysql_query("SELECT * FROM bballtickets_courts WHERE id='".$_GET['courtid']."'"));
}

?>


<table>
<tr>
<form method="post" id="court" name="court" action="javascript:loadinparent()">
<td style='line-height:2;' VALIGN="top">
Navn: <br>
Antal Pladser: <br>
Beskrivelse: <br>
</td>
<td VALIGN="top" style='line-height:2;' align="right">
<input id="name" type="text" name="name" value="<?php echo $court['name'] ?>"><br>
<input id="seats" onkeypress="return isNumberKey(event)" type="text" name="seats" value="<?php echo $court['seats'] ?>"><br>
<textarea rows="8" cols="40" id="address" name="address"><?php echo $court['address'] ?></textarea><br>
<input type="hidden" id="courtid" name="courtid" value="<?php echo $_GET['courtid'] ?>">
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
