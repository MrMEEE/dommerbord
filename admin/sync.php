<?php

require("connect.php");
include("config.php");
require_once("checkAdmin.php");
require("theme.php");
require("commonFunctions.php");

$courts = getCourts($klubid);

session_start();

if($debug==0){
  error_reporting(0);
}

getThemeHeader();

getThemeTitle("Dommerplan - Synk");

require("menu.php");

mysql_query("UPDATE `config` set lastupdated=now() WHERE id='1'");

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

if(!mysql_num_rows(mysql_query("SELECT * FROM teams WHERE name = 'DBBF'"))){

  mysql_query("INSERT INTO teams SET name='DBBF'");

}

$dbbfentry=mysql_fetch_assoc(mysql_query("SELECT * FROM teams WHERE name = 'DBBF'"));

$dbbfid=$dbbfentry['id'];

while($icals=mysql_fetch_assoc($calendars)){
	$currentteam=$icals['team'];
	$gamechanged=0;
	echo "<br>Opdaterer: $currentteam<br>";
	if($debug!=0){
		echo $icals['address'];
		echo "<br>";
	}
	$teamid = $icals['id'];
	// new dom object  
	$dom = new DOMDocument();  
	//load the html  
	$content  = file_get_contents($icals['address']);
	$page = '<html>
		<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<title>Dommer Sync</title>
		</head>
		<body></body>
		</html>';
	$page .= $content;
	$html = $dom->loadHTML($page);

	//discard white space   
	$dom->preserveWhiteSpace = false;   
  
	//the table by its tag name  
	$tables = $dom->getElementsByTagName('table');
  
	$info = $dom->getElementsByTagName('h2');
   
	$pulje = explode(", ",$info->item(0)->nodeValue);
  
	//get all rows from the table  
	if ( $tables->length > 1){
		$rows = $tables->item(1)->getElementsByTagName('tr');   
	}else{
		$rows = $tables->item(0)->getElementsByTagName('tr');
	}
	// loop over the table rows  
	foreach ($rows as $row){   
		// get each column by tag name  
		$cols = $row->getElementsByTagName('td');   
		$hometeam = $cols->item(2)->nodeValue;
		$hometeam = str_replace("\n", "", $hometeam);
		$hometeam = str_replace("\r", "", $hometeam);
		$awayteam = $cols->item(3)->nodeValue;
		$awayteam = str_replace("\n", "", $awayteam);
		$awayteam = str_replace("\r", "", $awayteam);
		$awayteam = trim($awayteam);
		$place = $cols->item(4)->nodeValue;
		$place = trim($place);
		$place = str_replace("  ", "", $place);
		$result = $cols->item(5)->nodeValue;
		$result = trim($result);
		$result = str_replace("  ", "", $result);
		$status = $cols->item(6)->nodeValue;   
		$status = str_replace("\n", "", $status);      
		$status = str_replace("\r", "", $status);      
		$status = str_replace(" ", "", $status);
	      
		if($status=="UDS"){
		    $status=4;
		}
	      
		$awayteam_mod = trim($awayteam);
		$awayteam_mod = explode(" ",$awayteam_mod);
		$hometeam_mod = trim($hometeam);
		$klubnavn_mod = trim($klubnavn);
		$hometeam_mod = explode(" ",$hometeam_mod);
		$klubnavn_mod = explode(" ",$klubnavn_mod);   

		if(in_array(str_replace(array("\r\n","\r"),"",strtok($place, "\n")),$courts)){
		        $athome=1;
		}else{                                
			$athome=0;                                                        
		}

		if(($hometeam_mod[0] == $klubnavn_mod[0]) || ($awayteam_mod[0] == $klubnavn_mod[0]) || ($athome == 1)){
			$id=$cols->item(0)->nodeValue;
			$id=str_replace("\n", "", $id);
			$id=str_replace("\r", "", $id);
			$id=str_replace(" ", "", $id);
			$basketid=getGame($id);
			$dom2 = new DOMDocument();
		      
			$content2 = file_get_contents("http://resultater.basket.dk/tms/Turneringer-og-resultater/Kamp-Information.aspx?KampId=$basketid");
			$page2 = '<html><head>
				<meta http-equiv="content-type" content="text/html; charset=utf-8">
				<title>Dommer Sync</title>
				</head><body></body></html>';
		      
			$page2 .= $content2;
		      
			if(strstr($page2, "1. dommer")){
				$html2 = $dom2->loadHTML($page2);
				$dom2->preserveWhiteSpace = false;
				$tables2 = $dom2->getElementsByTagName('table');
				$rows2 = $tables2->item(0)->getElementsByTagName('tr');
				$refrow1 = $rows2->item(11)->getElementsByTagName('td');
				$ref1 = $refrow1->item(1)->nodeValue;
				$updatequery = "UPDATE games SET refereeteam1id='$dbbfid',referee1name='$ref1'";
				
				if(strstr($page2, "2. dommer")){
					$refrow2 = $rows2->item(12)->getElementsByTagName('td');
					$ref2 = $refrow2->item(1)->nodeValue;
					$updatequery .= ",refereeteam2id='$dbbfid',referee2name='$ref2'";
				}
				$updatequery .=" WHERE id='$id'";
				mysql_query($updatequery);
			}
		      
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
			$text = $hometeam." : ".$currentteam.", ".$pulje[1];
			$text .= "<br>Mod ";
			$text .= $awayteam;
			//echo "!!!".str_replace(array("\r\n","\r"),"",strtok($place, "\n"))."!!!";
			
			/*if(in_array(str_replace(array("\r\n","\r"),"",strtok($place, "\n")),$courts)){
				$athome=1;
			}else{
				$athome=0;
			}
			
			/*if($hometeam_mod[0] == $klubnavn_mod[0]){
				$athome=1;
			}else{
				$athome=0;
			}*/

			if(mysql_num_rows(mysql_query("SELECT id FROM games WHERE id = '$id'"))) {
				mysql_query("UPDATE `games` set place='$place' WHERE id='$id'");
				$query=mysql_fetch_assoc(mysql_query("SELECT * FROM games WHERE id = '$id'"));
				$oldtext=$query['text'];
				$olddate=$query['date'];
				$oldtime=$query['time'];
				$oldathome=$query['homegame'];
				$oldteamid=$query['team'];
				$oldresult=$query['result'];
				if($oldtext==$text && $olddate==$date && substr($oldtime,0,5)==$time && $oldathome==$athome && $oldteamid==$teamid && $oldresult==$result && $status!=4){
					mysql_query("UPDATE `games` set dt_added=now() WHERE id='$id'");
					if($debug!=0){
						print_r("Nothing Changed on '$id' <br>");
					}
				}else{
					if($oldtext!=$text && $olddate==$date && substr($oldtime,0,5)==$time){
						// Info changed
						print_r("Opdatere Info om kamp: '$id' <br>");
					}else{
						print_r("Ændring til kamp: '$id' <br>");
						$gamechanged=1;
					}
					if($status != 4){
						if($gamechanged){
							mysql_query("UPDATE games SET status='2' WHERE id = '$id'");
						}
					}else{
						if($gamechanged){
							mysql_query("UPDATE games SET status='$status' WHERE id = '$id'");
						}
					}
					mysql_query("UPDATE games SET text='$text',date='$date',time='$time',dt_added=now(),homegame='$athome',team='$teamid',result='$result' WHERE id = '$id'");
				}
			}else{
				if($status!=6){
					$status=1;
				}
				mysql_query("INSERT INTO games (`id`, `text`, `date`, `time`, `status`, `tableteam3id`, `place`, `homegame`,`team`) VALUES ('$id', '$text', '$date', '$time','$status',9999,'$place','$athome','$teamid')");
				if($athome){
					print_r("Tilføjer Hjemmekamp: '$id' <br>");
				}else{
					print_r("Tilføjer Udekamp: '$id' <br>");
				}
				$gamechanged=1;
			} 
		}      
	}

	if($gamechanged==0){
		echo "Ingen Ændringer.<br><br>";
	}

}

$config = mysql_fetch_assoc(mysql_query("SELECT * FROM config WHERE id = '1'"));

$lastupdated = $config['lastupdated'];
echo $lastupdated;

mysql_query("UPDATE `games` set status='3' WHERE dt_added < '$lastupdated' AND position != 9999999");

getThemeBottom();

?>

