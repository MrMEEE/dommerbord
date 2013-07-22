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

    if ((document.stat.statnavn.value=="") || (document.stat.statnavn.value=="Udfyld statnavnet")){
         document.stat.statnavn.value="Udfyld statnavnet";
    }else{
         opener.document.stat.statnavn.value=document.stat.statnavn.value;
         opener.document.stat.operation.value=document.stat.operation.value;
         opener.document.stat.oldname.value=document.stat.oldname.value;
         opener.document.stat.submit();
         window.close();
    }

}

function formfocus() {
   document.getElementById('operation').focus();
}
window.onload = formfocus;

<?php

getThemeTitle("Stat: ".$_GET['statname']);


$do = "Opdater";

if(substr($_GET['statname'],0,2)=="£"){

     list($start,$operation,$statnavn)=split("£",$_GET['statname']);

}else{
     
     $statnavn=$_GET['statname'];

}


?>

<form method="post" id="stat" name="stat" action="javascript:loadinparent()">
Statnavn: <input id="statnavn" type="text" name="statnavn" value="<?php echo $statnavn ?>"><br>
Udregning (Valgfri): <input id="operation" type="text" name="operation" value="<?php echo $operation ?>"><br>
<input name="oldname" id="oldname" type="hidden" value="<?php echo $_GET['statname'] ?>"><br>
<input name="opdater" type="submit" value="<?php echo $do ?>">
</form>

<?php

getThemeBottom();

?>
