<?php

require "connect.php";
require "config.php";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript">

function ConfirmChoice(teamid)

{
 
 answer = confirm("Er du sikker på at du vil slette dette Hold/denne Pulje");
 
 if (answer !=0)
 {
  
  var klubadresse = "<?php echo $klubadresse;?>";
  
  location = "http://" + klubadresse + "/dommer/admin/addallsources.php?removesource=1&id=" + teamid;
  
  }
   
  }

function ConfirmAllChoice()

{
 
  answer = confirm("Er du sikker på at du vil slette alle Hold/Puljer");
   
  if (answer !=0)
     {
       
   var klubadresse = "<?php echo $klubadresse;?>";
           
  location = "http://" + klubadresse + "/dommer/admin/addallsources.php?removeallsources=1";
               
   }
                  
}
                      
    
</script>
    
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $klubnavn; ?> Dommerbordsplan</title>

<!-- Including the jQuery UI Human Theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/humanity/jquery-ui.css" type="text/css" media="all" />

<!-- Our own stylesheet -->
<link rel="stylesheet" type="text/css" href="styles.css" />

</head>

<body>

<h1><?php echo $klubnavn; ?> Dommerplan</h1>

<div id="main">

<?php require("menu.php"); ?>

<?php
if(isset($_GET["removesource"])){
    if(isset($_GET["id"])){
      $id=$_GET["id"];
      mysql_query("DELETE FROM `calendars` WHERE `id` = $id");
      echo "Hold/Pulje slettet.<br>";
      }
}elseif(isset($_GET["removeallsources"])){
    mysql_query("DELETE FROM `calendars`");
    echo "Alle Hold/Puljer slettet.<br>";
    
}elseif(isset($_GET["refreshsources"])){

  //$dom = new DOMDocument();
  $url        = "http://resultater.basket.dk/tms/Turneringer-og-resultater/Forening-Holdoversigt.aspx?ForeningsId=$klubid";
  $input = @file_get_contents($url) or die("Could not access file: $url");
  $regexp = "PuljeId=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
  $regexp2 = "RaekkeId=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
  
  if(preg_match_all("/$regexp/siU", $input, $matches)) { 
  // $matches[2] = array of link addresses // $matches[3] = array of link text - including HTML code 
  }
  if(preg_match_all("/$regexp2/siU", $input, $matches2)) {
  
  }
  $i=0;
  $newteams=0;
  foreach ($matches[2] as $urls){
   $name=$matches2[3][$i];
    if(mysql_num_rows(mysql_query("SELECT * FROM calendars WHERE address = 'http://resultater.basket.dk/tms/Turneringer-og-resultater/Pulje-Komplet-Kampprogram.aspx?PuljeId=$urls'"))){
    if($debug != 0){ 
      echo $urls;
      echo " - ";
      echo "$name already exists<br>";
    }
    }
    else{
        mysql_query("INSERT into calendars (`address`, `team`) VALUES ('http://resultater.basket.dk/tms/Turneringer-og-resultater/Pulje-Komplet-Kampprogram.aspx?PuljeId=$urls', '$name')");
    
 print('Tilføjet: <a href="http://resultater.basket.dk/tms/Turneringer-og-resultater/Pulje-Komplet-Kampprogram.aspx?PuljeId='.$urls.'">'.$name.'</a><br>');
 
 
  $newteams=1;
  
  }
  $i=$i+1;
  }
  
  if($newteams==0){
   echo "Ingen nye Hold/Puljer.<br>";
  }
}
$query = mysql_query("SELECT * FROM `calendars` ORDER BY `team`");

// Filling the $todos array with new ToDo objects:
echo '<br><a href="addallsources.php?refreshsources=1">Tilføj alle klubbens hold/puljer</a><br>';
echo '<br><a href="javascript:void(ConfirmAllChoice())">Fjern alle klubbens hold/puljer</a><br>';
echo '<br><br>Hold: <br><br>';

while($row = mysql_fetch_assoc($query)){
    echo '<a href="';
    echo $row['address'];
    echo '">';
    echo $row['team'];
    echo '</a>';
    echo ' - ';
    echo '<a href="javascript:void(ConfirmChoice('.$row['id'].'))">Fjern</a><br>';    
}
                  
echo '<br><br>';
?>

</div>

</body>
</html>
