<?php

require "connect.php";
require "todo.class.php";

// Select all the todos, ordered by position:
$query = mysql_query("SELECT * FROM `games` WHERE CURDATE() <= `date` ORDER BY `date`,`time` ASC ");

$todos = array();

// Filling the $todos array with new ToDo objects:

while($row = mysql_fetch_assoc($query)){
	$todos[] = new ToDo($row);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BMS Dommerbordsplan</title>

<!-- Including the jQuery UI Human Theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/humanity/jquery-ui.css" type="text/css" media="all" />

<!-- Our own stylesheet -->
<link rel="stylesheet" type="text/css" href="styles.css" />

</head>

<body>

<h1>BMS Dommerplan</h1>

<div id="main">
<a href=http://www.bmsbasket.dk/dommer/sync.php>Opdater Kampprogram</a> | <a href=http://www.bmsbasket.dk/dommer/admin/people.php>Tilføj/Vis Hold/Personer</a>
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

<a id="addButton" class="green-button" href="#">Tilføj en Kamp</a>

</div>

<!-- This div is used as the base for the confirmation jQuery UI POPUP. Hidden by CSS. -->
<div id="dialog-confirm" title="Delete TODO Item?">Er du sikker på at du vil slette den kamp??</div>

<a href="./viewall.php">Vis alle hjemmebanekampe</a>
<!-- Including our scripts -->

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="script.js"></script>

</body>
</html>
