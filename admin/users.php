<?php

require("connect.php");
require("config.php");
require("checkConfig.php");
require("checkLogin.php");
require("checkAdmin.php");
require("theme.php");

$error="";

if (checkAdmin($_SESSION['username'])){ 

if( (isset($_POST["adduser"])) || (isset($_POST["changepasswd"])) ){
   $adduser = $_POST["adduser"];
   $changepasswd = $_POST["changepasswd"];
   
   if ( (isset($_POST["passwd1"])) && ($_POST["passwd1"] != "")  ){
      if ($_POST["passwd1"] == $_POST["passwd2"]) {
          $passwd = $_POST["passwd1"];
          if ( $_POST["isadmin"] ==  1){
              $admin = 1;
          }else{
              $admin = 0;
          }
          if(isset($_POST["adduser"])){
            if(mysql_num_rows(mysql_query("SELECT * FROM users WHERE `name` = '$adduser'"))) {
                $error="Brugeren eksistere allerede!";
              }else{
                mysql_query("INSERT INTO `users` (`name`,`password`,`admin`) VALUES ('$adduser',md5('$passwd'),'$isadmin')");
              }
          }else{
            mysql_query("UPDATE `users` SET `password` = md5('$passwd') WHERE `id` = '$changepasswd'");
            $error = "Kode Ændret!";
          }
      }else{
        $error="De to adgangskodefelter er ikke ens!!";
      }
   }else{
     $error="Indtast venligst adgangskoden i begge felter!!";
   }
}

if(isset($_GET["deluser"])){
  $deluser = $_GET["deluser"];
  if(mysql_num_rows(mysql_query("SELECT * FROM users WHERE `id` = '$deluser'"))){
    if($deluser == 1){
      $error = "Adminbrugeren kan ikke slettes!!";
    }else{
      mysql_query("DELETE FROM `users` WHERE `id` = '$deluser'"); 
    }
  }else{
    $error = "Brugeren eksistere ikke!!";
  }
}

if(isset($_GET["changeadmin"])){
  $changeadmin = $_GET["changeadmin"];
  if(mysql_num_rows(mysql_query("SELECT * FROM users WHERE `id` = '$changeadmin'"))){
    if($changeadmin == 1){
      $error = "Adminbrugerens rettigheder kan ikke ændres!!";
    }else{
      $oldadmin = mysql_fetch_assoc(mysql_query("SELECT admin FROM users WHERE id = '$changeadmin'"));
      $oldadmin = $oldadmin['admin'];
      if($oldadmin == 0){
        $admin = 1;
      }else{
        $admin = 0;
      }
    mysql_query("UPDATE `users` SET `admin` = '$admin' WHERE `id` = '$changeadmin'");
    }
  }
}
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

