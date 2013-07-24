<?php

require("config.php");
require("connect.php");

require("checkLogin.php");
require("theme.php");

require("mobile.common.functions.php");

getThemeHeader();

getThemeTitle("Dommerplan Mobil");



?>
<table width="100%">
<?php

createMenuItem("Bekræft tjanser","confirm.php");
createMenuItem("Kampe","games.php");
createMenuItem("Dommer/Dommerbordstjanser","duties.php");
?>

</table>

<?php

getThemeBottom();

?>