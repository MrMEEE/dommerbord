<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<SCRIPT LANGUAGE="javascript">
function loadinparent(url,id){
  
    opener.document.changeperson.newname.value=document.changeperson.newname.value;
    opener.document.changeperson.changeperson.value=id;
    opener.document.changeperson.submit();
    window.close();
       
}
function formfocus() {
   document.getElementById('newname').focus();
}
window.onload = formfocus;
</SCRIPT>
<!-- Including the jQuery UI Human Theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/humanity/jquery-ui.css" type="text/css" media="all" />

<!-- Our own stylesheet -->
<link rel="stylesheet" type="text/css" href="styles.css" />

</head>

<body>
<h1>Skift Navn</h1>


       
<?php
$id = $_GET["id"];

echo '<form method=post name="changeperson" action="javascript:loadinparent(\'people.php\', '.$id.' ,true)">
Nyt Navn for '.$_GET["name"].': <br><input id="newname" type="text" name="newname"><br>
<input "name="changeperson" type="submit" value="Skift Navn"> 
</form><br>';
echo "</body>";


?>


