<?php

require("config.php");
require("connect.php");

require("checkLogin.php");
require("theme.php");

getThemeHeader();

?>

function showAll(){

  document.confirmref.showall.value = "yes";
  document.confirmref.submit();

}

<?php

if(isset($_POST['refid'])){

  mysql_query("UPDATE `games` SET `ref".$_POST['refid']."confirmed`='1' WHERE `id`='".$_POST['gameid']."'");

}

getThemeTitle("Dommerplan Mobil");

$user = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `name`='".$_SESSION['username']."'"));

$teams = explode(",",$user['teams']);

foreach($teams as $team){

  if($team != ""){
      $team_query .= "`team`='".$team."' OR ";
  }

}


if(date("m") > 7){

$fromdate = date("Y")."-08-01";

}else{

$year = date("Y") - 1;
$fromdate = $year."-08-01";

}


if($_POST['showall'] != "yes"){
    $games_query = "SELECT * FROM `games` WHERE (".substr($team_query,0,-3).") AND `date`>='".$fromdate."' ORDER BY `date`,`time`";
    echo '<table width="100%">
    <tr>
    <td width="2%">
    </td>
    <td bgcolor="#FFFFFF" width="96%">
    <input type="submit" value="Vis Alle Kampe" style="font-size:60px;height: 100px; width:100%;" onclick="showAll();">
    </td>
    </tr>
    <tr>
    <td height="20px"></td>
    </tr>   
    </table>';
}else{
    $games_query = "SELECT * FROM `games` WHERE (".substr($team_query,0,-3).") ORDER BY `date`,`time`";
}

$games = mysql_query($games_query);


?>
<table cellpadding="0" table-layout: fixed;>

<?php

while($game = mysql_fetch_assoc($games)){

echo '<tr>
<td width="2%">
</td>
<td bgcolor="#FFFFFF" width="96%" colspan="2">';

echo $game['id']." : ".$game['date']." ".$game['time']."<br>".preg_replace('/\s+/', ' ',$game['text'])."<br>".$game['place'];

$ref1_query = mysql_query("SELECT * FROM `teams` WHERE `id`='".$game['refereeteam1id']."'");
$ref2_query = mysql_query("SELECT * FROM `teams` WHERE `id`='".$game['refereeteam2id']."'");
$table1_query = mysql_query("SELECT * FROM `teams` WHERE `id`='".$game['tableteam1id']."'");
$table2_query = mysql_query("SELECT * FROM `teams` WHERE `id`='".$game['tableteam2id']."'");
$table3_query = mysql_query("SELECT * FROM `teams` WHERE `id`='".$game['tableteam3id']."'");

$ref1 = mysql_fetch_assoc($ref1_query);
$ref2 = mysql_fetch_assoc($ref2_query);
$table1 = mysql_fetch_assoc($table1_query);
$table2 = mysql_fetch_assoc($table2_query);
$table3 = mysql_fetch_assoc($table3_query);

if($ref1['name'] == "DBBF"){
  $ref1name = $ref1['name'].": ".trim(preg_replace('/\s+/', ' ', $game['referee1name']));
}else{
  $ref1name = $ref1['name'];
}

if($ref2['name'] == "DBBF"){
  $ref2name = $ref2['name'].": ".trim(preg_replace('/\s+/', ' ', $game['referee2name']));
}else{ 
  $ref2name = $ref2['name'];          
}

echo'</td>
</tr>';
if($game['homegame'] == "1"){
echo '<tr>
      <td width=2%></td>
      <td width="48%">Dommer 1:<br>'.$ref1name.'</td>
      <td width="48%">Dommer 2:<br>'.$ref2name.'</td>
      </tr>';

echo '<tr><td height="15px"></td></tr>';
      
echo '<tr>
      <td width=2%></td>
      <td width="48%">Dommerbord:<br>'.$table1['name'].'</td>
      <td width="48%">Dommerbord:<br>'.$table2['name'].'</td>
      </tr>';

echo '<tr><td height="15px"></td></tr>';

echo '<tr>
      <td width=2%></td>
      <td width="48%">24 Sekunder:<br>'.$table3['name'].'</td>
      <td width="48%"></td>
      </tr>';
}
echo '<tr><td height="40px"></td></tr>';

}
?>
</table>

<form name="confirmref" method="post">
<input type="hidden" name="showall" value="<?php echo $_POST['showall']?>">
</form>

<?php

getThemeBottom();

?>