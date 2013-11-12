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

function getTeamNames(){


include("config.php");
foreach ($klubids as $clubid){

$url = "http://resultater.basket.dk/tms/Turneringer-og-resultater/Forening-Holdoversigt.aspx?ForeningsId=".$clubid;

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

$xpath = new DOMXPath($dom);
    
$tags = $xpath->query("//a[contains(@id,'hlTeam')]");

foreach($tags as $tag){
  if(!in_array(trim($tag->nodeValue),$teams)){
    $teams[] = trim($tag->nodeValue);
  }


}

}

return $teams;

}

function userCheckRights($user){
   
   $currentuser = mysql_fetch_assoc(mysql_query("SELECT * FROM `users` WHERE `name`='".$_SESSION['username']."'"));
   
   if ((checkAdmin($_SESSION['username'])) || ($currentuser['id'] == $user)){
      return 1;
   }else{
      return 0;
   }

}

function userVerifyPassword($password1,$password2){

   if ($password1 == $password2) {
         return $password1;
   }elseif ($password1 == "" || $password2 == ""){
         return 1;
   }else{
         return 0;
   }

}

function userChangePassword($user,$password){

  if (userCheckRights($user)){
     mysql_query("UPDATE `users` SET `password` = md5('$password') WHERE `id` = '$user'");  
     return "Kode Ændret!";
  }else{
     return "Du har ikke rettigheder til at ændre koden!";      
  }

}

function userAdd($user,$password,$admin){
  if (userCheckRights($user)){
     if(mysql_num_rows(mysql_query("SELECT * FROM users WHERE `name` = '$user'"))) {
         return "Brugeren eksistere allerede!";
     }else{
         mysql_query("INSERT INTO `users` (`name`,`password`,`admin`) VALUES ('$user',md5('$password'),'$admin')");
         return "Brugeren blev oprettet.";
     }
  }else{
     return "Du er ikke administrator!!";
  }
}

function userDelete($user){
  if (userCheckRights($user)){
       if(mysql_num_rows(mysql_query("SELECT * FROM users WHERE `id` = '$user'"))){
           if($deluser == 1){
               return "Adminbrugeren kan ikke slettes!!";
           }else{
               mysql_query("DELETE FROM `users` WHERE `id` = '$user'");
               return "Brugeren blev slettet.";
           }
       }else{
           return "Brugeren eksistere ikke!!";
       }
  }
}

function userChangeAdmin($user){
   if (userCheckRights($user)){
       if(mysql_num_rows(mysql_query("SELECT * FROM users WHERE `id` = '$user'"))){
           if($user == 1){
               return "Adminbrugerens rettigheder kan ikke ændres!!";
           }else{
               $oldadmin = mysql_fetch_assoc(mysql_query("SELECT admin FROM users WHERE id = '$user'"));
               $oldadmin = $oldadmin['admin'];
               if($oldadmin == 0){
                  $admin = 1;
               }else{
                  $admin = 0;
               }
               mysql_query("UPDATE `users` SET `admin` = '$admin' WHERE `id` = '$user'");
           }
       
       }
   }
}

function checkAdmin($username){
   $user=mysql_query("SELECT * FROM users WHERE name = '$username'");

   if(mysql_num_rows($user)){
      $user = mysql_fetch_assoc($user);
      $admin = $user['admin'];
   }else{
      $admin = 0;
   }

   return $admin;
}


?>
