<?php

require("../admin/config.php");
require("../admin/connect.php");

require("checkLogin.php");
require("theme.php");

getThemeHeader();

getThemeTitle("Dommerplan Mobil");



?>
<table width="100%">
<tr>
<td width="2%">
</td>
<td>
<input type="submit" value="Kampe" style="font-size:60px;height: 200px; width:96%;" onclick="location.href='games.php';">
</td>
</tr>
</table>

<?php

getThemeBottom();

?>