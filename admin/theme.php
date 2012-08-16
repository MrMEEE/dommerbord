<?php

function getThemeHeader(){

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>

<script type="text/javascript">';

}

function getThemeTitle($pagename){

require("config.php");

echo '</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'.$klubnavn.' Dommerbordsplan</title>

<!-- Including the jQuery UI Human Theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/humanity/jquery-ui.css" type="text/css" media="all" />

<!-- Our own stylesheet -->
<link rel="stylesheet" type="text/css" href="http://' . $klubadresse . $klubpath . '/admin/css/styles.css" />

</head>

<body>

<h1>'.$klubnavn.' - '.$pagename.'</h1>

<div id="main">';

}

function getThemeBottom(){

  echo '</div>
  </body>
  </html>';

}


?>