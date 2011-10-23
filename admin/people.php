<?php

require "connect.php";

if(isset($_GET["addperson"])){
//    $team=$_GET["team"];
    $name=$_GET["name"];
    if($name!=""){
        mysql_query("INSERT into teams (`name`) VALUES ('$name')");
    }
}

$teamlist="";

/*$result=mysql_query("select id, name from teams order by name asc");
                while(list($id, $name)=mysql_fetch_row($result)) {
                                $teamlist.= "<option value=\"".$id."\">".$name."</option>";
                }

Teamname<select name="team" id="referee1Select">
        <option value="0">Vælg et hold</option>   
        '.$teamlist.'
        </select>
*/
echo '<form type="get">
        Name: <input type="text" name="name"><input name="addperson" type="submit">
</form>';

$query = mysql_query("SELECT * FROM `teams` ORDER BY `name` ASC");

// Filling the $todos array with new ToDo objects:

while($row = mysql_fetch_assoc($query)){
    echo $row['name'];
    //echo ": ";
    //echo $row['playername'];
    echo "<br>";
}


?>        
