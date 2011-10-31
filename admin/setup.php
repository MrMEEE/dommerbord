<?php

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
        $file = 'connect.php';
        
        // Search and Replace Arrays
        $search = array('/HOST/','/USER/','/PASSWORD/','/DATABASE/');
        $replace = array($host,$bruger,$kode,$database);
        
        // Open the file
        $lines = file($file);
        $fh = fopen($file, 'w') or die("can't open file");
        // Read through the file
        for ($i = 0; $i < count($lines); $i++) {
          //$line = htmlentities($lines[$i]);
          $line = htmlentities(preg_replace($search, $replace, $lines[$i]));          
          fwrite($fh, html_entity_decode($line));
        }
    
        /*foreach($lines as $key => $line) {
            echo $key." ".$line;
            $text = preg_replace($search, $replace, $line);
            //var_dump("1: $text :1");
            //fwrite($fh,$text);
        }*/
        fclose($fh);
        $sql = explode(';', file_get_contents ('sql/dommerbord.sql'));
        $n = count ($sql) - 1;
        for ($i = 0; $i < $n; $i++) {
        $query = $sql[$i];
        $result = mysql_query ($query) or die ('<p>Query: <br><tt>' . $query . '</tt><br>failed. MySQL error: ' . mysql_error());
        }
        header( 'Location: configuration.php' );       
        $failed=10;
      }   
    }
  }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">  
<head>
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

</form> 