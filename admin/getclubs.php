<?php

require("config.php");

if($debug==0){
error_reporting(0);
}


function getClubs(){

$url = "http://resultater.basket.dk/tms/Turneringer-og-resultater/Soegning.aspx";

$viewstate = "%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jZUhvbGRlcjEkU29lZ25pbmckQ2x1Ykxpc3QkZ3ZDbHViTGlzdA9nZAUeX19Db250cm9sc1JlcXVpcmVQb3N0QmFja0tleV9fFgkFKWN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkU29lZ25pbmckcmJSb3dzBSljdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJFNvZWduaW5nJHJiQ2x1YgUpY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRTb2VnbmluZyRyYkNsdWIFLGN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkU29lZ25pbmckcmJTdGFkaXVtBSxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJFNvZWduaW5nJHJiU3RhZGl1bQUqY3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRTb2VnbmluZyRyYk1hdGNoBSpjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJFNvZWduaW5nJHJiTWF0Y2gFLGN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkU29lZ25pbmckcmJSZWZlcmVlBSxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJFNvZWduaW5nJHJiUmVmZXJlZQU8Y3RsMDAkQ29udGVudFBsYWNlSG9sZGVyMSRTb2VnbmluZyRTdGFkaXVtTGlzdCRndlN0YWRpdW1MaXN0D2dkBTxjdGwwMCRDb250ZW50UGxhY2VIb2xkZXIxJFNvZWduaW5nJFJlZmVyZWVMaXN0JGd2UmVmZXJlZUxpc3QPZ2QFQGN0bDAwJENvbnRlbnRQbGFjZUhvbGRlcjEkU29lZ25pbmckQ29tbWl0dGVlTGlzdCRndkNvbW1pdHRlZUxpc3QPZ2T4rw74aMNUTCzfeajczzrWXz%2FyKXb6ck1nn0cFGMisoA%3D%3D";
$eventtarget = "";
$eventargument = "";
$eventvalidation = "%2FwEWJAKjmaOECQKeq%2BOGDALJ1JGYBwKyhL3FCALcrbawAwLHvJ3HBgLEwpqGDgLLpZO8AgLKpZO8AgLmqNOCDALnqNOCDALmqLeBDALkqNOCDALlqNOCDALiqNOCDALjqNOCDALgqNOCDALxqNOCDAL%2BqNOCDALmqJOBDALmqJ%2BBDALmqJuBDALmqKeBDALmqKuBDAK7xKn4CAKFgKyhCAKgkYrkAgLK0p%2BjDwLQ%2FYOnDAKHxMqBDwLWypCpCQKS6Oa2DQLGuMf7AQLkwrbPAgLF5dmPCgKs7J3ZDpx4JmcamFtEqFAoj1Y%2BKJf34gVLrxSgkmyAP3Dczdkm"; 

$fields = array(
            '__VIEWSTATE'=>$viewstate,
            '__EVENTTARGET'=>$eventtarget,
            '__EVENTARGUMENT'=>$eventargument,
            '__EVENTVALIDATION'=>$eventvalidation,
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
            'ctl00%24ContentPlaceHolder1%24Soegning%24txtRefereeName'=>''
            
);

foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
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
$html = $dom->loadHTML($page);

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