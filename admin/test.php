<?php
require_once 'Mobile-Detect/Mobile_Detect.php';
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

echo $detect->isMobile();

require("config.php");
require("connect.php");

$config=mysql_fetch_assoc(mysql_query("SELECT * FROM config WHERE id = '1'"));

if($detect->isMobile()){

    ob_start();
    header("Location: http://" . $mobileaddress . "/");
    ob_flush();
            
}
            

?>