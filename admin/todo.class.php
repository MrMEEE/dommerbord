<?php

require "connect.php";

//DEBUG: echo '<script language="javascript">confirm("'.$query.'")</script>;';

/* Defining the ToDo class */

class ToDo{
	
	/* An array that stores the todo item data: */
	
	private $data;
	
	/* The constructor */
	public function __construct($par){
		if(is_array($par))
			$this->data = $par;
	}
	
	/*
		This is an in-build "magic" method that is automatically called 
		by PHP when we output the ToDo objects with echo. 
	*/
		
	public function __toString(){
		// The string we return is outputted by the echo statement
		$refereeteamlist1="";
		$refereeteamlist2="";
		$tableteamlist1="";
		$tableteamlist2="";
		$tableteamlist3="";
		
		$result=mysql_query("select id, name from teams order by name asc");
		while(list($id, $name)=mysql_fetch_row($result)) {
		        if($this->data['refereeteam1id']==$id){
		        	$refereeteamlist1.= "<option value=\"".$id."\" selected>".$name."</option>";
		        }
		        else{
		        	$refereeteamlist1.= "<option value=\"".$id."\">".$name."</option>"; 
		        }
		}
		$result=mysql_query("select id, name from teams order by name asc");
		while(list($id, $name)=mysql_fetch_row($result)) {
                        if($this->data['refereeteam2id']==$id){
                                $refereeteamlist2.= "<option value=\"".$id."\" selected>".$name."</option>";
                        }
                        else{
                                $refereeteamlist2.= "<option value=\"".$id."\">".$name."</option>"; 
                        }
                }
                $result=mysql_query("select id, name from teams order by name asc");
		while(list($id, $name)=mysql_fetch_row($result)) {
                        if($this->data['tableteam1id']==$id){
                                $tableteamlist1.= "<option value=\"".$id."\" selected>".$name."</option>";
                        }
                        else{
                                $tableteamlist1.= "<option value=\"".$id."\">".$name."</option>"; 
                        }
                }
                $result=mysql_query("select id, name from teams order by name asc");
		while(list($id, $name)=mysql_fetch_row($result)) {
                        if($this->data['tableteam2id']==$id){
                                $tableteamlist2.= "<option value=\"".$id."\" selected>".$name."</option>";
                        }
                        else{
                                $tableteamlist2.= "<option value=\"".$id."\">".$name."</option>"; 
                        }
                }
                $result=mysql_query("select id, name from teams order by name asc");
		while(list($id, $name)=mysql_fetch_row($result)) {
                        if($this->data['tableteam3id']==$id){
                                $tableteamlist3.= "<option value=\"".$id."\" selected>".$name."</option>";
                        }
                        else{
                                $tableteamlist3.= "<option value=\"".$id."\">".$name."</option>"; 
                        }
                }
                $date=substr($this->data['date'],8,2);
                $date.="/";
                $date.=substr($this->data['date'],5,2);
                $date.="-";
                $date.=substr($this->data['date'],0,4);
		$return = "";
		
		if($this->data['status']=='1' && $this->data['refereeteam1id']!='0' && $this->data['refereeteam2id']!='0' && $this->data['tableteam1id!']!='0' && $this->data['tableteam2id']!='0' && $this->data['tableteam3id']!='0'){
		    mysql_query("UPDATE games SET status='0' WHERE id = '".$this->data['id']."'");
		    $this->data['status']='0';
		}
		switch($this->data['status']){
		case 0:  // OK
		    $return.= '<li id="todo-'.$this->data['id'].'" class="todo ok">';
		    break;
		case 1:  // New
		    $return.= '<li id="todo-'.$this->data['id'].'" class="todo new">';
		      break;
		case 2:  // Changed
		    $return.= '<li id="todo-'.$this->data['id'].'" class="todo changed">';
		    break;
		case 3:
		    $return.= '<li id="todo-'.$this->data['id'].'" class="todo cancelled">';
		    break;
		case 4:
		    $return.= '<li id="todo-'.$this->data['id'].'" class="todo moved">';
		    break;
		}
		$return .= '
				
				Kampnummer: <div class="number">'.$this->data['id'].'</div>
				Dato: <div class="date">'.$date.'</div>
				Tidspunkt: <div class="time">'.$this->data['time'].'</div>
				Beskrivelse: <div class="text">'.$this->data['text'].'</div>

				<div class="actions">
				
					<div style="position:absolute; right:150px;">
						Dommerbord: <form name="tableteam1" action="" class="tableteam1">
                                                <select name="table1" id="table1Select">
                                                  <option value="0">Vælg et hold</option>
                                                  '.$tableteamlist1.'
                                                </select>
                                                </form>
                                                <br>
                                                Dommerbord: <form name="tableteam2" action="" class="tableteam2">
                                                <select name="table2" id="table2Select">
                                                  <option value="0">Vælg et hold</option>
                                                  '.$tableteamlist2.'
                                                 </select>
                                                 </form>	
								
						<a href="#" class="delete">Delete</a>
						<a href="#" class="edit">Edit</a>
					</div>
					<div style="position:absolute; right:0px;">
						
						1.Dommer: <form name="refereeteam1" action="" class="refereeteam1">
						<select name="referee1" id="referee1Select">
						  <option value="0">Vælg et hold</option>
						  '.$refereeteamlist1.'
						</select>
						</form>
						<br>
						2.Dommer: <form name="refereeteam2" action="" class="refereeteam2">
						<select name="referee2" id="referee2Select">
                        		          <option value="0">Vælg et hold</option>
                                		  '.$refereeteamlist2.'
                                		 </select>
						</form>		
						<br>
						24. Sekunder: <form name="tableteam3" action="" class="tableteam3">
						<select name="table3" id="table3Select">
                                                  <option value="0">Vælg et hold</option>
                                                  '.$tableteamlist3.'
                                                 </select>
                                                </form>
					</div>	
				</div> <!-- actions -->				
			</li>';
		 return $return;
	}
	
	public static function changeTeam($id, $team, $teamlist){
		switch($teamlist){
			case '1':
				$idlist="refereeteam1id";
			        break;
			case '2':
                                $idlist="refereeteam2id";
                                break;
			case '3':
				$idlist="tableteam1id";
                                break;
			case '4':
                                $idlist="tableteam2id";
                                break;
			case '5':
                                $idlist="tableteam3id";
                                break;	
		}
		$team = self::esc($team);
		if(!$team) throw new Exception("Wrong update text!");
		$status=mysql_fetch_assoc(mysql_query("SELECT status FROM games WHERE id = '$id'"));
		$status=$status['status'];
		mysql_query("   UPDATE games
				SET $idlist='".$team."'
				WHERE id=".$id);
		if($status=='2'){
		mysql_query("   UPDATE games
				SET status='1'
				WHERE id=".$id);
		}
		//echo "alert(\"$idlist and $team and $id\")";
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
	}
		
	/*
		The following are static methods. These are available
		directly, without the need of creating an object.
	*/
	
	
	
	/*
		The edit method takes the ToDo item id and the new text
		of the ToDo. Updates the database.
	*/
		
	public static function edit($id, $text, $type){
		echo '<script language="javascript">confirm("'.$text.'")</script>;';
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong update text!");
		
		mysql_query("	UPDATE games
						SET $type='".$text."'
						WHERE id=".$id
					);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't update item!");
	}

	
	/*
		The delete method. Takes the id of the ToDo item
		and deletes it from the database.
	*/
	
	public static function delete($id){
		
		mysql_query("DELETE FROM games WHERE id=".$id);
		
		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Couldn't delete item!");
	}
	
	/*
		The rearrange method is called when the ordering of
		the todos is changed. Takes an array parameter, which
		contains the ids of the todos in the new order.
	*/
	
	public static function rearrange($key_value){
		
		$updateVals = array();
		foreach($key_value as $k=>$v)
		{
			$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1).PHP_EOL;
		}
		
		if(!$strVals) throw new Exception("No data!");
		
		// We are using the CASE SQL operator to update the ToDo positions en masse:
		
		mysql_query("	UPDATE games SET position = CASE id
						".join($strVals)."
						ELSE position
						END");
		
		if(mysql_error($GLOBALS['link']))
			throw new Exception("Error updating positions!");
	}
	
	/*
		The createNew method takes only the text of the todo,
		writes to the databse and outputs the new todo back to
		the AJAX front-end.
	*/
	
	public static function createNew($text){
		
		$text = self::esc($text);
		if(!$text) throw new Exception("Wrong input data!");
		
		$posResult = mysql_query("SELECT MAX(position)+1 FROM games");
		
		if(mysql_num_rows($posResult))
			list($position) = mysql_fetch_array($posResult);

		//if(!$position) 
		$position = 1;

		mysql_query("INSERT INTO games SET text='".$text."',time='00:00:00',position = ".$position);

		if(mysql_affected_rows($GLOBALS['link'])!=1)
			throw new Exception("Error inserting TODO!");
		
		// Creating a new ToDo and outputting it directly:
		
		echo (new ToDo(array(
			'id'	=> mysql_insert_id($GLOBALS['link']),
			'text'	=> $text
		)));
		
		
		exit;
	}
	
	/*
		A helper method to sanitize a string:
	*/
	
	public static function esc($str){
		
		if(ini_get('magic_quotes_gpc'))
			$str = stripslashes($str);
		
		return mysql_real_escape_string(strip_tags($str));
	}
	
} // closing the class definition

?>
