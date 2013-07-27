<?php

require("config.php"); 
require("connect.php");
require("checkAdmin.php");
require("checkConfig.php");
require("checkLogin.php");
require("theme.php");

require_once('calendar/classes/tc_calendar.php');
header ( "Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header ("Pragma: no-cache");

getThemeHeader();
?>

<?php
getThemeTitle("Dommer Statistik");

echo '<script language="javascript" src="calendar/calendar.js"></script>
      <link href="calendar/calendar.css" rel="stylesheet" type="text/css">';

require("menu.php"); 

$referees = mysql_query("SELECT * FROM `teams` WHERE `name`!='-'");


echo '<form name="refereeform" method="post">
      Dommer :<select name="referee">
      <option value="">Vælg Dommer</option>';

while($referee = mysql_fetch_assoc($referees)){

    echo '<option value="'.$referee["id"].'" ';
    
    if($_POST['referee'] == $referee['id'])
      echo 'selected';
    echo '>'.$referee['name'].'</option>';

}
echo '</select>';
?>
<table border="0" cellspacing="0" cellpadding="3">
<tr>
<td>Dato :</td>
<td><?php
          if(isset($_REQUEST["date3"])){
           $date3_default = $_REQUEST["date3"];
           $date4_default = $_REQUEST["date4"];
          }else{
            $date4_default = date("Y-m-d");
           if(date("m") > 7){
            $date3_default = date("Y")."-08-01";
           }else{
            $year = date("Y") - 1;
            $date3_default = $year."-08-01";
           }
           
          }
	  $myCalendar = new tc_calendar("date3", true, false);
	  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
	  $myCalendar->setDate(date('d', strtotime($date3_default))
            , date('m', strtotime($date3_default))
            , date('Y', strtotime($date3_default)));
	  $myCalendar->setPath("calendar/");
	  $myCalendar->setYearInterval(1970, 2040);
	  $myCalendar->setAlignment('left', 'bottom');
	  $myCalendar->setDatePair('date3', 'date4', $date4_default);
	  $myCalendar->writeScript();	  
	  
	  $myCalendar = new tc_calendar("date4", true, false);
	  $myCalendar->setIcon("calendar/images/iconCalendar.gif");
	  $myCalendar->setDate(date('d', strtotime($date4_default))
           , date('m', strtotime($date4_default))
           , date('Y', strtotime($date4_default)));
	  $myCalendar->setPath("calendar/");
	  $myCalendar->setYearInterval(1970, 2040);
	  $myCalendar->setAlignment('left', 'bottom');
	  $myCalendar->setDatePair('date3', 'date4', $date3_default);
	  $myCalendar->writeScript();
?>
</td>
</tr>
</table>
<p>
<input type="submit" name="Submit" value="Vis" />
</p>
</form>
<br>
<br>

<?
if(isset($_POST['referee'])){

  $games = mysql_query("SELECT * FROM `games` WHERE (`refereeteam1id`='".$_POST['referee']."' OR `refereeteam2id`='".$_POST['referee']."') AND `homegame`='1' AND `date`>'".$_REQUEST["date3"]."' AND `date`<'".$_REQUEST["date4"]."'");

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
