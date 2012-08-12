<?php 

function getSite() {

 $uri = $_SERVER["REQUEST_URI"];
 if ( substr_count($uri, "?") > 0 ){
   $site = substr($uri,0,stripos($uri,"?"));
 }else{
   $site = $uri;
 }

 return strrchr($site, "/");

}

echo '<link rel="stylesheet" type="text/css" href="css/menu.css" />
<script src="js/menu.js" type="text/javascript"></script>

<span class="preload1"></span>
<span class="preload2"></span>

<ul id="nav">';

$link = "Dommerplan";
if ((getSite() != "/") && (getSite() != "/index.php")){
	$url = '<a href="http://' . $klubadresse . $klubpath . '/admin/" class="top_link">';
}else{
	$url = '<a href="#" class="top_link">';
}

echo '
<li class="top">'. $url .'<span>'. $link .'</span></a>
	<ul class="sub">
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/sync.php">Opdater Kampprogram</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/">Kommende Kampe</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/index.php?view=unassigned">Utildelte Kampe</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/index.php?view=moved">Flyttede Kampe</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/index.php?view=cancelled">Aflyste Kampe</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/index.php?view=all">Sæsonens Kampe</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/addgame.php">Tilføj Kamp Manuelt</a></li>
				
	</ul>
</li>
<li class="top"><a href="#" class="top_link"><span>Klubben</span></a>	
	<ul class="sub">
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/people.php">Tilføj/Vis Hold/Personer</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/addallsources.php">Tilføj/Vis alle klubbens hold</a></li>
	</ul>
</li>';

if (checkAdmin($_SESSION['username'])){
echo '
<li class="top"><a href="#" class="top_link"><span>Administration</span></a>
	<ul class="sub">
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/configuration.php">Konfiguration</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/users.php">Brugere</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/upgrade.php">Backup/Opdater</a></li>
	</ul>
</li>';	
}

foreach (glob("plugins/*_pluginmenu.php") as $filename) {
	include($filename);
}

echo'
<li class="top"><a href="http://' . $klubadresse . $klubpath . '/admin/logout.php" class="top_link"><span>Log ud</span></a></li>

<br><br><br><br>';

?>
