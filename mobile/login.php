<?PHP

//add slashes to the username and md5() the password
$user = addslashes($_POST['username']);
$pass = md5($_POST['password']);

require("../admin/connect.php");
require("theme.php");

$dbHost = $db_host;
$dbUser = $db_user;
$dbPass = $db_pass;
$dbDatabase = $db_database;

if(!mysql_num_rows(mysql_query("select * from users WHERE `id`=1"))){

 mysql_query("INSERT INTO `users` (`id`,`name`,`password`,`admin`) VALUES ('1','admin',md5('dommer'),'1')");
 $message = "<br><br><font color=\"red\">Standard Bruger er: 'admin', med koden: 'dommer'</font><br><br>";
  
}
  

//set the database connection variables
if ((isset($_POST['username'])) && ($_POST['username'] != "")){

//connet to the database

$db = mysql_connect("$dbHost", "$dbUser", "$dbPass") or die ("Error connecting to database.");

mysql_select_db("$dbDatabase", $db) or die ("Couldn't select the database.");

if(!mysql_num_rows(mysql_query("select * from users WHERE `id`=1", $db))){

 mysql_query("INSERT INTO `users` (`id`,`name`,`password`,`admin`) VALUES ('1','admin',md5('dommer'),'1')",$db);
 $message = "<br><br><font color=\"red\">Standard Bruger er: 'admin', med koden: 'dommer'</font><br><br>";

}

$result=mysql_query("select * from users where name='$user' AND password='$pass'", $db);

//check that at least one row was returned

$rowCheck = mysql_num_rows($result);
if($rowCheck > 0){
while($row = mysql_fetch_array($result)){

  //start the session and register a variable
  
    session_start();
    $_SESSION['username'] = $user;
      //session_register('username');
      
        //successful login code will go here...
              ob_start();          
            //we will redirect the user to another page where we will make sure they're logged in
              $config=mysql_fetch_assoc(mysql_query("SELECT * FROM config WHERE id = '1'"));
              if (($config['klubadresse']=="") || ($config['klubpath']=="") || ($config['klubnavn']=="")){
               header( "Location: configuration.php" );
              }else{
               header( "Location: index.php" );
              }
              ob_flush();
                }
                
                  }
                    else {
                    
                      //if nothing is returned by the query, unsuccessful login code goes here...
                      
                        echo 'Incorrect login name or password. Please try again.';
                          }
                         }

getThemeHeader();

?>

function formfocus() {
  document.getElementById('username').focus();
}
window.onload = formfocus;

<?php
getThemeTitle("Dommerplan Mobil");


echo $message; 

?>
<form method="POST" action="login.php">
<table width="100%">
<tr>
<td width="2%">
</td>
<td>
<font size="40px">Brugernavn:</font>
</td>
</tr>
<tr>
<td width="2%"> 
</td>
<td>
<input type="text" id="username" name="username" style="font-size:50px;height:100px;width:96%;">
</td>
</tr>
<tr>
<td width="2%"> 
</td>
<td>
<font size="40px">Password:</font>
</td>
</tr>
<tr>
<td width="2%"> 
</td> 
<td>
<input type="password" name="password" style="font-size:50px;height:100px;width:96%;">
</td>
</tr>
<tr>
<td width="2%"> 
</td> 
<td> 
<input type="submit" value="Login" name="login" style="font-size:60px;height:150px;width:96%;">
</td>
</tr>
</table>
</form>
<?php
getThemeBottom();

?>