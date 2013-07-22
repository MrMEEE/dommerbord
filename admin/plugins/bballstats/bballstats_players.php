<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");
require("bballstats_check_database.php");

getThemeHeader();

?>

function ConfirmRemove(userid){
 
 answer = confirm("Er du sikker på at du vil slette denne spiller??")
 
 if (answer !=0)
 {
   document.spiller.fjernspiller.value=userid;
   spiller.submit();
 }
  
}

function FormSubmitTeam(el) {  
  
  var teaminfo = el.value.split(':');
  
  document.teamlist.teamid.value=teaminfo[0]
  document.teamlist.teamname.value=teaminfo[1]
  teamlist.submit() ;

  return;
}

function redigerSpiller(id,teamid){   
 
  var path = "bballstats_players_profile.php?id=" + id + "&teamid=" + teamid; 
  mywindow = window.open(path,"mywindow","menubar=1,resizable=1,width=1000,height=650");
}

<?php

getThemeTitle("Statistik Spillere");

$selectedteam = $_POST['teamid'];

if(isset($_POST['id'])){

     if($_POST['id'] == "-1"){
            mysql_query("INSERT INTO `bballstats_players` SET hold='".$_POST['teamid']."', fornavn='".$_POST['fornavn']."', efternavn='".$_POST['efternavn']."',nummer='".$_POST['nummer']."', position='".$_POST['position']."', beskrivelse='".$_POST['beskrivelse']."',photo='".$_POST['photo']."'");
     }else{
            mysql_query("UPDATE `bballstats_players` SET hold='".$_POST['teamid']."', fornavn='".$_POST['fornavn']."', efternavn='".$_POST['efternavn']."',nummer='".$_POST['nummer']."', position='".$_POST['position']."', beskrivelse='".$_POST['beskrivelse']."',photo='".$_POST['photo']."' WHERE id='".$_POST['id']."'");
     }
}

if(isset($_POST['fjernspiller'])){
 
     mysql_query("DELETE FROM `bballstats_players` WHERE `id` = '".$_POST['fjernspiller']."'");

}

require("../../menu.php");

$teamlist = "";

if(mysql_num_rows(mysql_query("SELECT * FROM bballstats_config WHERE id = 1 AND hold = ''"))){

     $teamlist .= '<option value="" selected>Ingen hold aktiveret</option>';

}else{

     $teamlist .= '<option value=""'; 
     if($selectedteam==""){
          $teamlist .= ' selected';
     }
     $teamlist .= '>Vælg hold</option>';
     
     $teams = mysql_fetch_assoc(mysql_query("SELECT * FROM bballstats_config WHERE id = 1"));
     
     $teams = explode(",",$teams['hold']);
     
     foreach($teams as $teamid){
          if($teamid != ""){
               $teaminfo = mysql_fetch_assoc(mysql_query("SELECT * FROM calendars WHERE id = '".$teamid."'"));
               $teamlist .= '<option value="'.$teamid.':'.$teaminfo['team'].'"';
               if($_POST['teamid']==$teamid){
                     $teamlist .= ' selected';
               }
               $teamlist .= '>'.$teaminfo['team'].'</option>';
          }
     }
}

echo 'Hold:
<form method="post" name="teamlist">
 <select name="teamselect" onChange="FormSubmitTeam(this)">
  '.$teamlist.'
  <input type="hidden" id="teamid" name="teamid" value="">
  <input type="hidden" id="teamname" name="teamname" value="">
 </select>
</form>
<form method="post" name="spiller">
 
 <input type="hidden" id="fornavn" name="fornavn" value="">
 <input type="hidden" id="efternavn" name="efternavn" value="">
 <input type="hidden" id="nummer" name="nummer" value="">
 <input type="hidden" id="id" name="id" value="">
 <input type="hidden" id="teamid" name="teamid" value="'.$_POST['teamid'].'">
 <input type="hidden" id="fjernspiller" name="fjernspiller" value="">
 <input type="hidden" id="position" name="position" value="">
 <input type="hidden" id="beskrivelse" name="beskrivelse" value="">
 <input type="hidden" id="photo" name="photo" value="">
</form>
<br>';
if($_POST['teamid'] !=""){
     echo '<a href="javascript:void(0)" onclick="redigerSpiller(-1,'.$_POST['teamid'].');"><img width="25px" src="img/add.png"></a> <font size="3">Tilføj Spiller</font>';
}
echo '<br>
<br>
';

$query = mysql_query("SELECT * FROM bballstats_players WHERE hold = '".$_POST['teamid']."' ORDER BY `fornavn` ASC ");

if(mysql_num_rows($query)){
     while($player = mysql_fetch_assoc($query)){
          echo '<a href="javascript:void(ConfirmRemove('.$player['id'].'))"><img width="15px" src="img/remove.png"></a>
          <a href="javascript:void(0)" onclick="redigerSpiller('.$player['id'].','.$_POST['teamid'].');"><img width="15px" src="img/edit.png">
          </a> '.$player['fornavn'].' '.$player['efternavn'].'<br>';
     }
} 

getThemeBottom();
?>
