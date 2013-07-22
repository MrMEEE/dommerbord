<?php

require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");
require("bballtickets_functions.php");

header('Content-type: text/backup; charset=utf-8');
header('Content-Disposition: inline; filename=export-'.date("dmY-Hi").'.tde');

genExport();

?>
