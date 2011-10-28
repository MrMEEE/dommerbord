<?php


error_reporting(0);
//set correct content-type-header
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=calendar.ics');
$HoldId=$_GET["HoldId"];
  // new dom object  
  $dom = new DOMDocument();  
  
  //load the html  
 $content        = file_get_contents("http://resultater.basket.dk/tms/Turneringer-og-resultater/Hold-Kampprogram.aspx?HoldId=$HoldId");
//$content        = file_get_contents("http://resultater.basket.dk/tms/Turneringer-og-resultater/Hold-Kampprogram.aspx?HoldId=123");

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
//#DTSTAMP:" . gmdate('Ymd').'T'. gmdate('His') . "Z\r


?>
