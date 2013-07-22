<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkAdmin.php");

function generateBarcode($id){

      require("../../config.php");
      $url = "http://" . $klubadresse . $klubpath . "/admin/plugins/bballtickets/includes/barcode.php?encode=CODE39&bdata=".$id."&height=50&scale=2&bgcolor=%23FFFFFF&color=%23000000&file=&type=jpg&Genrate=Submit";
      file_put_contents("./barcodes/".$id.".jpg",file_get_contents($url));

}

if(isset($_GET['ticketid'])){
        $ticket = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_tickets` WHERE `id`='".$_GET['ticketid']."'"));
        $type = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_tickettypes` WHERE `id`='".$ticket['type']."'"));
        $cardcode = trim(str_pad((int) $ticket['type'],"4","0",STR_PAD_LEFT).str_pad((int) $ticket['id'],"10","0",STR_PAD_LEFT));

        if(!file_exists("barcodes/".$cardcode.".jpg")){
                generateBarcode($cardcode);
        }
        $config = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_config` WHERE `id`=1"));

        include_once('includes/tbs_class.php');
        include_once('includes/tbs_plugin_opentbs.php');

        $TBS = new clsTinyButStrong;
        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN);
        $TBS->LoadTemplate('templates/'.$config['template']);
        $name = $ticket['name'];
        $type = $type['name'];
        $barcode = "barcodes/".$cardcode.".jpg";
        $logo = "images/logo.jpg";
        $TBS->Show(OPENTBS_DOWNLOAD);


}
?>
