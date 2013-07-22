<?php

if(!file_exists("connect.php")){
 ob_start();
 header( "Location: setup.php" );        
 ob_flush();
}

require("config.php"); 
require("connect.php");

$config=mysql_fetch_assoc(mysql_query("SELECT * FROM config WHERE id = '1'"));

if (($config['klubadresse']=="") || ($config['klubpath']=="") || ($config['klubnavn']=="")){
    ob_start();
    header( "Location: configuration.php" );
    ob_flush();
}

require("checkAdmin.php");
require("checkConfig.php");
require("checkLogin.php");
require("todo.class.php");
require("theme.php");

if(!mysql_num_rows(mysql_query("SELECT * FROM `teams` WHERE `name` = '-'"))){
    mysql_query("INSERT INTO `teams` (`id`, `name`) VALUES ('9999','-')");
}

$viewgames="default";

if(isset($_GET["view"])){
    $viewgames=$_GET["view"];

}


// Select all the todos, ordered by positions
switch ($viewgames) {
    case "default":
        $query = mysql_query("SELECT * FROM `games` WHERE CURDATE() <= `date` AND `homegame`= 1 ORDER BY `date`,`time` ASC ");
        $query2 = mysql_query("SELECT * FROM `games` WHERE `date` = '0000-00-00' AND `homegame`= 1 ORDER BY `date`,`time` ASC ");
    break;
    case "unassigned":
        $query = mysql_query("SELECT * FROM `games` WHERE CURDATE() <= `date` AND `status` = 1 AND `homegame`= 1 ORDER BY `date`,`time` ASC ");
        $query2 = mysql_query("SELECT * FROM `games` WHERE `date` = '0000-00-00' AND `status` = 1 AND `homegame`= 1 ORDER BY `date`,`time` ASC ");
    break;
    case "moved":
        $query = mysql_query("SELECT * FROM `games` WHERE CURDATE() <= `date` AND `status` = 2 AND `homegame`= 1 ORDER BY `date`,`time` ASC ");
        $query2 = mysql_query("SELECT * FROM `games` WHERE `date` = '0000-00-00' AND `status` = 2 AND `homegame`= 1 ORDER BY `date`,`time` ASC ");
    break;
    case "cancelled":
        $query = mysql_query("SELECT * FROM `games` WHERE CURDATE() <= `date` AND `status` = 3 AND `homegame`= 1 ORDER BY `date`,`time` ASC ");
        $query2 = mysql_query("SELECT * FROM `games` WHERE `date` = '0000-00-00' AND `status` = 3 AND `homegame`= 1 ORDER BY `date`,`time` ASC ");
    break;
    case "all":
        $query = mysql_query("SELECT * FROM `games` ORDER BY `date`,`time` ASC ");
        $query2 = mysql_query("SELECT * FROM `games` WHERE `date` = '0000-00-00' ORDER BY `date`,`time` ASC ");
    break;

}


$todos = array();

// Filling the $todos array with new ToDo objects:

if(mysql_num_rows($query2)){
while($row = mysql_fetch_assoc($query2)){
        $todos[] = new ToDo($row);
}
}
if(mysql_num_rows($query)){
while($row = mysql_fetch_assoc($query)){
	$todos[] = new ToDo($row);
}
}

getThemeHeader();
getThemeTitle("Dommerplan");

require("menu.php"); 

?>

<center><table>
<tr>
<td width=300>
<li style="color: #80FF99;list-style: square;font-size: 22px;"><font color="000000" size="2px">Kamp med påsatte bord/dommere</font></li>
</td>
<td width=300>
<li style="color: #FFD633;list-style: square;font-size: 22px;"><font color="000000" size="2px">Kamp der mangler bord/dommere</font></li>
</td>
</tr>
<tr>
<td>
<li style="color: #FF9980;list-style: square;font-size: 22px;"><font color="000000" size="2px">Ændret/Flyttet kamp</font></li>
</td>
<td>
<li style="color: #1B9DE3;list-style: square;font-size: 22px;"><font color="000000" size="2px">Aflyst kamp</font></li>
</td>   
</tr>
<tr>
<td>
<li style="color: #ff6501;list-style: square;font-size: 22px;"><font color="000000" size="2px">Udsat kamp</font></li>
</td>
<td>

</td>   
</tr>
</table>
</center>

	<ul class="todoList">
		
        <?php
		
		// Looping and outputting the $todos array. The __toString() method
		// is used internally to convert the objects to strings:
		//ToDo::changeTeam(2,1,1);
		foreach($todos as $item){
			echo $item;
		}
		?>

    </ul>

</div>

<!-- This div is used as the base for the confirmation jQuery UI POPUP. Hidden by CSS. -->
<div id="dialog-confirm" title="Delete TODO Item?">Er du sikker på at du vil slette den kamp??</div>

<!-- Including our scripts -->

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="js/script.js"></script>

<?php
getThemeBottom();

?>