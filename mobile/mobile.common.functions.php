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

function createBackButton(){

    echo '<table width="100%">
    <tr>
    <td width="2%">
    </td>
    <td bgcolor="#FFFFFF" width="96%">
    <input type="submit" value="Tilbage" style="font-size:60px;height: 100px; width:100%;" onclick="location.href=\'./\'">
    </td>
    </tr>
    <tr>
    <td height="20px"></td>
    </tr>   
    </table>';

}

?>
