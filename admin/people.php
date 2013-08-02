<?php

require("connect.php");
require("config.php");
require("checkConfig.php");
require("checkLogin.php");
require("checkAdmin.php");
require("theme.php");

if (checkAdmin($_SESSION['username'])){

if(isset($_POST["addperson"])){
    $name=$_POST["name"];
    if($name!=""){
        mysql_query("INSERT into teams (`name`) VALUES ('$name')");
    }
}
if(isset($_POST["removeperson"])){
    if(isset($_POST["id"])){
        $id=$_POST["id"];
        mysql_query("DELETE FROM `teams` WHERE `id` = $id");
    }
}

if(isset($_POST["changeperson"])){
    if(isset($_POST["newname"])){
        $id=$_POST["changeperson"];
        $name = $_POST["newname"];
        mysql_query("UPDATE `teams` SET `name` = \"$name\" WHERE `id` = $id");
    }

}

if(isset($_GET["setteam"])){
    if(isset($_GET["id"])){
        $id=$_GET["id"];
        $team = $_GET["setteam"];
        mysql_query("UPDATE `teams` SET `teamid` = $team WHERE `id` = $id");
    }
}

if(isset($_POST["changetype"])){

    mysql_query("UPDATE `teams` SET `person`='".$_POST['value']."' WHERE `id`='".$_POST['id']."'");

}


}
$teamlist="";

getThemeHeader();

?>


function ConfirmChoice(personid)

{

answer = confirm("Er du sikker på at du vil slette denne Person/dette Hold")

if (answer !=0)
{

document.removeperson.removeperson.value = 1;
document.removeperson.id.value = personid;

document.removeperson.submit();

}

}

function openWindowName(userid,name){
  
    var path = "changePerson.php?id=" + userid + "&name='" + name +"'";
    window.open(path,"mywindow","menubar=1,resizable=1,width=350,height=250");
}

function openWindowTeams(userid,name){
  
    var path = "selectTeams.php?id=" + userid + "&name='" + name +"'";
    window.open(path,"mywindow","menubar=1,resizable=1,scrollbars,width=700,height=500");
}

function changeType(userid,set){

    document.change.id.value = userid;
    document.change.value.value = set;
    document.change.submit();

}


<?php 

getThemeTitle("Dommerplan");

require("menu.php"); 

echo '<br><br>';

if (checkAdmin($_SESSION['username'])){

echo '<form method="post">
Name: <input type="text" name="name"><input name="addperson" type="submit" value="Tilføj">
</form><br><br>';
}

echo '<form method="post" name="changeperson">
<input type="hidden" name="changeperson">
<input type="hidden" name="newname">
</form>';

echo '<form method="post" name="removeperson">
<input type="hidden" name="removeperson">
<input type="hidden" name="id">
</form>';

echo '<form method="post" name="change">
        <input type="hidden" name="id">
        <input type="hidden" name="changetype" value="1">
        <input type="hidden" name="value">
    </form>';


echo 'Hold/Personer:<br><br>';

$query = mysql_query("SELECT * FROM `teams` ORDER BY `name` ASC");

while($row = mysql_fetch_assoc($query)){
    if($row['name']!="-"){
    echo $row['name'];
    if (checkAdmin($_SESSION['username'])){
        echo ' - <a href="javascript:changeType('.$row['id'].'';
        if($row['person']){
            echo ',0)">Person</a>';
        }else{
            echo ',1)">Hold</a>';
        }
        echo ' - <a href="javascript:openWindowTeams('.$row['id'].',\''.$row['name'].'\')">Skift Tilknyttede Hold</a>';
        echo ' - <a href="javascript:openWindowName('.$row['id'].',\''.$row['name'].'\')">Ændre Navn</a>';
        echo ' - <a href="javascript:void(ConfirmChoice('.$row['id'].'))">Fjern</a>';
    }
        echo "<br>";
    }
}

getThemeBottom();

?>
