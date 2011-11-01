<?PHP
//check that the user is calling the page from the login form and not accessing it directly
//and redirect back to the login form if necessary


//add slashes to the username and md5() the password
$user = addslashes($_POST['username']);
$pass = md5($_POST['password']);


//set the database connection variables
if ((isset($_POST['username'])) && ($_POST['username'] != "")){
require("connect.php");

$dbHost = $db_host;
$dbUser = $db_user;
$dbPass = $db_pass;
$dbDatabase = $db_database;

//connet to the database

$db = mysql_connect("$dbHost", "$dbUser", "$dbPass") or die ("Error connecting to database.");

mysql_select_db("$dbDatabase", $db) or die ("Couldn't select the database.");

$result=mysql_query("select * from users where name='$user' AND password='$pass'", $db);

//check that at least one row was returned

$rowCheck = mysql_num_rows($result);
if($rowCheck > 0){
while($row = mysql_fetch_array($result)){

  //start the session and register a variable
  
    session_start();
      session_register('username');
      
        //successful login code will go here...
              ob_start();          
            //we will redirect the user to another page where we will make sure they're logged in
              header( "Location: index.php" );
              ob_flush();
                }
                
                  }
                    else {
                    
                      //if nothing is returned by the query, unsuccessful login code goes here...
                      
                        echo 'Incorrect login name or password. Please try again.';
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

<form method="POST" action="login.php">
Bruger: <input type="text" name="username" size="20"><br>
Kode: <input type="password" name="password" size="20"><br>
<input type="submit" value="Login" name="login">
</form>


</body>
</html>                              