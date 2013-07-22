<?php

require("bballticketclient_connect.php");

switch($_POST['action']){

    case 'scan':
/*
/ Status Codes
/
/ 0 - OK
/ 1 - Invalid Code Length
/ 2 - Non-existing Card/Ticket
/ 3 - Wrong or Unknown Type
/ 4 - Card/Ticket Suspended
/ 5 - Tickettype has Expired
/ 6 - Card/Ticket doesn't give access to this game
/ 7 - Ticket sold
/ 8 - Number of accesses for the Card/Ticket has been exceeded
/ 9 - No room in available seatgroups
*/

if(isset($_POST['scan'])){
    $scan = $_POST['scan'];
    $game = $_POST['game'];
    
    if(strlen($scan) != 14){
         $message = "Ugyldig billet/kort kode";
         $status = 1;
         $color = "red";
    }else{
         $ticket = substr($scan,4,10);
         $type = substr($scan,0,4);
         $ticketquery = mysql_query("SELECT * FROM `bballtickets_tickets` WHERE `id`=".$ticket);
         if(!mysql_num_rows($ticketquery)){
              $message = "Billet/Kort eksisterer ikke";
              $status = 2;
              $color = "red";
         }else{
              $ticketinfo = mysql_fetch_assoc($ticketquery);
              $typequery = mysql_query("SELECT * FROM `bballtickets_tickettypes` WHERE `id`=".$type);
              $typeinfo = mysql_fetch_assoc($typequery);
              if($ticketinfo['type'] != $type || !mysql_num_rows($typequery)){
                   $message = "Billet/Kort er forkert type eller typen eksisterer ikke";
                   $status = 3;
                   $color = "red";
              }elseif($ticketinfo['suspended']){
                   $message = "Billetten/Kortet er deaktiveret";
                   $status = 4;
                   $color = "red";
              }elseif(($typeinfo['expires'] < date("Y-m-d")) && !($typeinfo['expires'] == "0000-00-00")){
                   $message = "Billettypes udlbsdato er n†et";
                   $status = 5;
                   $color = "red";
              }
         }
    }     
    if(!isset($status)){
         $typeaccess = explode(',',$typeinfo['access']);
         $typeseatgroups = explode(',',$typeinfo['group']);
         foreach($typeseatgroups as $typeseatgroup){
              if(($typeseatgroup != "") && ($typeseatgroup != 0)){
                   $typegroupquery .= "`id` = '".$typeseatgroup."' OR ";
              }
         }
         $typegroupquery = substr($typegroupquery,0,-3);
         $seatgroupquery = mysql_query("SELECT * FROM `bballtickets_seatgroups` WHERE ".$typegroupquery." ORDER BY `priority` DESC");
         
         if(in_array('all',$typeaccess)){
               $checkinquery = mysql_query("SELECT * FROM `bballtickets_checkins` WHERE `game`='".$game."' AND `code`='".$scan."' AND `status`='0'");
         }elseif(in_array('free',$typeaccess)){
               $checkinquery = mysql_query("SELECT * FROM `bballtickets_checkins` WHERE `code`='".$scan."' AND `status`='0'");
         }elseif(in_array('counter',$typeaccess)){
               $checkinquery = "counter";
         }else{
               if(!in_array($game,$typeaccess)){
                    $message = "Billetten/Kortet giver ikke adgang til denne kamp";
                    $status = 6;
                    $color = "red";
               }else{
                    $checkinquery = mysql_query("SELECT * FROM `bballtickets_checkins` WHERE `game`='".$game."' AND `code`='".$scan."' AND `status`='0'");
               }
         }
    }
    if(!isset($status)){
         if($checkinquery != "counter"){
              if($typeinfo['seats'] != "unlimited"){
                   if(mysql_num_rows($checkinquery) >= $typeinfo['seats']){
                        $message = "Billetten/Kortet giver adgang for ".$typeinfo['seats']." person(er), og er allerede opbrugt";
                        $status = 8;
                        $color = "red";
                   }
              }
         }
    }
    if(!isset($status)){
         foreach($typeseatgroups as $typeseatgroup){
              if(($typeseatgroup != "") && ($typeseatgroup != 0) && ($seatgroup == "")){
                   $seatgroupinfo = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_seatgroups` WHERE `id`='".$typeseatgroup."'"));
                   $checkinquery = mysql_query("SELECT * FROM `bballtickets_checkins` WHERE `game`='".$game."' AND `status`='0' AND `seatgroup`='".$typeseatgroup."'");
                   if(mysql_num_rows($checkinquery) < $seatgroupinfo['seats']){
                        $seatgroup = $typeseatgroup;
                   }
              }
         }
         if($seatgroup == ""){
              $message = "Ingen af de s‘degrupper billetten/kortet giver adgang til er frie";
              $status = 9;
              $color = "red";
         }elseif($checkinquery == "counter"){
              $message = "Billet solgt, placeret i ".$seatgroupinfo['name']."";
              $status = 7;
              $color = "green";         
         }else{
              $message = "Billet/Kort OK, placeret i ".$seatgroupinfo['name']."";
              $status = 0;
              $color = "green";
         }
         
    }
    
    $query = "INSERT INTO `bballtickets_checkins` (`game`,`code`,`status`,`seatgroup`,`new`) VALUES ('".$_POST['game']."','".$scan."','".$status."','".$seatgroup."','1')";
    mysql_query($query);
}     
        $query = "SELECT * FROM `bballtickets_checkins` WHERE `game` = ".$_POST['game']." AND `status` = 0";
        $query = mysql_query($query);
        
        $checkins = mysql_num_rows($query);
           
        $json = '{ ';
        $json .= '"message" : "'.$message.'", ';
        $json .= '"status" : "'.$status.'", ';
        $json .= '"checkins" : "'.$checkins.'", '; 
        $json .= '"color" : "'.$color.'"';
        $json .= "}";
        
        echo $json;
        
    break;
}

?>
