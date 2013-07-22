<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkAdmin.php");

require("bballtickets_functions.php");


if(!file_exists("barcodes/".$_GET["id"].".jpg")){
      generateBarcode($_GET["id"]);
}

echo '<img src="barcodes/'.$_GET["id"].'.jpg">';

?>