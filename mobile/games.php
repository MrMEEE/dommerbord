<?php

require("config.php");
require("connect.php");

require("checkLogin.php");
require("theme.php");

getThemeHeader();

?>

function confirmRef(gameid,refid,refname){

  answer = confirm("Bekræft at "+refname+" har dømt kamp nummer: "+gameid+"?");
  
  if (answer !=0){
  
  document.confirmref.gameid.value = gameid;
  document.confirmref.refid.value = refid;
  document.confirmref.submit();
  
  }

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

$games_query = "SELECT * FROM `games` WHERE ".substr($team_query,0,-3)." ORDER BY `date`,`time`";

$games = mysql_query($games_query);

?>


<table cellpadding="0" table-layout: fixed;>

<?php

while($game = mysql_fetch_assoc($games)){

echo '<tr>
<td width="2%">
</td>
<td bgcolor="#FFFFFF" width="96%" colspan="2">';

echo $game['id']." : ".$game['date']." ".$game['time']."<br>".preg_replace('/\s+/', ' ',$game['text']);

$ref1_query = mysql_query("SELECT * FROM `teams` WHERE `id`='".$game['refereeteam1id']."'");
$ref2_query = mysql_query("SELECT * FROM `teams` WHERE `id`='".$game['refereeteam2id']."'");

$ref1 = mysql_fetch_assoc($ref1_query);
$ref2 = mysql_fetch_assoc($ref2_query);

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

if($game['ref1confirmed']){
  $ref1confirmed = 'Bekræftet" disabled';
}else{
  $ref1confirmed = 'Bekræft"';
}

if($game['ref2confirmed']){
  $ref2confirmed = 'Bekræftet" disabled';
}else{
  $ref2confirmed = 'Bekræft"';           
}

echo'</td>
</tr>';

echo '<tr>
      <td width=2%></td>
      <td width="48%">Dommer 1:<br>'.$ref1name.'<br><input onclick="confirmRef('.$game['id'].',1,\''.$ref1name.'\');" type="submit" style="font-size:40px;height: 80px; width:95%;" value="'.$ref1confirmed.'></td>
      <td width="48%">Dommer 2:<br>'.$ref2name.'<br><input onclick="confirmRef('.$game['id'].',2,\''.$ref2name.'\');" type="submit" style="font-size:40px;height: 80px; width:95%;" value="'.$ref2confirmed.'></td>
      </tr>';

echo '<tr>
      <td height="15px"></td>
      </tr>';
}
?>
</table>

<form name="confirmref" method="post">
<input type="hidden" name="gameid" value="">
<input type="hidden" name="refid" value="">
</form>

<?php

getThemeBottom();

?>