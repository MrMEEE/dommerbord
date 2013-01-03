<?php

function convertDate($date){
 $newdate = substr($date,6,4);
 $newdate .= "-";
 $newdate .= substr($date,3,2);
 $newdate .= "-";
 $newdate .= substr($date,0,2);

 return $newdate;
}

function getCourts($club){

$address = "http://resultater.basket.dk/tms/Turneringer-og-resultater/Forening-Information.aspx?ForeningsId=$club";

$content  = file_get_contents($address);

$dom = new DOMDocument();

$page = '<html>
         <head> 
         <meta http-equiv="content-type" content="text/html; charset=utf-8">
         <title>Dommer Sync</title>
         </head>
         <body></body>
         </html>';

$page .= $content;
$html = $dom->loadHTML($page);

$xpath = new DOMXPath($dom);

$tags = $xpath->query('//div[@id="ctl00_ContentPlaceHolder1_Forening1_pnlStadium"]/table/tr/td/table/tr/td/a[@title="Information for spillestedet"]');
foreach ($tags as $tag) {
    $courts[] = (trim($tag->nodeValue));
}

return $courts;

}

function setBasketDKValidation(){

$validation = array('url' => "http://resultater.basket.dk/tms/Turneringer-og-resultater/Soegning.aspx",
		    'viewstate' => "%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BDxYEHwVlHwBoZAIDD2QWAmYPPCsAEQEBEBYAFgAWAGQCBQ9kFgJmDzwrABEBARAWABYAFgBkAgcPZBYCZg88KwARAQEQFgAWABYAZAIJD2QWAmYPPCsAEQEBEBYAFgAWAGQCDQ9kFgJmDzwrABEBARAWABYAFgBkGAYFNGN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkU29lZ25pbmckUm93TGlzdCRndlJvd0xpc3QPZ2QFNmN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkU29lZ25pbmckQ2x1Ykxpc3QkZ3ZDbHViTGlzdA9nZAUeX19Db250cm9sc1JlcXVpcmVQb3N0QmFja0tleV9fFgkFKWN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkU29lZ25pbmckcmJSb3dzBSljdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJFNvZWduaW5nJHJiQ2x1YgUpY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRTb2VnbmluZyRyYkNsdWIFLGN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkU29lZ25pbmckcmJTdGFkaXVtBSxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJFNvZWduaW5nJHJiU3RhZGl1bQUqY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRTb2VnbmluZyRyYk1hdGNoBSpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJFNvZWduaW5nJHJiTWF0Y2gFLGN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkU29lZ25pbmckcmJSZWZlcmVlBSxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJFNvZWduaW5nJHJiUmVmZXJlZQU8Y3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRTb2VnbmluZyRTdGFkaXVtTGlzdCRndlN0YWRpdW1MaXN0D2dkBTxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJFNvZWduaW5nJFJlZmVyZWVMaXN0JGd2UmVmZXJlZUxpc3QPZ2QFQGN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkU29lZ25pbmckQ29tbWl0dGVlTGlzdCRndkNvbW1pdHRlZUxpc3QPZ2QzLLJl5PGHSdCl9xU8nYApEahj18J1x3g2J4zoGssZ9g%3D%3D",
		    'eventtarget' => "",
		    'eventargument' => "",
		    'eventvalidation' => "%2FwEWKgLHyaWUBAKeq%2BOGDALJ1JGYBwKyhL3FCALcrbawAwLHvJ3HBgLEwpqGDgLLpZO8AgLKpZO8AgLmqNOCDALnqNOCDALmqPOCDALmqLeBDALkqNOCDALlqNOCDALiqNOCDALjqNOCDALgqNOCDALxqNOCDAL%2BqNOCDALmqJOBDALmqJ%2BBDALmqJuBDALmqKeBDALmqKuBDALmqP%2BCDAK7xKn4CALovNTXBQKFgKyhCAKgkYrkAgLK0p%2BjDwLQ%2FYOnDAKHxMqBDwLWypCpCQKS6Oa2DQLGuMf7AQLkwrbPAgLF5dmPCgKs7J3ZDgKI%2FMHvCQKW%2FMHvCQLe6q8bzQtoreFheuHIQsGnLR7o4wPtYN%2FgjmMQUlHttz9xaI8%3D"
		    );

return $validation;

}

function getGame($gameid){

require_once("config.php");

if($debug==0){
   error_reporting(0);
}

$validation = setBasketDKValidation();

$fields = array(
            '__VIEWSTATE'=>$validation['viewstate'],
            '__EVENTTARGET'=>$validation['eventtarget'],
            '__EVENTARGUMENT'=>$validation['eventargument'],
            '__EVENTVALIDATION'=>$validation['eventvalidation'],
            'ctl00%24ContentPlaceHolder1%24Soegning%24Search'=>'rbMatch',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtSelectedCenterSearchModule'=>'4',
            'ctl00%24ContentPlaceHolder1%24Soegning%24ddlGender'=>'1',
            'ctl00%24ContentPlaceHolder1%24Soegning%24ddlDivision'=>'2',
            'ctl00%24ContentPlaceHolder1%24Soegning%24ddlSeason'=>'0',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtClubName'=>'',
            'ctl00%24ContentPlaceHolder1%24Soegning%24btnSearchClub'=>'S%C3%B8g',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtStadiumName'=>'',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtCommitteeName'=>'',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtMatchNumber'=>$gameid,
            'ctl00%24ContentPlaceHolder1%24Soegning%24btnSearchMatchNumber'=>'S%C3%B8g',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtRefereeName'=>'',
            'ctl00%24ContentPlaceHolder1%24Soegning%24ddlTournaments_Tournament'=>'0'
);

$url = $validation['url'];

foreach($fields as $key=>$value){
	$fields_string .= $key.'='.$value.'&';
}

rtrim($fields_string,'&');

$ch = curl_init();

curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_HTTPHEADER,Array("Content-Type: application/x-www-form-urlencoded")); 
curl_setopt($ch,CURLOPT_TIMEOUT,5);


$result = curl_exec($ch);

$dom = new DOMDocument();

//load the html
$page = '
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Dommer Sync</title>
</head>
<body></body>
</html>
';

$page .= $result;
$html = @$dom->loadHTML($page);

$dom->preserveWhiteSpace = false;


$tables = $dom->getElementsByTagName('table');

$rows = $tables->item(0)->getElementsByTagName('tr');

foreach ($rows as $row){

	$cols = $row->getElementsByTagName('a');

	$clubnames[] = trim($cols->item(1)->nodeValue);

	unset($colsarray);

	foreach ($cols as $col){

		$colsarray[] = $col->getAttribute('href');

	}

	$id=explode("=",$colsarray[0]);

	$clubids[] = $id[1];

	return $id[1];

}

curl_close($ch);

}

function getClubs(){

require_once("config.php");
  
if($debug==0){
	error_reporting(0);
}

$validation = setBasketDKValidation();

$url = $validation['url'];

$fields = array(
            '__VIEWSTATE'=>$validation['viewstate'],
            '__EVENTTARGET'=>$validation['eventtarget'],
            '__EVENTARGUMENT'=>$validation['eventargument'],
            '__EVENTVALIDATION'=>$validation['eventvalidation'],
            'ctl00%24ContentPlaceHolder1%24Soegning%24Search'=>'rdClub',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtSelectedCenterSearchModule'=>'2',
            'ctl00%24ContentPlaceHolder1%24Soegning%24ddlGender'=>'1',
            'ctl00%24ContentPlaceHolder1%24Soegning%24ddlDivision'=>'2',
            'ctl00%24ContentPlaceHolder1%24Soegning%24ddlSeason'=>'0',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtClubName'=>'',
            'ctl00%24ContentPlaceHolder1%24Soegning%24btnSearchClub'=>'S%C3%B8g',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtStadiumName'=>'',
            'ctl00%24ContentPlaceHolder1%24Soegning%24CommitteeName'=>'',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtMatchNumber'=>'',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtRefereeName'=>'',
            'ctl00%24ContentPlaceHolder1%24Soegning%24ddlTournaments_Tournament'=>'0'

);

foreach($fields as $key=>$value){ 
        $fields_string .= $key.'='.$value.'&'; 
}

rtrim($fields_string,'&');

$ch = curl_init();

curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_HTTPHEADER,Array("Content-Type: application/x-www-form-urlencoded")); 
curl_setopt($ch,CURLOPT_TIMEOUT,5);


$result = curl_exec($ch);

$dom = new DOMDocument();
    
//load the html  
$page = '
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Dommer Sync</title>
</head>
<body></body>
</html>
';

$page .= $result;
$html = @$dom->loadHTML($page);

$dom->preserveWhiteSpace = false;

$tables = $dom->getElementsByTagName('table');

$rows = $tables->item(0)->getElementsByTagName('tr');

foreach ($rows as $row){

        $cols = $row->getElementsByTagName('a');

        $clubnames[] = trim($cols->item(1)->nodeValue);

        unset($colsarray);

        foreach ($cols as $col){
        
                $colsarray[] = $col->getAttribute('href');

        }

        $id=explode("=",$colsarray[0]);

        $clubids[] = $id[1];

}


curl_close($ch);

return array ($clubnames,$clubids);

}

?>
