<?php
/* Short and sweet */
require($_SERVER['DOCUMENT_ROOT'].'/wp-blog-header.php');
?>
   <?php get_header(); ?>
<?php if ( file_exists( TEMPLATEPATH . '/sidebar2.php') )
load_template( TEMPLATEPATH . '/sidebar2.php');
else
load_template( ABSPATH . 'wp-content/themes/default/sidebar.php');

?>
<br>
<div id="content" class="narrowcolumn">    
<div id="main">
    <?php
	require "admin/connect.php";
	require "admin/todo.view.class.php";
    ?>
   <h2>Dommerbordsplan</h2>
   <br>
      <?php
      $date = mysql_fetch_assoc(mysql_query("SELECT CURDATE() as date"));
      $year = substr($date['date'],0,4);
      $month = substr($date['date'],5,2);
      if($month<7){
      echo '<h3>Statistik for forårssæsonen '.$year.'</h3>
	    <br>
	    ';
	    $fromdate = "$year-01-01";
      }
      else{
      echo '<h3>Statistik for efterårssæsonen '.$year.'</h3>
	    <br>
	    ';
	    $fromdate = "$year-07-01";
      }
      $maxteamid = mysql_fetch_assoc(mysql_query("SELECT MAX(id) FROM teams"));
      $maxteamid = $maxteamid['MAX(id)'];

      $refstats=array();
      $tablestats=array();
      $refquery1 = mysql_query("SELECT refereeteam1id, COUNT( * ) as count FROM games WHERE date >= $fromdate AND status != 3 GROUP BY refereeteam1id");
      $refquery2 = mysql_query("SELECT refereeteam2id, COUNT( * ) as count FROM games WHERE date >= $fromdate AND status != 3 GROUP BY refereeteam2id");
      $tablequery1 = mysql_query("SELECT tableteam1id, COUNT( * ) as count FROM games WHERE date >= $fromdate AND status != 3 GROUP BY tableteam1id");
      $tablequery2 = mysql_query("SELECT tableteam2id, COUNT( * ) as count FROM games WHERE date >= $fromdate AND status != 3 GROUP BY tableteam2id");
      $tablequery3 =mysql_query("SELECT tableteam3id, COUNT( * ) as count FROM games WHERE date >= $fromdate AND status != 3 GROUP BY tableteam3id");
      
      while($refcount1 = mysql_fetch_assoc($refquery1)){
	$refstats[$refcount1['refereeteam1id']]+=$refcount1['count'];
      }
      while($refcount2 = mysql_fetch_assoc($refquery2)){
	$refstats[$refcount2['refereeteam2id']]+=$refcount2['count'];
      }
      while($tablecount1 = mysql_fetch_assoc($tablequery1)){
	$tablestats[$tablecount1['tableteam1id']]+=$tablecount1['count'];
      }
      while($tablecount2 = mysql_fetch_assoc($tablequery2)){
	$tablestats[$tablecount2['tableteam2id']]+=$tablecount2['count'];
      }
      while($tablecount3 = mysql_fetch_assoc($tablequery3)){
	$tablestats[$tablecount3['tableteam3id']]+=$tablecount3['count'];
      }
      
      $refcount2 = mysql_fetch_assoc($refquery2);
      $tablecount1 = mysql_fetch_assoc($tablequery1);
      $tablecount2 = mysql_fetch_assoc($tablequery2);
      $tablecount3 = mysql_fetch_assoc($tablequery3);

      echo '
      <table id="games" class="wp-table-reloaded wp-table-reloaded-id-1">
      <thead>
            <tr class="row-1 odd">
            <th class="column-1">Hold</th><th class="column-2">Dommertjanser</th><th class="column-3">Dommerbordstjanser</th>
            </tr>
      </thead>
      <tbody>';

      for ($i = 1; $i <= $maxteamid; $i++) {
	  $teamname=mysql_fetch_assoc(mysql_query("SELECT name FROM teams WHERE id=$i"));
	  $teamname=$teamname['name'];
	  if(($refstats[$i]==0 && $tablestats[$i]==0) || $teamname=="-" ){ //hide empty	  
	  }else{
	  echo '<tr class="row-2 even">
		<td class="column-1">'.$teamname.'</td><td class="column-2">'.$refstats[$i].'</td><td class="column-3">'.$tablestats[$i].'</td>
		</tr>';
	  }

      }
	  echo '<tr class="row-2 even">
		<td class="column-1">Ikke tildelte tjanser</td><td class="column-2">'.$refstats[0].'</td><td class="column-3">'.$tablestats[0].'</td>
		</tr>';
      echo '</tbody>
      </table>';


      ?>
      
</div>
</div>



<?php get_sidebar(); ?>

<?php get_footer(); ?>
