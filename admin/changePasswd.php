<?php

require("theme.php");


getThemeHeader();
?>
function loadinparent(url,id){
  
    opener.document.changepasswd.passwd1.value=document.changepasswd.passwd1.value;
    opener.document.changepasswd.passwd2.value=document.changepasswd.passwd2.value;
    opener.document.changepasswd.changepasswd.value=id;
    opener.document.changepasswd.submit();
    window.close();
       
}
<?php
getThemeTitle(Skift Kodeget);

$id = $_GET["id"];

echo '<form method=post name="changepasswd" action="javascript:loadinparent(\'users.php\', '.$id.' ,true)">
Kode: <input type="password" name="passwd1"><br>
Gentag Kode: <input type="password" name="passwd2"><br>
<input name="changepasswd" type="submit" value="Skift Kode"> 
</form><br>';
echo "</body>";

getThemeBottom()


?>


