<?php


error_reporting(0);

if(isset($_GET["HoldId"])){
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=calendar.ics');
$HoldId=$_GET["HoldId"];
  // new dom object  
  $dom = new DOMDocument();  
  
  //load the html  
 $content        = file_get_contents("http://resultater.basket.dk/tms/Turneringer-og-resultater/Hold-Kampprogram.aspx?HoldId=$HoldId");

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
  $dom->preserveWhiteSpace = false;   
  
  //the table by its tag name  
  $tables = $dom->getElementsByTagName('table');   
  
  //get all rows from the table  
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
   // echo the values    
      $id=$cols->item(0)->nodeValue;
      $id=str_replace("\n", "", $id);
      $id=str_replace("\r", "", $id);
      $id=str_replace(" ", "", $id);
      
      $fulldate= $cols->item(1)->nodeValue;
      $fulldate = str_replace("\n", "", $fulldate);
      $fulldate = str_replace("\r", "", $fulldate);
      $fulldate = str_replace(" ", "", $fulldate );

      $year = "20";
      $year .= substr($fulldate,6,2);
      $month = substr($fulldate,3,2);
      $day = substr($fulldate,0,2);
      $time = substr($fulldate,13,2);
      $time .= substr($fulldate,16,2);
      $endtime = (int)$time;
      $endtime = $endtime + 200;
        $text = $hometeam;
        $text .= " Vs. ";
        $text .= $awayteam;

$ical = "BEGIN:VCALENDAR\r
VERSION:2.0\r
PRODID:-//hacksw/handcal//NONSGML v1.0//EN\r
CALSCALE:GREGORIAN\r
BEGIN:VTIMEZONE\r
TZID:Europe/Copenhagen\r
X-LIC-LOCATION:Europe/Copenhagen\r
BEGIN:DAYLIGHT\r
TZOFFSETFROM:+0100\r
TZOFFSETTO:+0200\r
TZNAME:GMT\r
DTSTART:19700329T020000\r
RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=3\r
END:DAYLIGHT\r
BEGIN:STANDARD\r
TZOFFSETFROM:+0200\r
TZOFFSETTO:+0100\r
TZNAME:GMT\r
DTSTART:19701025T030000\r
RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=10\r
END:STANDARD\r
END:VTIMEZONE\r
BEGIN:VEVENT\r
UID:" . md5(uniqid(mt_rand(), true)) . "@basket.dk\r
DTSTAMP:".$year."".$month."".$day."T".$time."00\r
DTSTART:".$year."".$month."".$day."T".$time."00\r
DTEND:".$year."".$month."".$day."T".$endtime."00\r
SUMMARY: $text\r
END:VEVENT\r
END:VCALENDAR\r\n";
      
echo $ical;        
}

}elseif(isset($_GET["refId"])){
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=calendar.ics');

if(!file_exists("admin/connect.php")){
 ob_start();
 header( "Location: admin/setup.php" );
 ob_flush();
}
   
require "admin/config.php"; 
require "admin/connect.php";

$query = mysql_query("SELECT * FROM `games` WHERE (`refereeteam1id` = ".$_GET['refId']." OR `refereeteam2id` = ".$_GET['refId']." OR `tableteam1id` = ".$_GET['refId']." OR `tableteam2id` = ".$_GET['refId']." OR `tableteam3id` = ".$_GET['refId'].") ORDER BY `date`,`time` ASC");

while($row = mysql_fetch_assoc($query)){
  $fulldate=$row['date'];
  $fulltime=$row['time'];
  $refs = ($row['refereeteam1id'] == $_GET["refId"]) + ($row['refereeteam2id'] == $_GET["refId"]);
  $tables = ($row['tableteam1id'] == $_GET["refId"]) + ($row['tableteam2id'] == $_GET["refId"]) + ($row['tableteam3id'] == $_GET["refId"]);
  $text = "";
  if(($refs > 0) || ($tables>0)){
    $year = substr($fulldate,0,4);
    $month = substr($fulldate,5,2);
    $day = substr($fulldate,8,2);
    $time = substr($fulltime,0,2);
    $time .= substr($fulltime,3,2);
    $endtime = (int)$time;
    $endtime = $endtime + 200;
    if($refs>0){
      $text .= $refs." x Dommer, ";
    }
    if($tables>0){
      $text .= $tables." x Dommerbord, ";
    }
    $text .= "til: ".$row['text'];
echo "BEGIN:VCALENDAR\r
VERSION:2.0\r
PRODID:-//hacksw/handcal//NONSGML v1.0//EN\r
CALSCALE:GREGORIAN\r
BEGIN:VTIMEZONE\r
TZID:Europe/Copenhagen\r
X-LIC-LOCATION:Europe/Copenhagen\r
BEGIN:DAYLIGHT\r
TZOFFSETFROM:+0100\r
TZOFFSETTO:+0200\r
TZNAME:GMT\r
DTSTART:19700329T020000\r
RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=3\r
END:DAYLIGHT\r
BEGIN:STANDARD\r
TZOFFSETFROM:+0200\r
TZOFFSETTO:+0100\r
TZNAME:GMT\r
DTSTART:19701025T030000\r
RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=10\r
END:STANDARD\r
END:VTIMEZONE\r
BEGIN:VEVENT\r
UID:" . md5(uniqid(mt_rand(), true)) . "@basket.dk\r
DTSTAMP:".$year."".$month."".$day."T".$time."00\r
DTSTART:".$year."".$month."".$day."T".$time."00\r
DTEND:".$year."".$month."".$day."T".$endtime."00\r
SUMMARY: $text\r
END:VEVENT\r
END:VCALENDAR\r\n";
}
}

}else{
if(!file_exists("admin/connect.php")){
 ob_start();
 header( "Location: admin/setup.php" );
 ob_flush();
}

require "admin/config.php";   
require "admin/connect.php";
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-blog-header.php')){
  require($_SERVER['DOCUMENT_ROOT'].'/wp-blog-header.php');
  get_header();
  if ( file_exists( TEMPLATEPATH . '/sidebar2.php') )
    load_template( TEMPLATEPATH . '/sidebar2.php');
  else
    load_template( ABSPATH . 'wp-content/themes/default/sidebar.php');
}else{
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'.$klubnavn.' Kamp Kalendere</title>

<!-- Including the jQuery UI Human Theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/humanity/jquery-ui.css" type="text/css" media="all" />

<!-- Our own stylesheet -->
<link rel="stylesheet" type="text/css" href="admin/styles.css" />

</head>

<body>

<h1>'.$klubnavn.' Kamp Kalendere</h1>

<div id="main" align="center">

<br>';
}
echo '<table>
<tr>
<td>Kamp Kalendere</td>
<td>Dommer/Dommerplans Kalendere</td>
</tr>
<tr>
<td VALIGN="top" width=300>';

$url = "http://resultater.basket.dk/tms/Turneringer-og-resultater/Forening-Holdoversigt.aspx?ForeningsId=$klubid";
$input = @file_get_contents($url) or die("Could not access file: $url");
$regexp = "HoldId=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";  
$regexp2 = "RaekkeId=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";

preg_match_all("/$regexp/siU", $input, $matches);
preg_match_all("/$regexp2/siU", $input, $matches2);

$i=0;
foreach ($matches[2] as $urls){
  $name=$matches2[3][$i];
  
  echo '<a href="http://'.$klubadresse.$klubpath.'/ical.php?HoldId='.$urls.'">'.$name.'</a>';
  echo '<br>';
  
  $i=$i+1;  
}      
echo '</td><td VALIGN="top" width=300>';
require("admin/connect.php");
$query = mysql_query("SELECT * FROM `teams` ORDER BY `name` ASC");
if(mysql_num_rows(mysql_query("SELECT * FROM `teams` ORDER BY `name` ASC"))){
  while($row = mysql_fetch_assoc($query)){
    if($row['name']!="-"){
      echo '<a href="http://'.$klubadresse.$klubpath.'/ical.php?refId='.$row["id"].'">'.$row["name"].'</a><br>';
    }
  }
}

echo '</td>';
if (file_exists($_SERVER['DOCUMENT_ROOT'].'/wp-blog-header.php')){
  get_sidebar();
  get_footer(); 
}else{
  echo '</div><br></body>
  </html>';
}
}

?>
