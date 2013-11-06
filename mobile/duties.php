<?php

require("config.php");
require("connect.php");

require("checkLogin.php");
require("theme.php");
require("mobile.common.functions.php");

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

$teams = explode(",",$user['refs']);

foreach($teams as $team){

  if($team != ""){
      $ref1_query .= "`refereeteam1id`='".$team."' OR ";
      $ref2_query .= "`refereeteam2id`='".$team."' OR ";
      $table1_query .= "`tableteam1id`='".$team."' OR ";
      $table2_query .= "`tableteam2id`='".$team."' OR ";
      $table3_query .= "`tableteam3id`='".$team."' OR ";
  }

}


if(date("m") > 7){

$fromdate = date("Y")."-08-01";

}else{

$year = date("Y") - 1;
$fromdate = $year."-08-01";

}

createBackButton();

if($_POST['showall'] != "yes"){
    $games_query = "SELECT * FROM `games` WHERE ((".substr($ref1_query,0,-3).") OR (".substr($ref2_query,0,-3).") OR (".substr($table1_query,0,-3).") OR (".substr($table2_query,0,-3).") OR (".substr($table3_query,0,-3).")) AND `date`>='".$fromdate."' AND `homegame`='1' ORDER BY `date`,`time`";
    createShowAllButton();
}else{
    $games_query = "SELECT * FROM `games` WHERE ((".substr($ref1_query,0,-3).") OR (".substr($ref2_query,0,-3).") OR (".substr($table1_query,0,-3).") OR (".substr($table2_query,0,-3).") OR (".substr($table3_query,0,-3).")) AND `homegame`='1' ORDER BY `date`,`time`";
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

echo $game['id']." : ".$game['date']." ".$game['time']."<br>".preg_replace('/\s+/', ' ',$game['text'])."<br>".$game['place']."<br>";

foreach($teams as $team){

  $team_name = mysql_fetch_assoc(mysql_query("SELECT * FROM `teams` WHERE `id`='".$team."'"));
  
  $number_of_refs = 0;
  $number_of_table = 0;
  
  if($game['refereeteam1id'] == $team)
    $number_of_refs++;
  if($game['refereeteam2id'] == $team)
    $number_of_refs++;
  if($game['tableteam1id'] == $team)
    $number_of_table++;
  if($game['tableteam2id'] == $team)
    $number_of_table++;
  if($game['tableteam3id'] == $team)
    $number_of_table++;
    
  if($number_of_refs > 0)
    echo $team_name['name']." : ".$number_of_refs." Dommertjans(er)<br>";
  
  if($number_of_table > 0)
    echo $team_name['name']." : ".$number_of_table." Dommerbordstjans(er)<br>";
}

echo '</td></tr>';
echo '<tr height="20px"></tr>';
}
?>
</table>

<form name="confirmref" method="post">
<input type="hidden" name="showall" value="<?php echo $_POST['showall']?>">
</form>

<?php

getThemeBottom();

?>