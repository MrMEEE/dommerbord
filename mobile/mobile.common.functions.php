<?php

function createMenuItem($name,$location){
echo '<tr>
<td width="2%">
</td>
<td>
<input type="submit" value="'.$name.'" style="font-size:60px;height: 200px; width:96%;" onclick="location.href=\''.$location.'\';">
</td>
</tr>
<tr>
<td height="20px"></td>
</tr>';

}

?>
