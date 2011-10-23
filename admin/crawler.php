<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://www.infosport.dk/cgi-bin/MMS.dbbf/puljer_klub.hms?kn=1100&navn=BMS&fb=DBBF");
curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
$result=curl_exec ($ch);
curl_close ($ch);
// Search The Results From The Starting Site
if( $result )
{
// I LOOK ONLY FROM TOP domains change this for your usage
//preg_match_all( '/<a href="(kprogram[^0-9].+?|stilling[^0-9].+?)"/', $result, $output, PREG_SET_ORDER );
preg_match_all( '/<a href="(stilling[^0-9].+?)"/', $result, $output, PREG_SET_ORDER );
foreach( $output as $item )

{
// ALL LINKS DISPLAY HERE

//print_r(urldecode($item[1]));
//print_r("\n");

$url1 = str_replace("amp;", "", $item[1]);

//print_r("http://www.infosport.dk/cgi-bin/MMS.dbbf/$url1 \n");

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://www.infosport.dk/cgi-bin/MMS.dbbf/$url1");
curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
$result=curl_exec ($ch);
curl_close ($ch);

preg_match_all( '/<a href="(kprogram.hms\?hnr=BMS[^0-9].+?)"/', $result, $output2, PREG_SET_ORDER );

foreach( $output2 as $item2 )
{
  $url2 = str_replace("amp;", "", $item2[1]);
  //print_r("http://www.infosport.dk/cgi-bin/MMS.dbbf/$url2 \n");
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,"http://www.infosport.dk/cgi-bin/MMS.dbbf/$url2");
  curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
  curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  $result=curl_exec ($ch);
  curl_close ($ch);
  
  preg_match_all( "/<a href='(kalen[^0-9].+?)'/", $result, $output3, PREG_SET_ORDER );
  foreach( $output3 as $item3 )
  {
    $url3 = str_replace("amp;", "", $item3[1]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"http://www.infosport.dk/cgi-bin/MMS.dbbf/$url3");
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    $result=curl_exec ($ch);
    curl_close ($ch);
    
    preg_match_all( '/a href = "webcal:\/\/([^0-9].+?)"/', $result, $output4, PREG_SET_ORDER );
    foreach( $output4 as $item4 ){
      $icalurl = str_replace("amp;", "", "http://$item4[1]");
      $teamnamepos = strpos($icalurl,"hn=");
      $teamnamepos2 = strpos($icalurl,"&dnr");
      
      $teamname=substr($icalurl,strpos($icalurl,"hn=")+3,strpos($icalurl,"&dnr")-strpos($icalurl,"hn=")-3);
      $teamname=urldecode(str_replace("%C62","Æ",str_replace("%C61", "Æ", $teamname)));
      print_r(quoted_printable_decode("$teamname \n"));
      print_r("$icalurl \n");
    }
  }
}



// NOW YOU ADD IN YOU DATABASE AND MAKE A LOOP TO ENGINE NEVER STOP


}

}
?>