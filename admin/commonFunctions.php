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


?>