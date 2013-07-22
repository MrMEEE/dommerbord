<?php

require("bballticketclient_connect.php");
require("bballticketclient_check_database.php");
require("bballticketclient_theme.php");
require("bballticketclient_functions.php");

function clearDatabase(){

      mysql_query("DROP TABLE bballtickets_courts,bballtickets_tickets,bballtickets_config,bballtickets_seatgroups,bballtickets_tickettypes,calendars,games,bballtickets_checkins");

}

function file_get_contents_utf8($fn) {
     $content = file_get_contents($fn);
     return mb_convert_encoding($content, 'UTF-8',
     mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true));
}

function importTDE($filename){
      $file = mb_convert_encoding(file_get_contents_utf8($filename), 'HTML-ENTITIES', "UTF-8");
      importData($file);
}

function importDownload($downloaddata){
      $data = mb_convert_encoding($downloaddata, 'HTML-ENTITIES', "UTF-8");
      importData($data);
}

function importData($data){
      $lines = preg_split( '/\r\n|\r|\n/', $data );
      while ($lines[0] == " "){
            array_shift($lines);
      }
      
      clearDatabase();
      require("bballticketclient_check_database.php");
      $numberofcheckins = 0;
      if(array_shift($lines) == "BBALLTICKETDATABASEEXPORT"){
	    foreach($lines as $line){
		   if(substr($line,0,1)=="%"){
			  $table=str_replace('%','',$line);
                   }elseif(substr($line,0,1)=="&"){
                                  $fields = substr($line,1);
                   }else{
			  $query="INSERT INTO `$table` ($fields) VALUES ($line)";
                          mysql_query($query);
                          //echo $query;
                   }
            }
            mysql_query("UPDATE `bballticketclient_config` SET `lastupdate`=NOW()");
            return "Data fra eksporten blev indlæst.";
       }else{
            return "Den oploadede fil er ikke en 'Ticket Database Export'-fil";
       }

}

if(isset($_POST['masterserver'])){
    
    $master = $_POST['masterserver'];
    if($master[strlen($master)-1] == "/"){
        $master = substr_replace($master ,"",-1);
    }
    mysql_query("UPDATE `bballticketclient_config` SET `masterurl`='".$master."'");

}

if(isset($_POST['clientname'])){

    mysql_query("UPDATE `bballticketclient_config` SET `clientname`='".$_POST['clientname']."'");

}                     

if(isset($_FILES['file'])){

      // Where the file is going to be placed 
      $target_path = "imports/";

      if(!file_exists($target_path)){
            mkdir($target_path);
      }
      /* Add the original filename to our target path.  
      Result is "uploads/filename.extension" */
      $target_filepath = $target_path . basename( $_FILES['file']['name']);
      $filename=explode('.',$_FILES['file']['name']);
      
      if(end($filename) == "tde"){
            if(!file_exists($target_path."/".$_FILES['file']['name'])){
                  if(move_uploaded_file($_FILES['file']['tmp_name'], $target_filepath)) {
                        $message="Filen ".  basename( $_FILES['file']['name'])." blev oploadet.";
                        importTDE($target_filepath);
                        
                  }else{
                        $message="Der opstod en fejl under opload.";
                  }
            }else{
                  $message="Denne eksport er allerede blevet oploadet.";
            }
      }else{
            $message="Den oploadede fil er ikke en 'Ticket Database Export'-fil, eller har ikke suffix '.tde'";
      }

            
}

getThemeHeader();
?>
function removeCheckins(ticketid){

 answer = confirm("Er du sikker på at du vil slette alle lokale indcheckninger???")

 if (answer !=0) {
   answer = confirm("Er du HELT sikker??, disse data er ikke sendt til master-serveren!!!!")
   if (answer !=0){
    document.checkin.action.value="removeCheckins";
    document.checkin.submit();
   }
 }

}
<?php
getThemeTitle();

$clientconfig = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballticketclient_config` WHERE id='1'"));

if((url_exists($clientconfig['masterurl'].'/admin/plugins/bballtickets/bballtickets_importexport.php') == "302") || (url_exists($clientconfig['masterurl'].'/admin/plugins/bballtickets/bballtickets_importexport.php') == "200")){

$url = $clientconfig['masterurl'].'/admin/plugins/bballtickets/bballtickets_importexport.php';
$fields = array(
	'clientname' => urlencode($clientconfig['clientname']),
	'clientid' => urlencode($clientconfig['clientid']),
	'clientpass' => urlencode($clientconfig['clientpass'])
);

$fields_string = http_build_query($fields);

$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);

//execute post
if( ! $result = curl_exec($ch)){
    trigger_error(curl_error($ch));
} 

//close connection
curl_close($ch);

if($result[2] == "1"){

$message .= '<font color="red">Denne Klient er ikke blevet godkendt på Master Serveren.</font><br><br>';

}

if($_POST['action'] == "download"){
      importDownload($result);

}
if($_POST['action'] == "removeCheckins"){
      
      mysql_query("DELETE FROM `bballtickets_checkins` WHERE `new` = 1");

}

if($_POST['action'] == "upload"){
      $query = mysql_query("SELECT * FROM `bballtickets_checkins` WHERE `new`='1'");
      while($checkin = mysql_fetch_assoc($query)){
            $url = $clientconfig['masterurl'].'/admin/plugins/bballtickets/bballtickets_importexport.php';
	    
	    $fields = array(
	          'clientid' => urlencode($clientconfig['clientid']),
	          'clientpass' => urlencode($clientconfig['clientpass']),
	          'checkindata' => "(".$checkin['game'].",".$checkin['code'].",".$checkin['status'].",".$checkin['seatgroup'].")"
            );
            
            $fields_string = http_build_query($fields);
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
 
            //execute post
            if( ! $result = curl_exec($ch)){ 
                trigger_error(curl_error($ch));
            }

            //close connection
            curl_close($ch);

            if($result[2] == "0"){
                  mysql_query("DELETE FROM `bballtickets_checkins` WHERE `id`='".$checkin['id']."'");
            }
            
      }
}

}else{

$message .= '<font color="red">Ingen forbindelse eller forkert adresse til Master Server</font><br><br>';


}

echo $message;

$clientconfig = mysql_fetch_assoc(mysql_query("SELECT * FROM `bballticketclient_config` WHERE id='1'"));

$checkins = mysql_num_rows(mysql_query("SELECT * FROM `bballtickets_checkins` WHERE `new` = '1'"));

if($checkins != 0){
      $status = '"Send til Server først" disabled';
}else{
      $status = '"Hent fra Server"';
}

echo '<form action="bballticketclient_import.php" method="post" name="data">
      <input type="hidden" name="action" value="upload">
      <input type="submit" value="Send til Server">
      </form><br>';

echo 'Antal ikke afsendte checkins: '.$checkins.'<br><br>';

echo '<form action="bballticketclient_import.php" method="post" name="data">
      <input type="hidden" name="action" value="download">
      <input type="submit" value='.$status.'>
      </form><br>';
echo '<form action="bballticketclient_import.php" method="post" name="checkin">
      <input type="hidden" name="action">
      </form>';

echo 'Sidst Opdateret: '.$clientconfig['lastupdate'].'<br><br>';

echo '<input type="submit" value="Slet lokale checkins" onClick="return removeCheckins();" /><br><br><br><br>';

?>

<form action="bballticketclient_import.php" method="post" enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file" />
<br><br>
<input type="submit" name="submit" value="Importer Fil" />
</form>

<br><br><br><br>
<h3>Konfiguration</h3><br>
<table>
<tr>
<td>
<form action="bballticketclient_import.php" method="post">
Master Server:
</td>
<td>
<input type="text" name="masterserver" id="masterserver" value="<?php echo $clientconfig['masterurl'] ?>">
</td>
</tr>
<tr>
<td>
Klient Navn:
</td>
<td>
<input type="text" name="clientname" id="clientname" value="<?php echo $clientconfig['clientname'] ?>">
</td>
</tr>
</table>
<input type="submit" name="submit" value="Gem">
</form>

<?php

getThemeBottom();

?>
