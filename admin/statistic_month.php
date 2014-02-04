<?php

require("config.php"); 
require("connect.php");
require("checkConfig.php");
require("checkLogin.php");
require("theme.php");
require_once("commonFunctions.php");

getThemeHeader();
?>

function checkMonth(){

   if(document.refereeform.month.value == "season"){
       if(document.refereeform.year.options[0].text == "<?php echo date("Y",strtotime(date("Y").' -7 year')) ?>"){
         document.refereeform.year.length = 0;
<?php
	for ($i = -7; $i <= 7; $i++) {
	   $j = $i + 1;
	   echo 'document.refereeform.year.options[document.refereeform.year.options.length]=new Option("'.date("Y",strtotime(date("Y").' +'.$i.' year')).'/'.date("Y",strtotime(date("Y").' +'.$j.' year')).'","'.date("Y",strtotime(date("Y").' +'.$i.' year')).'/'.date("Y",strtotime(date("Y").' +'.$j.' year')).'",false,';
	   if($i == 0){
	      echo 'true)
	      ';
           }else{
	      echo 'false)
	      ';
           }
        }
?>
        }
   }else{
        if(document.refereeform.year.options[0].text != "<?php echo date("Y",strtotime(date("Y").' -7 year')) ?>"){
        document.refereeform.year.length = 0;
<?php
	for ($i = -7; $i <= 7; $i++) {
	   echo 'document.refereeform.year.options[document.refereeform.year.options.length]=new Option("'.date("Y",strtotime(date("Y").' +'.$i.' year')).'","'.date("Y",strtotime(date("Y").' +'.$i.' year')).'",false,';
	   if($i == 0){
	       echo 'true)
	       ';
           }else{
               echo 'false)
               '; 
           }
        }
?>
      }
   }

}

function changeConfirm(gameid,refid){

   answer = confirm("Er du sikker på at du vil ændre status for denne dommertjans??")
   
   if (answer !=0){
   
       document.changeconfirm.changeconfirmstatus.value = 1;
       document.changeconfirm.refid.value = refid;
       document.changeconfirm.gameid.value = gameid;
       document.changeconfirm.submit();
   
   }   
}

<?php

getThemeTitle("Dommer Statistik");

if(isset($_POST['changeconfirmstatus'])){
   $game = mysql_fetch_assoc(mysql_query("SELECT * FROM `games` WHERE `id`='".$_POST['gameid']."'"));
   
   if($game['ref1confirmed'] == 0 || $game['ref1confirmed'] == ""){
       $ref1new = 1;
   }else{
       $ref1new = 0;
   }
   
   if($game['ref2confirmed'] == 0 || $game['ref2confirmed'] == ""){
       $ref2new = 1;
   }else{
       $ref2new = 0;
   }
   
   if($game['refereeteam1id'] == $_POST['refid']){
       $update = "`ref1confirmed`='".$ref1new."'";
   }
   
   if($game['refereeteam2id'] == $_POST['refid']){
       if($update==""){
           $update = "`ref2confirmed`='".$ref2new."'";
       }else{
           $update .= ",`ref2confirmed`='".$ref2new."'";
       }
   }
   
   if($update !=""){
       
       $query = "UPDATE `games` SET ".$update." WHERE `id`='".$game['id']."'";
       mysql_query($query);
   
   }
   

}

require("menu.php"); 

$referees = mysql_query("SELECT * FROM `teams` WHERE `name`!='-' AND `person`='1'");


echo '<form name="refereeform" method="post">
      Dommer :<select name="referee">
      <option value="">Vælg Dommer</option>
      <option value="all" ';
      if($_POST['referee'] == "all")
        echo 'selected';      
echo '>Alle</option>';
      
while($referee = mysql_fetch_assoc($referees)){

    echo '<option value="'.$referee["id"].'" ';
    
    if($_POST['referee'] == $referee['id'])
      echo 'selected';
    echo '>'.$referee['name'].'</option>';

}
?>
</select>
Måned :<select name="month" onchange="javascript:checkMonth()">
<option value="1" <?php if($_POST['month'] == 1){ echo " selected";} ?>>Januar</option>
<option value="2" <?php if($_POST['month'] == 2){ echo " selected";} ?>>Februar</option>
<option value="3" <?php if($_POST['month'] == 3){ echo " selected";} ?>>Marts</option>
<option value="4" <?php if($_POST['month'] == 4){ echo " selected";} ?>>April</option>
<option value="5" <?php if($_POST['month'] == 5){ echo " selected";} ?>>Maj</option>
<option value="6" <?php if($_POST['month'] == 6){ echo " selected";} ?>>Juni</option>
<option value="7" <?php if($_POST['month'] == 7){ echo " selected";} ?>>Juli</option>
<option value="8" <?php if($_POST['month'] == 8){ echo " selected";} ?>>August</option>
<option value="9" <?php if($_POST['month'] == 9){ echo " selected";} ?>>September</option>
<option value="10" <?php if($_POST['month'] == 10){ echo " selected";} ?>>Oktober</option>
<option value="11" <?php if($_POST['month'] == 11){ echo " selected";} ?>>November</option>
<option value="12" <?php if($_POST['month'] == 12){ echo " selected";} ?>>December</option>
<option value="season" <?php if($_POST['month'] == "season"){ echo " selected";} ?>>Hele Sæsonen</option>
</select>

<?php
echo 'År :<select name="year">';
for ($i = -7; $i <= 7; $i++) {
   $j = $i + 1;
   if($_POST['month'] == "season"){
      $year = date("Y",strtotime(date("Y").' +'.$i.' year')).'/'.date("Y",strtotime(date("Y").' +'.$j.' year'));
   }else{
      $year = date("Y",strtotime(date("Y").' +'.$i.' year'));
   }
   echo '<option value="'.$year.'" ';
   if(isset($_POST['year'])){
       if($_POST['year'] == $year){
           echo 'selected';
       }
   }else{
       if($i == 0){
           echo 'selected';
       }    
   }
   echo '>'.$year.'</option>
  ';
}

echo '</select>

<input type="submit" name="Submit" value="Vis" />
</form>
<br>
<br>';

if(isset($_POST['referee'])){

  if($_POST['month'] == "season" ){
  
     $fromdate = substr($_POST['year'],0,4)."-08-01";
     $todate = date("Y-m-d",strtotime($fromdate.' + 1 year'));
  
  }else{
     
     $fromdate = $_POST['year']."-".$_POST['month']."-01";
     $todate = date("Y-m-t",strtotime($fromdate));
  }

  if($_POST['referee'] != "all"){
     $refs = mysql_query("SELECT * FROM `teams` WHERE `id`='".$_POST['referee']."'");
  }else{
     $refs = mysql_query("SELECT * FROM `teams` WHERE `person`='1'");
  }   
     while($ref=mysql_fetch_assoc($refs)){
       $confirmed_games = 0;
       $nonconfirmed_games = 0;
       $confirmed_grandprix = 0;
       $nonconfirmed_grandprix = 0;
       $games = mysql_query("SELECT * FROM `games` WHERE `homegame`='1' AND `date`>='".$fromdate."' AND `date`<='".$todate."'");;
       echo 'Dommer: <font color="blue">'.$ref['name'].'</font><br><br>';
       echo '<table width="100%">';
       while($game = mysql_fetch_assoc($games)){
           $confirmed = 0;
           if(($game['refereeteam1id']==$ref['id']) || ($game['refereeteam2id']==$ref['id'])){
               $game_info = $game['id']." : ".$game['date']." ".$game['time']."<br>".preg_replace('/\s+/', ' ',$game['text'])."<br>";
               if($game['grandprix']==1){
                   $game_info .= '<font color="darkblue">GrandPrix Kamp</font><br>';
               }
               if($game['refereeteam1id']==$ref['id']){
                   if($game['ref1confirmed']==1){
                       $confirmed = 1;
                       if($game['grandprix']==1){
                           $confirmed_grandprix++;
                       }else{
                           $confirmed_games++;
                       }
                       $game_info .= '<font color="green">Bekræftet</font><br>';
                   }else{
                       if($game['grandprix']==1){
                           $nonconfirmed_grandprix++;
                       }else{
                           $nonconfirmed_games++;
                       }
                       $game_info .= '<font color="red">Ikke Bekræftet</font><br>';
                   }
               }
               if($game['refereeteam2id']==$ref['id']){
                   if($game['ref2confirmed']==1){
                       $confirmed = 1;
                       if($game['grandprix']==1){
                           $confirmed_grandprix++;
                       }else{
                           $confirmed_games++;
                       }
                       $game_info .= '<font color="green">Bekræftet</font><br>';
                   }else{
                       if($game['grandprix']==1){
                           $nonconfirmed_grandprix++;
                       }else{    
                           $nonconfirmed_games++;
                       }
                       $game_info .= '<font color="red">Ikke Bekræftet</font><br>';
                   }
               }
               if($game_info != ""){		
               echo '<tr width="100%">
                     <td width="45%">
                     ';
               if($confirmed == 1 ){
                   echo $game_info;
                   $arrow = "img/unconfirm.jpg";
                   $text = "Afkræft dommertjans";
               }else{
                   $arrow = "img/confirm.jpg";
                   $text = "Bekræft dommertjans";
               }
               echo '</td>
                     <td width="10%">
                     <a href="#" onClick="changeConfirm('.$game['id'].','.$ref['id'].')"><img src="'.$arrow.'" width="40px" title="'.$text.'"></a>
                     </td>
                     <td width="45%">';
               if($confirmed != 1 ){ 
                   echo $game_info;
               }
       
               echo '</td>
                     </tr>
                     <tr height="15px"></tr>';
                     
               }
             
           } 
       }
       echo '<tr>
             <td>'.$confirmed_games.' Bekræftede kampe<br>
             '.$nonconfirmed_games.' Ikke bekræftede kampe<br>
             '.$confirmed_grandprix.' Bekræftede GrandPrix kampe<br>
             '.$nonconfirmed_grandprix.' Ikke bekræftede GrandPrix kampe<br>
             </td>
             </tr>
             <tr height="25px"></tr>';
             
       echo '</table>';
  }
     
  
}

echo '<form name="changeconfirm" method="post">
       <input name="changeconfirmstatus" type="hidden">
       <input name="gameid" type="hidden">
       <input name="refid" type="hidden" value="">
       <input name="referee" type="hidden" value="'.$_POST['referee'].'">
       <input name="month" type="hidden" value="'.$_POST['month'].'">
       <input name="year" type="hidden" value="'.$_POST['year'].'">
      </form>';

getThemeBottom();

?>
