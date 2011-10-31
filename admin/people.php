<?php

require "connect.php";
require "config.php";

if(isset($_GET["addperson"])){
//    $team=$_GET["team"];
    $name=$_GET["name"];
    if($name!=""){
        mysql_query("INSERT into teams (`name`) VALUES ('$name')");
    }
}
if(isset($_GET["removeperson"])){
    if(isset($_GET["id"])){
        $id=$_GET["id"];
        mysql_query("DELETE FROM `teams` WHERE `id` = $id");
    }
}

$teamlist="";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript">

function ConfirmChoice(personid)

{

answer = confirm("Er du sikker på at du vil slette denne Person/dette Hold")

if (answer !=0)
{

var klubadresse = "<?php echo $klubadresse;?>"
var klubpath = "<?php echo $klubpath;?>"

location = "http://" + klubadresse + "/" + klubpath + "/admin/people.php?removeperson=1&id=" + personid;

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

<?php


echo '<br><br><form type="get">
        Name: <input type="text" name="name"><input name="addperson" type="submit">
</form><br><br>
Hold/Personer:<br><br>';

$query = mysql_query("SELECT * FROM `teams` ORDER BY `name` ASC");

// Filling the $todos array with new ToDo objects:

while($row = mysql_fetch_assoc($query)){
    if($row['name']!="-"){
    echo $row['name'];
    echo ' <a href="javascript:void(ConfirmChoice('.$row['id'].'))">Fjern</a>';
    echo "<br>";
    }
}

?>        

</div>

</body>
</html>
