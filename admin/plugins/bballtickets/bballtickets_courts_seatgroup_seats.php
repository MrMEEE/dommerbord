<?php
require("../../connect.php");
require("../../config.php");
require("../../checkConfig.php");
require("../../checkLogin.php");
require("../../checkAdmin.php");

if(isset($_POST['courtid']) && $_POST['courtid'] != '')
{
  $courtid = $_POST['courtid'];
  $courtid = mysql_real_escape_string($courtid);
  $allocated = mysql_fetch_assoc(mysql_query("SELECT sum(seats) FROM bballtickets_seatgroups WHERE court='".$courtid."'"));
  $seats = mysql_fetch_assoc(mysql_query("SELECT * FROM bballtickets_courts WHERE id='".$courtid."'"));
  $freeseats = $seats['seats'] - $allocated['sum(seats)'];
  for ($i = 0; $i <= $freeseats; $i++){
    echo "<option value='".$i."'>".$i."</option>";
  }
}
?>
