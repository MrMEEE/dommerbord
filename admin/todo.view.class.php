<?php

require "connect.php";

//DEBUG: echo '<script language="javascript">confirm("'.$query.'")</script>;';

/* Defining the ToDo class */

class ToDo{
	
	/* An array that stores the todo item data: */
	
	private $data;
	private $lastdate;
	
	/* The constructor */
	public function __construct($par,$week){
		if(is_array($par))
			$this->data = $par;
			$this->lastweek=$week;
	}
	
	/*
		This is an in-build "magic" method that is automatically called 
		by PHP when we output the ToDo objects with echo. 
	*/
		
	public function __toString(){
		// The string we return is outputted by the echo statement
                $date=substr($this->data['date'],8,2);
                $date.="/";
                $date.=substr($this->data['date'],5,2);
                $date.="-";
                $date.=substr($this->data['date'],0,4);
		$dateformat=substr($this->data['date'],0,4);
		$dateformat.=substr($this->data['date'],5,2);
		$dateformat.=substr($this->data['date'],8,2);
		$pos=strpos($this->data['text'],":");
		$teams=substr($this->data['text'],0,$pos);
		$string = "";
		if($this->lastweek!=0 && $this->lastweek!=date("W",strtotime($dateformat))){
		    $string .= '</tbody>
		      		</table>';
		}
		if($this->lastweek!=date("W",strtotime($dateformat))){
		    $string .= '<table id="games" class="wp-table-reloaded wp-table-reloaded-id-1" border=1 width=540px>
		      		<thead>
				<tr class="row-1 odd">
				  <th class="column-1" width=50px>Uge: '.date("W",strtotime($dateformat)).'</th><th class="column-2" width=60px>Dato</th><th class="column-3">Kamp Beskrivelse</th><th class="column-4" width=50px>Bord</th><th class="column-5" width=50px>Dommer</th><th class="column-6"width=50px>24 sek</th>
				</tr>
				</thead>
				<tbody>';
		}
		$day="";
		switch(date("D",strtotime($dateformat))){
			case "Mon":
				$day="Mandag";
				break;
			case "Tue":
				$day="Tirsdag";
				break;
			case "Wed":
				$day="Onsdag";
				break;
			case "Thu":
				$day="Torsdag";
				break;
			case "Fri":
				$day="Fredag";
				break;
			case "Sat":
				$day="Lørdag";
				break;
			case "Sun":
				$day="Søndag";
				break;	
		}
		$string .= '
		<tr class="row-2 even" height=45px>
		<td class="column-1"><a href="admin/gotoGame.php?gameID='.$this->data['id'].'" target="_blank">'.$this->data['id'].'</a></td><td class="column-2">'.$day.'<br>'.$date.'</td><td class="column-3">';
		
		if(($this->data['status']==3) || ($this->data['status']==4)){
		    $string .= '<font style="text-decoration:line-through;">';
		}
		
		$string .= $this->data['text'];
		
		if(($this->data['status']==3) || ($this->data['status']==4)){
		    $string .= '</font>';
		    if($this->data['status']==3){
			$string .= '<br>Kamp Aflyst';
		    }
		    if($this->data['status']==4){
			$string .= '<br>Kamp Udsat';
		    }
		}
		
		if($this->data['refereeteam1']=="DBBF"){
			$ref1=$this->data['referee1name'];
		}else{
			$ref1=$this->data['refereeteam1'];
		}
		if($this->data['refereeteam2']=="DBBF"){
		        $ref2=$this->data['referee2name'];
		}else{
			$ref2=$this->data['refereeteam2'];
		}
		
		$string .= '</td><td class="column-4">'.$this->data['tableteam1'].'</td><td class="column-5">'.$ref1.'</td><td class="column-6">'.$this->data['tableteam3'].'</td>
		</tr>
		<tr class="row-2 odd" height=45px>
		<td class="column-1"></td><td class="column-2">'.$this->data['time'].'</td><td class="column-3">'.$this->data['place'].'</td><td class="column-4">'.$this->data['tableteam2'].'</td><td class="column-5">'.$ref2.'</td><td class="column-6"></td>
		</tr>
		<tr class="row-1 odd height=1px">
		
		</tr>
		';
		
		return $string;

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
		
		mysql_query("   UPDATE games
				SET $idlist='".$team."'
				WHERE id=".$id
		);
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
