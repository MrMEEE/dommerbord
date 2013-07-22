<?php

require("../../connect.php");
require "bballstats_stats_class.php";


//$id = (int)$_POST['id'];

try{

	switch($_POST['action'])
	{
		case 'edit':
			stats::changeValues($_POST);
			break;
		case 'get':
			$query = mysql_query("SELECT * FROM bballstats_stats WHERE `kampid` = '".$_POST['gameid']."'");
			$players = mysql_num_rows($query);
			while($playerstats = mysql_fetch_assoc($query)){
				$querystats = mysql_query("SHOW COLUMNS FROM `bballstats_stats`");
				while($stattype = mysql_fetch_assoc($querystats)){
					if(($stattype['Field']!="id") && ($stattype['Field']!="spiller") && ($stattype['Field']!="kampid")){
						if(substr($stattype["Field"],0,2)!="£"){
							$teamstats[$stattype["Field"]] = $playerstats[$stattype["Field"]] + $teamstats[$stattype["Field"]];
						}
					}
				}
			}
			
			$querystats = mysql_query("SHOW COLUMNS FROM `bballstats_stats`");
			while($stattype = mysql_fetch_assoc($querystats)){
				if(($stattype['Field']!="id") && ($stattype['Field']!="spiller") && ($stattype['Field']!="kampid")){
					if(substr($stattype["Field"],0,2)=="£"){
						$calcstr="";
						list($start,$calc,$name)=split("£",$stattype["Field"]);
						$calcarr = str_split($calc."!");
						$operators = array("/","*","+","-","(",")");
						$numbers = array("0","1","2","3","4","5","6","7","8","9");
						$operant = "";
						$last = "";
						$calcstr = "";
						foreach ($calcarr as $char){
							if(in_array($char,$operators) || in_array($char,$numbers)){
								if($last == "operant"){
									$calcstr .= $teamstats[$operant];
									$operant = "";
								}
								$calcstr .= $char;
								$last = "operator";
							}elseif($char == "!"){
								$calcstr .= $teamstats[$operant];
							}else{
								$operant .= $char;
								$last = "operant";
							}
						}
						
						$compute = create_function("", "return (" . $calcstr . ");" );
						$value = 0 + $compute();
						$teamstats[$name] = $value;
					}
				}
			}
			
			$json = '{ ';
			foreach ($teamstats as $statname => $stats){
				$json .= '"'.$statname.'" : "'.$stats.'", ';
			}
			$json = substr_replace($json ,"",-2);
			$json .= "}";
			
			echo $json;
			break;
	}
	

}
catch(Exception $e){
//	echo $e->getMessage();
	die("0");
}

?>
