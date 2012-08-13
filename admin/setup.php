<?php

require("theme.php");

if(file_exists("connect.php")){
header( "Location: index.php" );
}

$failed=0;

if(isset($_GET["host"])){
  $failed=1;

  $host=$_GET["host"];
  $database=$_GET["database"];
  $bruger=$_GET["bruger"];
  $kode=$_GET["kode"];

  if (($host!="") && ($database!="") && ($bruger!="") && ($kode!="")){
    $link = @mysql_connect($host,$bruger,$kode);	
    if (!$link) {
      $failed=2;
    }else{
      $dbcheck = mysql_select_db("$database");
      if (!$dbcheck) {
        $failed=3;
      }else {
        // File to open
        $fileopen = 'connect.php.default';
        
        // Search and Replace Arrays
        $search = array('/HOST/','/USER/','/PASSWORD/','/DATABASE/');
        $replace = array($host,$bruger,$kode,$database);
        
        // Open the file
        $lines = file($fileopen);
        $filewrite = 'connect.php';
        $fh = fopen($filewrite, 'w') or die("can't open file");
        // Read through the file
        for ($i = 0; $i < count($lines); $i++) {
          $line = htmlentities(preg_replace($search, $replace, $lines[$i]));          
          fwrite($fh, html_entity_decode($line));
        }
    
        fclose($fh);
        $sql = explode(';', file_get_contents ('sql/dommerbord.sql'));
        $n = count ($sql) - 1;
        for ($i = 0; $i < $n; $i++) {
        $query = $sql[$i];
        $result = mysql_query ($query) or die ('<p>Query: <br><tt>' . $query . '</tt><br>failed. MySQL error: ' . mysql_error());
        }
        mysql_query("UPDATE config SET updatesurl='http://www.dommerplan.dk/updates/' WHERE id = 1");
        header( 'Location: configuration.php' );       
        $failed=10;
      }   
    }
  }
}

getThemeHeader();
getThemeTitle("Installation");

?>


Indtast venligst database konfiguration:
<br><br>
<?php
if ($failed == 1){
echo '<font color="red">Indtast venligst alle oplysninger!</font><br><br>';
}
if ($failed == 2){
echo '<font color="red">Der kunne ikke oprettes forbindelse til databasen!</font><br><br>';
}
if ($failed == 3){
echo '<font color="red">Forbindelse oprettet, men databasen eksistere ikke!</font><br><br>';
}
?>
<form method="get" action="setup.php" name="database">
Host:<br>
<input type="text" name="host" value="<?php echo $host; ?>"><br>
Database:<br> 
<input type="text" name="database" value="<?php echo $database; ?>"><br>
Bruger:<br>  
<input type="text" name="bruger" value="<?php echo $bruger; ?>"><br>
Kode:<br>  
<input type="text" name="kode" value="<?php echo $kode; ?>"><br>

<input type="submit" value="Konfigurer">

Efter installationen vil det være muligt at logge ind med:<br>
<br>
Bruger: Admin
Kode: dommer
<br>
Denne kode kan efterfølgende ændres i bruger-håndteringen.
</form>

<?php

getThemeBottom();

?> 