<?php

require("config.php");
require("connect.php");

require("checkLogin.php");
require("theme.php");
require("mobile.common.functions.php");

getThemeHeader();

?>

function confirmRequest(gameid,refid){

  answer = confirm("Bekræft at du vil ansøge om at dømme kamp nummer: "+gameid);

  if (answer !=0){

  document.confirmref.gameid.value = gameid;
  document.confirmref.refid.value = refid;
  document.confirmref.submit();

  }

}

function showAll(){

  document.confirmref.showall.value = "yes";
  document.confirmref.submit();

}

<?php

if(isset($_POST['refid'])){

  mysql_query("INSERT INTO `requests` (`ref`,`game`,`status`) VALUES ('".$_POST['refid']."','".$_POST['gameid']."','1')");

}

getThemeTitle("Dommerplan Mobil");

$user = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `name`='".$_SESSION['username']."'"));

$fromdate = date("Y-m-d");

createBackButton();

$games_query = "SELECT * FROM `games` WHERE (`refereeteam1id`='0' OR `refereeteam1id`='0' OR `refereeteam2id`='9999' OR `refereeteam2id`='9999') AND `date`>='".$fromdate."' AND `homegame`='1' ORDER BY `date`,`time`";

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

$ref1 = mysql_fetch_assoc($ref1_query);
$ref2 = mysql_fetch_assoc($ref2_query);

if($ref1['name'] == "DBBF"){
  $ref1name = $ref1['name'].": ".trim(preg_replace('/\s+/', ' ', $game['referee1name']));
}elseif($ref1['name'] == "-" || $ref1['name'] == ""){
  $ref1name = "Ikke påsat";
}else{
  $ref1name = $ref1['name'];
}

if($ref2['name'] == "DBBF"){
  $ref2name = $ref2['name'].": ".trim(preg_replace('/\s+/', ' ', $game['referee2name']));
}elseif($ref2['name'] == "-" || $ref2['name'] == ""){
  $ref2name = "Ikke påsat";
}else{ 
  $ref2name = $ref2['name'];          
}

echo'</td>
</tr>';

$query = mysql_query("SELECT * FROM `requests` WHERE `ref`='".$user['refs']."' AND `game`='".$game['id']."'");

if(mysql_num_rows($query)==0){
    $value = "Anmod om kamp";
    $disabled = "";
}else{
    $value = "Anmodning sendt";
    $disabled = "disabled";
}
echo '<tr>
      <td width=2%></td>
      <td width="48%">Dommer 1:<br>'.$ref1name.'</td>
      <td width="48%">Dommer 2:<br>'.$ref2name.'</td>
      </tr>
      <tr>
      <td width=2%></td>
      <td colspan=2>
      <input onclick="confirmRequest('.$game['id'].','.$user['refs'].');" type="submit" style="font-size:30px;height: 80px; width:95%;" value="'.$value.'" '.$disabled.'>
      </td>
      </tr>';

echo '<tr><td height="40px"></td></tr>';

}
?>
</table>

<form name="confirmref" method="post">
<input type="hidden" name="gameid" value="">
<input type="hidden" name="refid" value="">
<input type="hidden" name="showall" value="<?php echo $_POST['showall']?>">
</form>

<?php

getThemeBottom();

?>
