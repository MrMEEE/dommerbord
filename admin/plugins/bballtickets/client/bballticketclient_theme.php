<?php

function getThemeHeader(){

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<script src="js/jquery-latest.js"></script>
<script type="text/javascript">';

}

function getThemeTitle(){

echo '</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Billetsystem</title>

<!-- Including the jQuery UI Human Theme -->
<link rel="stylesheet" href="css/jquery-ui.css" type="text/css" media="all" />

<!-- Our own stylesheet -->
<link rel="stylesheet" type="text/css" href="css/styles.css" />

</head>

<body>

<h1>Billetsystem</h1>

<center><a href="bballticketclient_import.php"><img src="img/sync.png" height=45px></a>&nbsp;&nbsp;<a href="bballticketclient_scan.php"><img src="img/scan.png" height=45px></a></center><br><br>

<div id="main">

';


}

function getThemeBottom(){

  echo '
  </div>
  </body>
  </html>';

}


?>