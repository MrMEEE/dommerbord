<?php

require("theme.php");

getThemeHeader();
?>
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

<?php

getThemeTitle("Skift Navn");


       
$id = $_GET["id"];

echo '<form method=post name="changeperson" action="javascript:loadinparent(\'people.php\', '.$id.' ,true)">
Nyt Navn for '.$_GET["name"].': <br><input id="newname" type="text" name="newname"><br>
<input name="changeperson" type="submit" value="Skift Navn"> 
</form><br>';

getThemeBottom();

?>


