<?php

require("connect.php");
require("config.php");
require("theme.php");
require("checkAdmin.php");
require("checkLogin.php");

function checkInstallation(){
  
  require("config.php");
  
  global $message;
  
  $install_path=$_SERVER['DOCUMENT_ROOT'].$klubpath;
  
  if(is_dir($install_path."/upgrades")){
  
    //echo "Upgrades folder exists..";
  
  }else{
  
    $message="Upgrades folder does not exist, creating.. <br>";
    shell_exec("mkdir ".$install_path."/upgrades");
  
  }
  
  if(is_dir($install_path."/downloads")){
  
    //echo "Downloads folder exists..";
    
  }else{
  
    $message.="Downloads folder does not exist, creating.. <br>";
    shell_exec("mkdir ".$install_path."/downloads");
    
  }
  
  if(is_dir($install_path."/backups")){
  
    //echo "Backups folder exists..";
  
  }else{
  
    $message.="Backups folder does not exist, creating.. <br>";
    shell_exec("mkdir ".$install_path."/backups");
  
  }

}

function fetchUpgrades($src){
  
  require("../version.php");
  require("config.php");
  
  $available_versions = file_get_contents($src);
  
  $versions=explode("<br>",$available_versions);
  
  foreach($versions as $version){
  
    $versionnumber=explode("-",$version);
    $versionnumber=explode(".tar.gz",$versionnumber[1]);
  
    if($versionnumber[0] > getVersion()){
      if(!file_exists($_SERVER['DOCUMENT_ROOT']."/downloads/".$version)){
      
        $dst=$_SERVER['DOCUMENT_ROOT']."/downloads/".$version;

        copy($src.$version,$dst);
      }
    }
  }

}

function backupCurrent(){

  require("../version.php");
  require("config.php");
  require("connect.php");
  
  $install_path=$_SERVER['DOCUMENT_ROOT'].$klubpath;
  
  if(file_exists($install_path."/backups/dommerplan-".date('d-m-y')."-".getVersion())){
   $backups=glob($install_path."/backups/dommerplan-".date('d-m-y')."-*");
   
   foreach($backups as $backup){
    $backupexplode = explode("-",$backup);
    $numbers[] = $backupexplode[5]; 
   }
   
   $newbackup=max($numbers) + 1;
   
   $backup_dir=$install_path."/backups/dommerplan-".date('d-m-y')."-".getVersion()."-".$newbackup;
   
  }else{
   $backup_dir=$install_path."/backups/dommerplan-".date('d-m-y')."-".getVersion();
  } 
  $backup_file="$backup_dir/admin/sql/backup.sql";
  
  if(!is_dir($backup_dir)){
    shell_exec("mkdir -p ".$backup_dir."/admin/sql");
    system("mysqldump --opt -h$db_host -u$db_user -p$db_pass $db_database > $backup_file");
    shell_exec("cp ".$install_path."/* ".$backup_dir);
    shell_exec("cp -a ".$install_path."/admin ".$backup_dir);
    
    return "Backup created..";

  }else{
  
    return "Backup already exists!!";
  
  }

}

function backupRestore($backup){

  require("config.php");
  require("connect.php");

  $install_path=$_SERVER['DOCUMENT_ROOT'].$klubpath;

  if(file_exists($install_path."/backups/".$backup."/admin/sql/backup.sql")){
  
   $sql = "SHOW TABLES FROM $db_database";
   if($result = mysql_query($sql)){
    while($row = mysql_fetch_row($result)){
      $found_tables[]=$row[0];
    }
   }else{
    die("Error, could not list tables. MySQL Error: " . mysql_error());
   }
  
   foreach($found_tables as $table_name){
    $sql = "DROP TABLE $db_database.$table_name";
    if($result = mysql_query($sql)){
      //echo "Success - table $table_name deleted.";
    }else{
      echo "Error deleting $table_name. MySQL Error: " . mysql_error() . "";
    }
   }
  
   $sql = explode(';', file_get_contents ($install_path."/backups/".$backup."/admin/sql/backup.sql"));
  
   $n = count ($sql) - 1;
   for ($i = 0; $i < $n; $i++) {
    $query = $sql[$i];
    $result = mysql_query ($query) or die ('<p>Query: <br><tt>' . $query . '</tt><br>failed. MySQL error: ' . mysql_error());
   }
  
   shell_exec("cp -a ".$install_path."/backups/".$backup."/* ".$install_path."/");
  }

}

function applyUpdate($version){

  require("config.php");
  require("connect.php");

  $install_path=$_SERVER['DOCUMENT_ROOT'].$klubpath;
  
  if(is_dir($install_path."/tmp")){
  
    shell_exec("rm -rf ".$install_path."/tmp");
  
  }
  
  
  shell_exec("mkdir ".$install_path."/tmp");
  
  copy($install_path."/downloads/dommerplan-".$version.".tar.gz", $install_path."/tmp/dommerplan-".$version.".tar.gz");
  
  shell_exec("cd ".$install_path."/tmp && tar zxvf dommerplan-".$version.".tar.gz && rm dommerplan-".$version.".tar.gz");
  
  if(file_exists($install_path."/tmp/admin/sql/update.sql")){
  
    $sql = explode(';', file_get_contents ($install_path."/tmp/admin/sql/update.sql"));
    $n = count ($sql) - 1;
    for ($i = 0; $i < $n; $i++) {
      $query = $sql[$i];
      $result = mysql_query ($query) or die ('<p>Query: <br><tt>' . $query . '</tt><br>failed. MySQL error: ' . mysql_error());
    }
    
  }
  
  shell_exec("cp -a ".$install_path."/tmp/* ".$install_path);
  

}

checkInstallation();


if(isset($_GET['doBackup'])){

 backupCurrent();
 $message='<font color="green">Backup oprettet...</font>';

}

if(isset($_GET['removeBackup'])){
 
 $install_path=$_SERVER['DOCUMENT_ROOT'].$klubpath;
 
 if(is_dir($install_path."/backups/".$_GET['removeBackup'])){
  shell_exec("rm -rf ".$install_path."/backups/".$_GET['removeBackup']);
  $message='<font color="green">Backup fjernet...</font>';
 }

}

if(isset($_GET['restoreBackup'])){

 backupRestore($_GET['restoreBackup']);
 
 $message='<font color="green">System reetableret fra Backup...</font>';

}

if(isset($_POST['message'])){

 switch($_POST['message']){
 
 case "restoreBackup":
  $message='<font color="green">Backup reetableret...</font>';
  break;
 case "removeBackup";
  $message='<font color="green">Backup slettet...</font>';
  break;
 }
}

getThemeHeader();

echo 'function Confirm(choice,input)

{

switch (choice){
case "removeBackup": 
question = "Er du sikker på at du vil slette denne backup??";
parameter = "removeBackup=" + input + "&message=removeBackup";
break;
case "restoreBackup":
question = "Er du sikker på at du vil reetabler denne backup??, alle kampændringer vil gå tabt..";
parameter = "restoreBackup=" + input + "&message=restoreBackup";
break;
default: ;
}

answer = confirm(question);
      
   if (answer !=0){
                   
      location = "http://'.$klubadresse.'/'.$klubpath.'/admin/upgrade.php?"+parameter;
   }           
                                                         
}'; 

getThemeTitle("Versionsstyring");

require("menu.php");

echo $message;

if(!isset($_GET['status'])){

$install_path=$_SERVER['DOCUMENT_ROOT'].$klubpath;

$updates = scandir($install_path."/downloads/");

$backups = scandir($install_path."/backups/");

echo '<center><table>';
echo '<tr>';
echo '<td width=400>';
echo 'Tilgængelig Opdateringer:';
echo '</td>';
echo '<td width=400>';
echo 'Backups:';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td valign="top">';
foreach($updates as $update){
  $name=explode("-",$update);
  if($name[0]=="dommerplan")
   echo "$update<br>";
}

echo '</td>';
echo '<td valign="top">';
foreach($backups as $backup){
  $name=explode("-",$backup);
  if($name[0]=="dommerplan"){
   echo "Version ".$name[4]." taget d. ".$name[1]."/".$name[2]."-".$name[3];
   if(isset($name[5]))
    echo " rev. $name[5]";
    echo ' - <a href="javascript:Confirm(\'restoreBackup\',\''.$backup.'\')"">Reetabler</a>';
    echo ' - <a href="javascript:Confirm(\'removeBackup\',\''.$backup.'\')">Fjern</a><br>';
   /*echo ' - <a href="http://' . $klubadresse . $klubpath . '/admin/upgrade.php?restoreBackup='.$backup.'">Reetabler</a>';
   echo ' - <a href="http://' . $klubadresse . $klubpath . '/admin/upgrade.php?removeBackup='.$backup.'">Fjern</a><br>';*/
  }
}

echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td height=10px>';
echo '</td>';
echo '<td>';
echo '</td>';
echo '</tr>';
echo '<tr>';
echo '<td>';
echo 'hej';
echo '</td>';
echo '<td>';
echo '<a href="http://' . $klubadresse . $klubpath . '/admin/upgrade.php?doBackup=1">Opret ny Backup</a>';
echo '</td>';
echo '</tr>';
echo '</table></center>';

}


getThemeBottom();

                
//fetchUpgrades("http://localhost/files/");

//checkInstallation();

//backupRestore("test");

//applyUpdate("1.0.1");

//backupCurrent();



?>
