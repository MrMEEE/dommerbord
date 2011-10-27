<?php

require "connect.php";
require "config.php";







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

<h1>Konfiguration</h1>

<div id="main">

<?php require("menu.php"); ?>

<?php
//mysql_query("INSERT INTO `bms_dommertest`.`config` (`klubnavn`, `klubpath`, `klubid`, `klubadresse`, `debug`) VALUES ('BMS', '', '', '', '0')");


?>