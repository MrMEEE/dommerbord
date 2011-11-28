<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<SCRIPT LANGUAGE="javascript">
function loadinparent(url,id){
  
    opener.document.changepasswd.passwd1.value=document.changepasswd.passwd1.value;
    opener.document.changepasswd.passwd2.value=document.changepasswd.passwd2.value;
    opener.document.changepasswd.changepasswd.value=id;
    opener.document.changepasswd.submit();
//    self.opener.location = url;
    window.close();
       
}
</SCRIPT>
<!-- Including the jQuery UI Human Theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/humanity/jquery-ui.css" type="text/css" media="all" />

<!-- Our own stylesheet -->
<link rel="stylesheet" type="text/css" href="styles.css" />

</head>

<body>
<h1>Skift Kode</h1>


       
<?php
$id = $_GET["id"];

echo '<form method=post name="changepasswd" action="javascript:loadinparent(\'users.php\', '.$id.' ,true)">
Kode: <input type="password" name="passwd1"><br>
Gentag Kode: <input type="password" name="passwd2"><br>
<input name="changepasswd" type="submit" value="Skift Kode"> 
</form><br>';
echo "</body>";


?>


