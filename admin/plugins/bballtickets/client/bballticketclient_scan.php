<?php

require("bballticketclient_connect.php");
require("bballticketclient_check_database.php");
require("bballticketclient_theme.php");

getThemeHeader();
?>

function formfocus() {
   document.getElementById('scan').focus();
}

function FormSubmitGame(el) {
  
  gamelist.submit() ;

  return;
}

function isNumberKey(evt){
     var charCode = (evt.which) ? evt.which : event.keyCode
     if (charCode > 31 && (charCode < 48 || charCode > 57))
          return false;
     return true;
}

window.onload = formfocus;

<?php

getThemeTitle();


if(!isset($_POST['game'])){
    $config = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_config` WHERE id='1'"));
    $teams = explode(",",$config['hold']);
    
    $gamelist = "<option>--- Vælg Kamp ---</option>";
    foreach($teams as $teamid){
         if($teamid != ""){
              $teaminfo = mysql_fetch_assoc(mysql_query("SELECT * FROM `calendars` WHERE id='".$teamid."'"));
              $games = mysql_query("SELECT * FROM `games` WHERE team='".$teamid."' AND homegame='1' ORDER BY date");
              while($game = mysql_fetch_assoc($games)){
                   $opponent = explode(">",$game['text']);
                   $gamelist .= "<option value='".$game['id']."'>".$game['date']."  ".$teaminfo['team']." ".$opponent[1]."</option>\n";
              }
         }
    }

    
    echo '<center><form method="post" name="gamelist" onChange="FormSubmitGame(this)">
           <select name="game">'.$gamelist.'</select>
           </form>
          </center>';

}else{
    $gameinfo = mysql_fetch_assoc(mysql_query("SELECT * FROM `games` WHERE id=".$_POST['game']));
    echo "<center><h3>".$gameinfo['text']."</h3><br></center>";
    //echo '<center><form name="scanform" id="scanform" action="bballticketclient_scan.php" method="post">
    echo '<center><form name="scanform" id="scanform">
           <input id="scan" name="scan" onkeypress="return isNumberKey(event)" type="text" size="20">
           <input id="game" name="game" type="hidden" value="'.$_POST['game'].'">
           <input id="action" name="action" type="hidden" value="scan">
          </form></center>';
    echo '<br><center><h3><div id=message name=message></div></h3></center><br>';
    $query = "SELECT * FROM `bballtickets_checkins` WHERE `game` = ".$_POST['game']." AND `status` = 0";
    $checkins = mysql_num_rows(mysql_query($query));
    echo '<br><center><h3><div id=checkedins name=checkedins><font color="blue" size="16px">'.$checkins.'</font></div></h3></center><br>';

}
getThemeBottom();

?>
<embed id=sound name=sound src="">

<script>
$('#scanform').submit(function() {
  var form = $('#scanform');
  $.ajax({type: "POST", url: "ajax.php",dataType: "json",data: form.serialize(),success: function(data){
     $("#scan").val('');
     $("#message").html('<font color="'+data.color+'">'+data.message+'</font>');
     $("#checkedins").html('<font color="blue" size="16px">'+data.checkins+'</font>');
//     if(data.color == "red")
  },error: function(xhr, status, err) {
     alert(status + ": " + err);
  }
  });
  
  return false;
});
$().ready(function() {

locked = window.setInterval(function(){
    $("#scan").focus();}, 200);
             
});
</script>