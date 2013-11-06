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
createMenuItem("Mine holds kampe","games.php");
createMenuItem("Dommer/Dommerbordstjanser","duties.php");
createMenuItem("Frie Dommertjanser","opengames.php");
?>

</table>

<?php

getThemeBottom();

?>