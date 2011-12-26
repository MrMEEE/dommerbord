<?php

require("connect.php");
require("config.php");

function checkInstallation(){
  
  require("config.php");
  
  $install_path=$_SERVER['DOCUMENT_ROOT'].$klubpath;
  
  if(is_dir($install_path."/upgrades")){
  
    //echo "Upgrades folder exists..";
  
  }else{
  
    echo "Upgrades folder does not exist, creating..";
    shell_exec("mkdir ".$install_path."/upgrades");
  
  }
  
  if(is_dir($install_path."/downloads")){
  
    //echo "Downloads folder exists..";
    
  }else{
  
    echo "Downloads folder does not exist, creating..";
    shell_exec("mkdir ".$install_path."/downloads");
    
  }
  
  if(is_dir($install_path."/backups")){
  
    //echo "Backups folder exists..";
  
  }else{
  
    echo "Backups folder does not exist, creating..";
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
  
  $backup_dir=$install_path."/backups/".date('d-m-y')."-".getVersion();
  
  $backup_file="$backup_dir/admin/sql/backup.sql";
  
  if(!is_dir($backup_dir)){
    shell_exec("mkdir -p ".$backup_dir."/admin/sql");
    echo "mysqldump --opt -h$db_host -u$db_user -p$db_pass $db_database > $backup_file";
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
//fetchUpgrades("http://localhost/files/");

//checkInstallation();

applyUpdate("1.0.1");

//backupCurrent();
/*
$file="http://localhost/files/test.tar.gz";

fetchUpgrade("$file");
$size = round((filesize($path)/1000000), 3);
print "transfer complete.<br>
<a><a href=\"$file\">$file</a><br>
<a><a href=\"$path\">$path</a> : $size MB";*/

//$_SERVER['DOCUMENT_ROOT']."/path2file/";shell_exec("");



?>