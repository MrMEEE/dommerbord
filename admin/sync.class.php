<?php

require("connect.php");

switch($_POST['action']){

    case 'sync':
        $currentteam=$icals['team'];



        
        $typecount = array_count_values($types);
        foreach($_POST['checkbox'] as $checkbox){
            $type = substr_replace($checkbox, "", -1, -10);
            $result = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballtickets_tickettypes` WHERE `id`='".$type."'"));
            $names[] = $result['name'];
            if(array_key_exists($checkbox, $typecount)){
                $values[] = $typecount[$checkbox];
            }else{
                $values[] = 0;
            }
        }
        
        $json = '{ ';
        $json .= '"values" : '.json_encode($values).', ';
        $json .= '"names" : '.json_encode($names).'' ;
        $json .= '}';
        
        echo $json;
        
    break;


}
?>
