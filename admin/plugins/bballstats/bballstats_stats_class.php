<?php

require("../../connect.php");
require("bballstats_common.php");

class stats{

        private $data;

        public function __construct($par){
                if(is_array($par))   
                        $this->data = $par;
        }
        
        public function __toString(){
                
                
                $playerinfo = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballstats_players` WHERE id='".$this->data["spiller"]."'"));
                $query = mysql_query("SHOW COLUMNS FROM `bballstats_stats`");
                $return = "";
                $return .= '<form method="post" action="#" id="statsform" name="statsform" class="statsform">';
                $return .= '<table class="stats" cellpadding="0" border="0">';
                $return .= '<tr>';
                $return .= '<td width="150px"><a href="javascript:void(RemovePlayerStats(\''.$this->data["id"].'\'))"><img width="15px" src="img/remove.png"></a>'.$playerinfo["fornavn"].' '.$playerinfo["efternavn"].'</td>';
                            while($stattype = mysql_fetch_assoc($query)){
                            $extra = "";
                            if(substr($stattype["Field"],0,2)=="£"){
                                    list($start,$operation,$stat)=split("£",$stattype["Field"]);
                                    $extra = 'readonly="readonly"';
                            }else{
                                    $stat = $stattype["Field"];
                            }
                                        if(($stattype['Field']!="id") && ($stattype['Field']!="spiller") && ($stattype['Field']!="kampid")){
                                                $return .= '<td width="45px" align="center"><input  style="width:30px;text-align:right;" type="text" class="'.$stat.'" name="'.$stattype['Field'].'" id="'.$stattype['Field'].'" '.$extra.' value="'.$this->data[$stattype['Field']].'"></td>';
                                        }
                            }
                        
                $return .= '<input type="hidden" name="action" id="action" value="edit">
                            <input type="hidden" name="id" id="id" value="'.$this->data["id"].'">';
                $return .= '</tr>';
                $return .= '</table>';
                $return .= '</form>';        
                
                return $return;
                                        
        }
        
        public function changeValues($values){
        
                $str="";
                $json="{";
                foreach ($values as $key => $value){
                        if(($key!="id") && ($key!="action")){
                                if(substr($key,0,2)=="£"){
                                    $calcstr="";
                                    list($start,$calc,$name)=split("£",$key);
                                    $calcarr = str_split($calc."!");
                                    $operators = array("/","*","+","-","(",")");
                                    $numbers = array("0","1","2","3","4","5","6","7","8","9");
                                    $operant = "";
                                    $last = "";
                                    $calcstr = "";
                                    foreach ($calcarr as $char){
                                        if(in_array($char,$operators) || in_array($char,$numbers)){
                                            if($last == "operant"){
                                                $calcstr .= $values[$operant];
                                                $operant = "";
                                            }
                                            $calcstr .= $char;
                                            $last = "operator";    
                                        }elseif($char == "!"){
                                            $calcstr .= $values[$operant];
                                        }else{
                                            $operant .= $char;
                                            $last = "operant";
                                        }
                                    
                                    }
                                    $compute = create_function("", "return (" . $calcstr . ");" );
                                    $value = 0 + $compute();
                                    $json .= '"'.$name.'" : "'.$value.'", ';
                                }
                                
                                $str .= "`".$key."`='".$value."',";
                        }
                }
                
                $str = substr_replace($str ,"",-1);
                
                $json = substr_replace($json ,"",-2);
                $json .= "}";
                mysql_query("UPDATE bballstats_stats SET ".$str." WHERE id='".$values['id']."'");
                
                echo $json;
        
        }

}

?>