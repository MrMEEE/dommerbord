<?php

require("connect.php");
require("config.php");
require("checkConfig.php");
require("checkLogin.php");
require("theme.php");
require_once("commonFunctions.php");

$error="";

if( (isset($_POST["adduser"])) || (isset($_POST["changepasswd"])) ){

  switch(userVerifyPassword($_POST["passwd1"],$_POST["passwd2"])){
      case "0":
            $error="De to adgangskodefelter er ikke ens!!";
      break;
      case "1":
            $error="Indtast venligst adgangskoden i begge felter!!";
      break;
      default:
            if(isset($_POST["adduser"])){
                  $error = userAdd($_POST["adduser"],$_POST["passwd1"],$_POST["isadmin"]);
            }else{
                  $error = userChangePassword($_POST["changepasswd"],$_POST["passwd1"]);
            }
      break;    
  }
}


if(isset($_GET["deluser"])){

    $error = userDelete($_GET["deluser"]);

}

if(isset($_GET["changeadmin"])){

    $error = userChangeAdmin($_GET["changeadmin"]);

}


getThemeHeader();

?>

function ConfirmChoice(userid){
 
 answer = confirm("Er du sikker på at du vil slette denne bruger??")
 
 if (answer !=0)
 {
 
 var klubadresse = "<?php echo $klubadresse;?>"
 var klubpath = "<?php echo $klubpath;?>"

 location = "http://" + klubadresse + "/" + klubpath + "/admin/users.php?deluser=" + userid;
  
 }
   
}

function openWindow(userid){
  
  var path = "changePasswd.php?id=" + userid; 
  window.open(path,"mywindow","menubar=1,resizable=1,width=350,height=250");

}

function openWindowTeams(userid,name,type){
  
  var path = "selectTeams.php?id=" + userid + "&name=" + name +"&user="+type;
  window.open(path,"mywindow","menubar=1,resizable=1,scrollbars,width=700,height=500");
}



<?php 

getThemeTitle("Brugere");

require("menu.php"); 

echo '<font color="red">'.$error.'</font>';

echo '<br><br><form method=post name="createuser">
<TABLE>
<TR><TD>Navn:</TD><TD><input type="text" name="adduser"></TD></TR>
<TR><TD>Kode:</TD><TD><input type="password" name="passwd1"></TD></TR>
<TR><TD>Gentag Kode:</TD><TD><input type="password" name="passwd2"></TD></TR>
<TR><TD rowspan=2><input name="addperson" type="submit" value="Tilføj Bruger"></TD></TR>
</TABLE>
</form><br><br>
<HR>
<br><br>
Brugere:<br><br>';

echo '<form method=post name="changepasswd">
<input type="hidden" name="passwd1">
<input type="hidden" name="passwd2">
<input type="hidden" name="changepasswd">
</form>';

$query = mysql_query("SELECT * FROM `users` ORDER BY `name` ASC");
 
while($row = mysql_fetch_assoc($query)){
  echo $row['name'];
  if($row['name']!="admin"){
    echo ' - <a href="http://'.$klubadresse.'/'.$klubpath.'/admin/users.php?changeadmin='.$row['id'].'">';
    if($row['admin'] == 0){
      echo 'Bruger</a>';
    }else{
      echo 'Admin</a>';
    }
  echo ' - <a href="javascript:void(ConfirmChoice('.$row['id'].'))">Fjern</a>';
  echo ' - <a href="javascript:openWindowTeams('.$row['id'].',\''.$row['name'].'\',1)">Hold</a>';
  echo ' - <a href="javascript:openWindowTeams('.$row['id'].',\''.$row['name'].'\',2)">Dommere</a>';
  }
  echo ' - <a href="javascript:openWindow('.$row['id'].')">Skift Adgangskode</a>';
  echo "<br>";
  
}
 

getThemeBottom();

?>                            

