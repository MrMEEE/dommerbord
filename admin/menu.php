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

echo '
<link href="http://' . $klubadresse . $klubpath . '/admin/css/dropdown.css" media="screen" rel="stylesheet" type="text/css" />
<link href="http://' . $klubadresse . $klubpath . '/admin/css/default.advanced.css" media="screen" rel="stylesheet" type="text/css" />

<!-- <body class="vimeo-com">-->

<ul id="nav" class="dropdown dropdown-horizontal">';

$link = "Dommerplan";
if ((getSite() != "/") && (getSite() != "/index.php")){
	$url = '<a href="http://' . $klubadresse . $klubpath . '/admin/">';
}else{
	$url = '<a href="#">';
}

echo '
<li class="first"><a href="#"><font color="#172322">.</font></a></li>
<li class="dir">Dommerplan
	<ul>
		<li class="first"><a href="http://' . $klubadresse . $klubpath . '/admin/sync.php">Opdater Kampprogram</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/">Kommende Kampe</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/index.php?view=unassigned">Utildelte Kampe</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/index.php?view=moved">Flyttede Kampe</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/index.php?view=cancelled">Aflyste Kampe</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/index.php?view=all">Sæsonens Kampe</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/statistic.php">Dommer Statistik</a></li>
		<li class="last"><a href="http://' . $klubadresse . $klubpath . '/admin/addgame.php">Tilføj Kamp Manuelt</a></li>
				
	</ul>
</li>
<li class="dir">Klubben	
	<ul>
		<li class="first"><a href="http://' . $klubadresse . $klubpath . '/admin/people.php">Tilføj/Vis Hold/Personer</a></li>
		<li class="last"><a href="http://' . $klubadresse . $klubpath . '/admin/addallsources.php">Tilføj/Vis alle klubbens hold</a></li>
	</ul>
</li>';

if (checkAdmin($_SESSION['username'])){
echo '
<li class="dir">Administration
	<ul>
		<li class="first"><a href="http://' . $klubadresse . $klubpath . '/admin/configuration.php">Konfiguration</a></li>
		<li><a href="http://' . $klubadresse . $klubpath . '/admin/users.php">Brugere</a></li>
		<li class="last"><a href="http://' . $klubadresse . $klubpath . '/admin/upgrade.php">Backup/Opdater</a></li>
	</ul>
</li>';	
}

foreach (glob($_SERVER['DOCUMENT_ROOT'].$klubpath."/admin/plugins/*/*_pluginmenu.php") as $filename) {
	include($filename);
}

echo'
<li class="last"><a href="http://' . $klubadresse . $klubpath . '/admin/logout.php" class="top_link"><span>Log ud</span></a></li>
</ul>
<br><br><br><br>
<!--</body>-->';

?>
