<?php
  
require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("../../theme.php");

require("bballstats_stats_class.php");
  
getThemeHeader();

?>

function CreatePlayerStats(playerid){

        document.stats.gameid.value=<?php echo $_POST['gameid'] ?>;
        document.stats.action.value="add";
        document.stats.playerid.value=playerid;
        stats.submit();
}

function RemovePlayerStats(playerid){

        answer = confirm("Er du sikker på at du vil slette disse statistikker??")
        if (answer !=0){
               document.stats.gameid.value=<?php echo $_POST['gameid'] ?>;
               document.stats.action.value="remove";
               document.stats.playerid.value=playerid;
               stats.submit();
        }

}

<?

getThemeTitle("Statistik");

require("../../menu.php");

if(isset($_POST['playerid']) && ($_POST['playerid'] != "")){
      if($_POST['action']=="add"){
             if(!mysql_num_rows(mysql_query("SELECT * FROM `bballstats_stats` WHERE kampid='".$_POST['gameid']."' AND spiller='".$_POST['playerid']."'")))
                     mysql_query("INSERT INTO `bballstats_stats` SET kampid='".$_POST['gameid']."', spiller='".$_POST['playerid']."'");
      }elseif($_POST['action']=="remove"){
             mysql_query("DELETE FROM `bballstats_stats` WHERE kampid='".$_POST['gameid']."' AND spiller='".$_POST['playerid']."'");
      }

}


$kampinfo = mysql_fetch_assoc(mysql_query("SELECT * FROM `games` WHERE id='".$_POST['gameid']."'"));

$udehold = explode("Mod ",$kampinfo['text']);
$hjemmehold = explode(":",$kampinfo['text']);

$query = mysql_query("SELECT * FROM `bballstats_stats` WHERE kampid='".$_POST['gameid']."'");

$statsform = "";

$excludelist = "0,";

$stats = array();

if(mysql_num_rows($query)){
        while($row = mysql_fetch_assoc($query)){
                $stats[] = new stats($row);
                $excludelist .= $row['spiller'].",";
        }
}

$excludelist = substr_replace($excludelist ,"",-1);

echo '<h3>Kamp nummer: '.$kampinfo['id'].', '.$hjemmehold[0].' mod '.$udehold[1].', '.$kampinfo['date'].'</h3><br>';

if($kampinfo['result'] !=""){
      echo '<br><h3>Resultat: '.$kampinfo['result'].'</h3><br>';
}

echo '<table class="stats" cellpadding="0" border="0">     
     <tr>
     <th width="150px" align="left">Spiller</th>';
$query = mysql_query("SHOW COLUMNS FROM `bballstats_stats`");
while($stattype = mysql_fetch_assoc($query)){
     if(($stattype['Field']!="id") && ($stattype['Field']!="spiller") && ($stattype['Field']!="kampid")){
           if(substr($stattype["Field"],0,2)=="£"){
                   list($start,$operation,$stat)=split("£",$stattype["Field"]);
           }else{
                   $stat = $stattype["Field"];
           }
           echo '<th width="45px">'.$stat.'</th>';
     }     
}     
echo '</tr>';
echo '</table>';

foreach($stats as $stat){
        echo $stat;
}
echo '<form method="post" action="#" id="teamstats" name="teamstats" class="teamstats">';
echo '<table class="teamstats" cellpadding="0" border="0">
      <tr>
      <td width="150px" align="left">Hold</td>';
$query = mysql_query("SHOW COLUMNS FROM `bballstats_stats`");
while($stattype = mysql_fetch_assoc($query)){
      if(substr($stattype["Field"],0,2)=="£"){
            list($start,$operation,$stat)=split("£",$stattype["Field"]);
      }else{     
            $stat = $stattype["Field"];
      }
      if(($stattype['Field']!="id") && ($stattype['Field']!="spiller") && ($stattype['Field']!="kampid")){
            echo '<td width="45px" align="center"><input  style="width:30px;text-align:right;" type="text" class="'.$stat.'" name="'.$stattype['Field'].'" id="'.$stattype['Field'].'" readonly="readonly"></td>';
      }

}
echo '</tr>';
echo '</table>';
echo '</form>';

echo '<br><h3>Tilføj Spillere: </h3><br>';

$query = mysql_query("SELECT * FROM `bballstats_players` WHERE id NOT IN (".$excludelist.") AND hold='".$kampinfo['team']."'");

while($player=mysql_fetch_assoc($query)){
        echo '<a href="javascript:void(CreatePlayerStats(\''.$player["id"].'\'))"><img width="15px" src="img/add.png"></a> '.$player['fornavn'].' '.$player['efternavn']."<br>";
}

echo '<form method="post" name="stats" id="stats">
        <input type="hidden" id="gameid" name="gameid">
        <input type="hidden" id="playerid" name="playerid">
        <input type="hidden" id="action" name="action">
      </form>';

echo '<form method="post" class="getstats" name="getstats" id="getstats">
        <input type="hidden" name="action" id="action" value="get">
        <input type="hidden" name="gameid" id="gameid" value="'.$_POST['gameid'].'">
      </form>';

?>

<script>

$(document).ready(function(){
var getform = $('form#getstats');
var setform = $('form#teamstats');
$.ajax({type: "POST", url: "ajax.php",dataType: "json",data: getform.serialize() ,success: function(data){
      $.each(data, function(label,value){
        setform.children();
        setform.children().find('.'+label);
        setform.children().find('.'+label).val(value);
      });
  },error: function(xhr, status, err) {
      alert(status + ": " + err);
  }
});
  
  $('.statsform').change(function(){
    var form = $(this).closest('.statsform');
    $.ajax({type: "POST", url: "ajax.php",dataType: "json",data: form.serialize(),success: function(data){
          $.each(data, function(label,value){
               form.children();
               form.children().find('.'+label);
               form.children().find('.'+label).val(value);
          });
    }, error: function(xhr, status, err) {
            alert(status + ": " + err);
    }
                  
    
    });
    var getform = $('form#getstats');
    var setform = $('form#teamstats');
    
    $.ajax({type: "POST", url: "ajax.php",dataType: "json",data: getform.serialize() ,success: function(data){
          $.each(data, function(label,value){
            setform.children();
            setform.children().find('.'+label);
            setform.children().find('.'+label).val(value);
          });
    },error: function(xhr, status, err) {
            alert(status + ": " + err);
      }
                   
    });
  }); 
});

</script>

<?php
getThemeBottom();

?>

