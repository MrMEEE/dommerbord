<?php

require "connect.php";
require "todo.class.php";


$id = (int)$_GET['id'];

try{

	switch($_GET['action'])
	{
		case 'delete':
			ToDo::delete($id);
			break;
			
		case 'rearrange':
			ToDo::rearrange($_GET['positions']);
			break;
			
		case 'edit':
			ToDo::edit($id,$_GET['text'],"text");
			break;
                case 'editdate':
                        $fulldate = $_GET['date'];
                        $date = substr($fulldate,6,4);
                        $date .= "-";
                        $date .= substr($fulldate,3,2);
                        $date .= "-";
                        $date .= substr($fulldate,0,2);
                        
                        ToDo::edit($id,$date,"date");
                        break;
                case 'edittime':
                        ToDo::edit($id,$_GET['time'],"time");
                        break;
		case 'editreferee1team':
			ToDo::changeTeam($id,$_GET['team'],1);
			break;
		case 'editreferee2team':
                        ToDo::changeTeam($id,$_GET['team'],2);
                        break;
		case 'edittable1team':
                        ToDo::changeTeam($id,$_GET['team'],3);
                        break;
		case 'edittable2team':
                        ToDo::changeTeam($id,$_GET['team'],4);
                        break;
		case 'edittable3team':
                        ToDo::changeTeam($id,$_GET['team'],5);
                        break;
		case 'new':
			ToDo::createNew($_GET['text']);
			break;
	}

}
catch(Exception $e){
//	echo $e->getMessage();
	die("0");
}

echo "1";
?>
