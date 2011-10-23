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
   <a href="http://www.bmsbasket.dk/dommer/statistik.php">Statistik</a>
   <br>
      <?php
      $query = mysql_query("SELECT games.*,r1.name as refereeteam1, r2.name as refereeteam2, t1.name as tableteam1, t2.name as tableteam2, t3.name as tableteam3 FROM `games` LEFT JOIN teams r1 ON games.refereeteam1id = r1.id LEFT JOIN teams r2 ON games.refereeteam2id = r2.id LEFT JOIN teams t1 ON games.tableteam1id = t1.id LEFT JOIN teams t2 ON games.tableteam2id = t2.id LEFT JOIN teams t3 ON games.tableteam3id = t3.id WHERE CURDATE() <= `date` ORDER BY `date`,`time` ASC");
      $todos = array();
      $lastweek = 0;

           
      while($row = mysql_fetch_assoc($query)){
	      $date=substr($row['date'],0,4);
              $date.=substr($row['date'],5,2);
              $date.=substr($row['date'],8,2);
              $todos[] = new ToDo($row,$lastweek);
              $lastweek = date("W",strtotime($date));
              
      }
      foreach($todos as $item){
        echo $item;
      }
      echo '</tbody>
      </table>';
echo 'I år forsøger vi at få endnu bedre styr på dommer og dommerbords tjanser. Lige som sidste år inddeler vi de enkelte hold i grupper. Trænerne på holdne vil hjælpe med at fordele tjanserne internt i grupperne. Der bliver meget mere kontrol og opfølgning og alle planer og statistikker vil ligge på denne side og løbende blive opdateret.

Bestyrelsen deler vagt tjansen mellem sig. Her neden for kan du se hvem der har vagten i denne måned. Vagtens ansvar er at sikre at planen er opdateret og følge op på trænerne. Trænernes ansvar er at sikre aftalerne med spillere, samt at finde afløsere ved sygdom og lignende. Spillerens ansvar er at møde op til tjanserne samt at finde afløsere internt i gruppen hvis de bliver forhindret. Opfølgning og tilbagemeldinger er nøglen til at det i år skal lykkedes at få dommer og dommerbordet til at virke.
<table class="wp-table-reloaded wp-table-reloaded-id-1">
<tbody>
<tr>
<td style="background-color: #56a7a9; width: 121px;" valign="top">Måned</td>
<td style="background-color: #56a7a9; width: 314px;" valign="top">Ansvarlig</td>
<td style="background-color: #56a7a9; width: 217px;" valign="top">Mobil nummer</td>
</tr>
<tr>
<td><span style="text-decoration: line-through;">September</span></td>
<td><span style="text-decoration: line-through;">Lars Nygaard</span></td>
<td><span style="text-decoration: line-through;">2128 1077</span></td>
</tr>
<tr>
<td><span style="text-decoration: line-through;">Oktober</span></td>
<td><span style="text-decoration: line-through;">Søren Hjortflod</span></td>
<td><span style="text-decoration: line-through;">2948 2859</span></td>
</tr>
<tr>
<td><span style="text-decoration: line-through;">November</span></td>
<td><span style="text-decoration: line-through;">Barbara Nedic</span></td>
<td><span style="text-decoration: line-through;">6060  2958</span></td>
</tr>
<tr>
<td>December</td>
<td>Mads Philipsen</td>
<td>2896 1235</td>
</tr>
<tr>
<td>Januar</td>
<td>Betina Højman</td>
<td>2058 3207</td>
</tr>
<tr>
<td>Februar</td>
<td>Lars Nygaard</td>
<td>2128 1077</td>
</tr>
<tr>
<td>Marts</td>
<td>Barbara Nedic</td>
<td>6060  2958</td>
</tr>
<tr>
<td>April</td>
<td>Søren Hjortflod</td>
<td>2948 2859</td>
</tr>
<tr>
<td>Maj</td>
<td>Mads Philipsen</td>
<td>28961235</td>
</tr>
<tr>
<td>Juni</td>
<td>Betina Højman</td>
<td>2058 3207</td>
</tr>
</tbody>
</table>';

      ?>
      
</div>
</div>



<?php get_sidebar(); ?>

<?php get_footer(); ?>
