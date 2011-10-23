<?php

require "connect.php";

if(isset($_GET["addurl"])){
    $team=$_GET["team"];
    $url=$_GET["url"];
    if(mysql_num_rows(mysql_query("SELECT * FROM calendars WHERE address = '$url'"))){
    echo "Team already exists";
    }
    else{
    if($team!=""&&$url!=""){
        mysql_query("INSERT into calendars (`address`, `team`) VALUES ('$url', '$team')");
    }
    }
}

echo '<form type="get">
URL: <input type="text" name="url">Holdnavn: <input type="text" name="team"><input name="addurl" type="submit">
</form>';

$query = mysql_query("SELECT * FROM `calendars`");

// Filling the $todos array with new ToDo objects:

while($row = mysql_fetch_assoc($query)){
    echo $row['team'];
    echo ": ";
    echo $row['address'];
    echo "<br>";
}


?>        