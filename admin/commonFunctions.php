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

$gyms = mysql_fetch_assoc(mysql_query("SELECT * FROM `config` WHERE `id`='1'"));
$gyms = explode(",",$gyms['gyms']);

foreach ($gyms as $gym){

  $courts[] = trim($gym);

}

return $courts;

}

function setBasketDKValidation(){

$info = explode("<br>",file_get_contents("http://www.dommerplan.dk/info.php"));

$validation = array('url' => "http://resultater.basket.dk/tms/Turneringer-og-resultater/Soegning.aspx",
		    'viewstate' => $info[0],
		    'eventtarget' => "",
		    'eventargument' => "",
		    'eventvalidation' => $info[1]
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

function getAllCourts(){

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
            'ctl00%24ContentPlaceHolder1%24Soegning%24Search'=>'rbStadium',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtSelectedCenterSearchModule'=>'3',
            'ctl00%24ContentPlaceHolder1%24Soegning%24ddlGender'=>'1',
            'ctl00%24ContentPlaceHolder1%24Soegning%24ddlDivision'=>'2',
            'ctl00%24ContentPlaceHolder1%24Soegning%24ddlSeason'=>'0',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtClubName'=>'',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtStadiumName'=>'',
            'ctl00%24ContentPlaceHolder1%24Soegning%24btnSearchStadium'=>'S%C3%B8g',
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtCommitteeName'=>'',
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

$xpath = new DOMXPath($dom);

$tags = $xpath->query("//a[contains(@id,'hlStadium')]");

foreach($tags as $tag){

$courts[] = trim($tag->nodeValue);

}

return $courts;

curl_close($ch);

}

?>
