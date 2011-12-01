<?php

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-blog-header.php')){
  require($_SERVER['DOCUMENT_ROOT'].'/wp-blog-header.php');
  get_header();
  if ( file_exists( TEMPLATEPATH . '/sidebar2.php') )
    load_template( TEMPLATEPATH . '/sidebar2.php');
  else
    load_template( ABSPATH . 'wp-content/themes/default/sidebar.php');
}else{
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dommerbordsplan</title>

<!-- Including the jQuery UI Human Theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/humanity/jquery-ui.css" type="text/css" media="all" />

<!-- Our own stylesheet -->
<link rel="stylesheet" type="text/css" href="admin/styles.css" />

</head>

<body>

<h1>Dommerbordsplan</h1>

<div id="main" align="center">

<br>';

}

?>

<?php
require("table.php");
?>

<?php

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-blog-header.php')){
  get_sidebar();
  get_footer(); 
}else{
  echo '</div><br></body>
  </html>';
}  
?>
