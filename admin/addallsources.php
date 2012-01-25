<?php

require("connect.php");
require("checkLogin.php");
require("checkAdmin.php");
require("config.php");
require("theme.php");

getThemeHeader();

?>

function ConfirmChoice(teamid){
 
 answer = confirm("Er du sikker på at du vil slette dette Hold/denne Pulje");
 
 if (answer !=0){
  
  var klubadresse = "<?php echo $klubadresse;?>";
  var klubpath = "<?php echo $klubpath;?>";
  
  location = "http://" + klubadresse + "/" + klubpath + "/admin/addallsources.php?removesource=1&id=" + teamid;
  
 }
   
}

function ConfirmAllChoice(){
 
  answer = confirm("Er du sikker på at du vil slette alle Hold/Puljer");
   
  if (answer !=0){
       
   var klubadresse = "<?php echo $klubadresse;?>";
   var klubpath = "<?php echo $klubpath;?>";
           
   location = "http://" + klubadresse + "/" + klubpath + "/admin/addallsources.php?removeallsources=1";
               
  }
                  
}

<?php                      

getThemeTitle("Holdadministration");    

require("menu.php");

if(checkAdmin($_SESSION['username'])){

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
 $url        = "http://resultater.basket.dk/tms/Turneringer-og-resultater/Forening-Holdoversigt.aspx?ForeningsId=$klubid";
 $input = @file_get_contents($url) or die("Could not access file: $url");
 $regexp = "PuljeId=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
 $regexp2 = "RaekkeId=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";

 preg_match_all("/$regexp/siU", $input, $matches);
 preg_match_all("/$regexp2/siU", $input, $matches2);
  
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
  }else{
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

}

$query = mysql_query("SELECT * FROM `calendars` ORDER BY `team`");

if (checkAdmin($_SESSION['username'])){
 echo '<br><a href="addallsources.php?refreshsources=1">Tilføj alle klubbens hold/puljer</a><br>';
 echo '<br><a href="javascript:void(ConfirmAllChoice())">Fjern alle klubbens hold/puljer</a><br>';
}

echo '<br><br>Hold: <br><br>';

while($row = mysql_fetch_assoc($query)){
 echo '<a href="';
 echo $row['address'];
 echo '">';
 echo $row['team'];
 echo '</a>';
 if (checkAdmin($_SESSION['username'])){
  echo ' - ';
  echo '<a href="javascript:void(ConfirmChoice('.$row['id'].'))">Fjern</a>';    
 }
 echo '<br>';
}
                  
echo '<br><br>';

getThemeBottom();

?>

