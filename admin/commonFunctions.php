<?php

function convertDate($date){
 $newdate = substr($date,6,4);
 $newdate .= "-";
 $newdate .= substr($date,3,2);
 $newdate .= "-";
 $newdate .= substr($date,0,2);

 return $newdate;
}



?>