<?php

require "admin/connect.php";

$klubnavn = "BMS";


$query="SELECT id,address,team FROM `calendars`";



$calendars = mysql_query($query);

if (!$calendars) {
  echo "Could not successfully run query ($query) from DB: " . mysql_error();
  exit;
}
     
if (mysql_num_rows($calendars) == 0) {
  echo "No rows found, nothing to print so am exiting";
  exit;
}
while($icals=mysql_fetch_assoc($calendars)){

$currentteam=$icals['team'];

print_r("<br>Syncing: $currentteam<br>");

  // new dom object  
  $dom = new DOMDocument();  
  
  //load the html  
 $content        = file_get_contents($icals['address']);
 echo $icals['address'];
$page = '
<html>
 <head>
     <meta http-equiv="content-type" content="text/html; charset=utf-8">
         <title>Dommer Sync</title>
           </head>
           <body></body>
           </html>

';
$page .= $content;
 $html = $dom->loadHTML($page);
//$dom->encoding = 'utf-8';
  //discard white space   
  $dom->preserveWhiteSpace = false;   
  
  //the table by its tag name  
  $tables = $dom->getElementsByTagName('table');   
  
  //get all rows from the table  
  echo $tables->length;
  if ( $tables->length > 1){
  $rows = $tables->item(1)->getElementsByTagName('tr');   
  }
  else{
  $rows = $tables->item(0)->getElementsByTagName('tr');
  }
  
  // loop over the table rows  
  foreach ($rows as $row)   
  {   
    
   // get each column by tag name  
      $cols = $row->getElementsByTagName('td');   
      $hometeam = $cols->item(2)->nodeValue;
      $hometeam = str_replace("\n", "", $hometeam);
      $hometeam = str_replace("\r", "", $hometeam);
      $hometeam = str_replace(" ", "", $hometeam);
      $awayteam = $cols->item(3)->nodeValue;
      $awayteam = str_replace("\n", "", $awayteam);
      $awayteam = str_replace("\r", "", $awayteam);
      $awayteam = str_replace(" ", "", $awayteam);
      if(substr($hometeam,0, strlen($klubnavn)) == $klubnavn){
   // echo the values    
      $id=$cols->item(0)->nodeValue;
      $id=str_replace("\n", "", $id);
      $id=str_replace("\r", "", $id);
      $id=str_replace(" ", "", $id);
      echo $id;
      
      $fulldate= $cols->item(1)->nodeValue;
      $fulldate = str_replace("\n", "", $fulldate);
      $fulldate = str_replace("\r", "", $fulldate);
      $fulldate = str_replace(" ", "", $fulldate );

      $date = "20";
      $date .= substr($fulldate,6,2);
      $date .= "-";
      $date .= substr($fulldate,3,2);
      $date .= "-";
      $date .= substr($fulldate,0,2);
      $time = substr($fulldate,13,2);
      $time .= ":";
      $time .= substr($fulldate,16,2);
	$text = $currentteam;
	$text .= " Vs. ";
	$text .= $awayteam;

    if(mysql_num_rows(mysql_query("SELECT id FROM games WHERE id = '$id'"))) {
      $oldtext=mysql_fetch_assoc(mysql_query("SELECT text FROM games WHERE id = '$id'"));
      $olddate=mysql_fetch_assoc(mysql_query("SELECT date FROM games WHERE id = '$id'"));
      $oldtime=mysql_fetch_assoc(mysql_query("SELECT time FROM games WHERE id = '$id'"));
      $oldtext=$oldtext['text'];
      $olddate=$olddate['date'];
      $oldtime=$oldtime['time'];
      if($oldtext==$text && $olddate==$date && substr($oldtime,0,5)==$time){
        print_r("Nothing Changed on '$id' <br>");
      }
      else{
        print_r("Updating Existing: '$id' <br>");
        mysql_query("UPDATE games SET text='$text' WHERE id = '$id'");
        mysql_query("UPDATE games SET date='$date' WHERE id = '$id'");
        mysql_query("UPDATE games SET time='$time' WHERE id = '$id'");
        mysql_query("UPDATE games SET status='2' WHERE id = '$id'");
      }
    }
    else{
    mysql_query("INSERT INTO games (`id`, `text`, `date`, `time`, `status`) VALUES ('$id', '$text', '$date', '$time','1')");
    print_r("Adding '$id' <br>");
    } 
  }   
  else
  {
  $id=$cols->item(0)->nodeValue;
  print_r("Game '$id' is an Away-Game.. Skipping<br>");
  }
      
}

}
?>
