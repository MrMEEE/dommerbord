<?php

require("config.php"); 
require("connect.php");
require("checkAdmin.php");
require("checkConfig.php");
require("checkLogin.php");
require("theme.php");

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

<?php
getThemeTitle("Dommer Statistik");

require("menu.php"); 

$referees = mysql_query("SELECT * FROM `teams` WHERE `name`!='-'");


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
     $games = mysql_query("SELECT * FROM `games` WHERE (`refereeteam1id`='".$_POST['referee']."' OR `refereeteam2id`='".$_POST['referee']."') AND `homegame`='1' AND `date`>'".$fromdate."' AND `date`<'".$todate."'");
  }else{
     $games = mysql_query("SELECT * FROM `games` WHERE AND `homegame`='1' AND `date`>'".$fromdate."' AND `date`<'".$todate."'");
  }
     
  
  $confirmed_games = 0;
  $nonconfirmed_games = 0;

  while($game = mysql_fetch_assoc($games)){
     echo $game['id']." : ".$game['date']." ".$game['time']."<br>".preg_replace('/\s+/', ' ',$game['text'])."<br>";
     if($game['refereeteam1id']==$_POST['referee']){
        if($game['ref1confirmed']==1){
           $confirmed_games++;
           echo '<font color="green">Bekræftet</font><br>';
        }else{
           $nonconfirmed_games++;
           echo '<font color="red">Ikke Bekræftet</font><br>';
        }
     }
     if($game['refereeteam2id']==$_POST['referee']){
        if($game['ref2confirmed']==1){
           $confirmed_games++;
           echo '<font color="green">Bekræftet</font><br>';
        }else{
           $nonconfirmed_games++;
           echo '<font color="red">Ikke Bekræftet</font><br>';
        }
     }
     
     echo '<br><br>';
  }
  
  echo $confirmed_games." Bekræftede kampe<br>";
  echo $nonconfirmed_games." Ikke bekræftede kampe";

}
getThemeBottom();

?>
