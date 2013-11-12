<?php

require("connect.php");
require("config.php");
require("checkConfig.php");
require("checkLogin.php");
require("theme.php");
require_once("commonFunctions.php");

$error="";

getThemeHeader();

?>
function assignGame(game,ref,refnumber,refname){

    if(refnumber == 0){
        alert("Kampen er allerede optaget..");
    }else{
        answer = confirm("Skal kamp nr: "+game+" tildeles til "+refname);
        if (answer !=0){        
          document.refinfo.ref.value = ref;
          document.refinfo.game.value = game;
          document.refinfo.refnumber.value = refnumber;
          document.refinfo.submit();
        }
    } 

}

<?php 

getThemeTitle("Kampanmodninger");

require("menu.php");

if($_POST['game']){

  mysql_query("UPDATE `games` SET `refereeteam".$_POST['refnumber']."id`='".$_POST['ref']."' WHERE `id`='".$_POST['game']."'");
  mysql_query("DELETE FROM `requests` WHERE `game`='".$_POST['game']."' AND `ref`='".$_POST['ref']."'");

}

echo '<table>
       <tr>
        <th width="70px" align="left">Dato</th>
        <th width="60px" align="left">Tid</th>
        <th width="430px" align="left">Info</th>
        <th width="75px" align="left">Dommer</th>
        <th width="100px" align="left"></th>
       </tr>';

$query = mysql_query("SELECT * FROM `requests` ORDER BY `game` ASC");
 
while($row = mysql_fetch_assoc($query)){
    $gamequery = mysql_query("SELECT * FROM `games` WHERE `id`='".$row['game']."'");
    $game = mysql_fetch_assoc($gamequery);
    if($game['date'] < date("Y-m-d")){
        mysql_query("DELETE FROM `requests` WHERE `id`='".$row['id']."'");
    }else{
    switch ($row['status']){
        case 1:
            $refquery = mysql_query("SELECT * FROM `teams` WHERE `id`='".$row['ref']."'");
            $ref = mysql_fetch_assoc($refquery);
            echo "<tr>";
            echo "<td>".$game['date']."</td>";
            echo "<td>".$game['time']."</td>";
            echo "<td>".str_replace("<br>",", ",$game['text'])."</td>";
            echo "<td>".$ref['name']."</td>";
            echo "<td>";
            $refnumber = 1;
            $message = "Tildel";
            if ($game['refereeteam1id']!=0 && $game['refereeteam1id']!=9999){
                $refnumber = 2;
                if ($game['refereeteam2id']!=0 && $game['refereeteam2id']!=9999){
                    $message = "Optaget";
                    $refnumber = 0;
                }
            }
            echo '<span onmouseover="this.style.cursor=\'pointer\'"
                  onmouseout="this.style.cursor=\'default\'"
                  onclick="assignGame('.$row["game"].','.$row["ref"].','.$refnumber.',\''.$ref["name"].'\')";>';
            echo $message;
            echo '</span>';
            echo "</td>";
            echo "</tr>";
        break;
    
    }
    }
}
 
echo "</table>";

echo '<form method="post" name="refinfo">
          <input type="hidden" name="ref">
          <input type="hidden" name="game">
          <input type="hidden" name="refnumber">
      </form>';

getThemeBottom();

?>                            

