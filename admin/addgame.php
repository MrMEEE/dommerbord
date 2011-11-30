<?php

if(!file_exists("connect.php")){
 ob_start();
 header( "Location: setup.php" );
 ob_flush();
}


require "config.php";
require "connect.php";
 
$config=mysql_fetch_assoc(mysql_query("SELECT * FROM config WHERE id = '1'"));
if (($config['klubadresse']=="") || ($config['klubpath']=="") || ($config['klubnavn']=="")){
    ob_start();
    header( "Location: configuration.php" );
    ob_flush();
}

require "checkLogin.php";
require "todo.class.php";

$message = "";
$color = "white";

$date = "00/00-0000";
$time = "00:00:00";
$description = "Beskrivelse af Kampen";

if (isset($_POST['date'])){
    if(($_POST['date'] != "00/00-0000") && ($_POST['date'] != "")){
        if(($_POST['time'] != "00:00:00") && ($_POST['time'] != "")){
            if(($_POST['description'] != "Beskrivelse af Kampen") && ($_POST['description'] != "")){
                $date = substr($_POST['date'],6,4);
                $date .= "-";
                $date .= substr($fulldate,3,2);
                $date .= "-";
                $date .= substr($fulldate,0,2);
                mysql_query("INSERT INTO games SET text='".$_POST['description']."',time='".$_POST['time']."',position = 9999999,status = 1, date=".$date."");
                $message = "Kamp Tilføjet";
                $color = "green";
            }else{
                $message = "Angiv venligst en beskrivelse!";
                $color = "red";
            }
        }else{
            $message = "Angiv venligst tidspunktet!";
            $color = "red";
        }
    }else{
        $message = "Angiv venligst datoen!";
        $color = "red";
    }
    $date = $_POST['date'];
    $time = $_POST['time'];
    $description = $_POST['description'];
}

?>
  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<?php require("gamemenu.php"); ?>
<?php echo '<font color="'.$color.'">'.$message.'</font>'; ?>
<form method="post">
<table>
<tr><td>Dato:</td><td><input type="text" name="date" value="<?php echo $date ?>"></td></tr>
<tr><td>Tidspunkt:</td><td><input type="text" name="time" value="<?php echo $time ?>"></td></tr>
<tr><td>Beskrivelse:</td><td><input type="text" name="description" value="<?php echo $description ?>"></td></tr>
<tr><td rowspan=2><input name="addgame" type="submit" value="Tilføj"></td></tr>
</table>
</form><br><br>