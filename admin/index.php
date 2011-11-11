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
        $query = mysql_query("SELECT * FROM `games` WHERE CURDATE() <= `date` ORDER BY `date`,`time` ASC ");
    break;
    case "unassigned":
        $query = mysql_query("SELECT * FROM `games` WHERE CURDATE() <= `date` AND `status` = 1 ORDER BY `date`,`time` ASC ");
    break;
    case "moved":
        $query = mysql_query("SELECT * FROM `games` WHERE CURDATE() <= `date` AND `status` = 2 ORDER BY `date`,`time` ASC ");
    break;
    case "cancelled":
        $query = mysql_query("SELECT * FROM `games` WHERE CURDATE() <= `date` AND `status` = 3 ORDER BY `date`,`time` ASC ");
    break;
    case "all":
        $query = mysql_query("SELECT * FROM `games` ORDER BY `date`,`time` ASC ");
    break;

}

    $query2 = mysql_query("SELECT * FROM `games` WHERE `date` = '0000-00-00' ORDER BY `date`,`time` ASC ");

$todos = array();

// Filling the $todos array with new ToDo objects:

while($row = mysql_fetch_assoc($query2)){
        $todos[] = new ToDo($row);
}

while($row = mysql_fetch_assoc($query)){
	$todos[] = new ToDo($row);
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
<script type="text/javascript" src="script.js"></script>

</body>
</html>
