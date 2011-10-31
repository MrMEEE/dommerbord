<?php

require("connect.php");
require("config.php");

$error=0;

if( (isset($_GET["adduser"])) || (isset($_GET["changeuser"])) ){
   $adduser = $_GET["adduser"];
   $changeuser = $_GET["changeuser"];
   
   if ( (isset($_POST["passwd1"])) && (isset($_POST["passwd2"]))  ){
      if ( $_POST["passwd1"] == $_POST["passwd2"]  ){
          $passwd = crypt($_POST["passwd1"]);
          if ( $_GET["isadmin"] ==  1){
              $admin = 1;
          }else{
              $admin = 0;
          }
          if(isset($_GET["adduser"])){
            if(mysql_num_rows(mysql_query("SELECT * FROM users WHERE `name` = '$adduser'"))) {
                $error="Brugeren eksistere allerede!";
              }else{
                mysql_query("INSERT INTO `users` (`name`,`password`,`admin`) VALUES ('$adduser','$passwd','$isadmin')");
              }
          }else{
            mysql_query("UPDATE `users` SET `password` = '$passwd', SET `admin` = '$admin' WHERE `id` = '$changeuser'");
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
    if($deluser != 1){
      $error = "Adminbrugeren kan ikke slettes!!";
    }else{
      mysql_query("DELETE FROM `users` WHERE `id` = '$deluser'"); 
    }
  }else{
    $error = "Brugeren eksistere ikke!!";
  }
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript">

function ConfirmChoice(userid){
 
 answer = confirm("Er du sikker på at du vil slette denne bruger??")
 
 if (answer !=0)
 {
 
 var klubadresse = "<?php echo $klubadresse;?>"
 var klubpath = "<?php echo $klubpath;?>"

 location = "http://" + klubadresse + "/" + klubpath + "/admin/users.php?deluser=" + userid;
  
 }
   
}
</script>
   
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $klubnavn; ?> Dommerbordsplan</title>

<!-- Including the jQuery UI Human Theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/humanity/jquery-ui.css" type="text/css" media="all" />

<!-- Our own stylesheet -->
<link rel="stylesheet" type="text/css" href="styles.css" />

</head>

<body>

<h1><?php echo $klubnavn; ?> Dommerplan</h1>

<div id="main">

<?php require("menu.php"); ?>

