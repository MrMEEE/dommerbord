<?php

if(isset($_GET["teamselect"])){
  $teamselect=$_GET["teamselect"];
}else{
  $teamselect="Alle";
}

require("admin/connect.php");

?>

<script type="text/javascript">
function FormSubmit(el) {
document.teamlist.action = 'index.php?teamselect=' + el.value;
document.teamlist.submit();
return;
}
</script>
<br>
<div id="content" class="narrowcolumn">    
<div id="main">
    <?php
	require "admin/config.php";
	require "admin/todo.view.class.php";
        $result=mysql_query("select id, name from teams order by name asc");
        if($teamselect=="Alle"){
              $teamlist.= "<option value=\"Alle\" selected>Alle</option>";
        }else{
              $teamlist.= "<option value=\"Alle\">Alle</option>";
        }
        
        while(list($id, $name)=mysql_fetch_row($result)) {
              
              if($id!=9999){
              if($teamselect==$id){
              $teamlist.= "<option value=\"".$id."\" selected>".$name."</option>";
              }else{
              $teamlist.= "<option value=\"".$id."\">".$name."</option>";
              }
              }
        }
    ?>
   <table width=545 border=0> 
   <tr>
   <td>
   <a href="http://<?php echo $klubadresse; ?>/<?php echo $klubpath; ?>/statistik.php">Statistik</a><br>
   <a href="http://<?php echo $klubadresse; ?>/<?php echo $klubpath; ?>/ical.php">Kalendere</a>
   </td>
   <td align="right">
   <form method="post" name="teamlist">
    <select name="teamselect" onChange="FormSubmit(this)">
    <?php echo $teamlist;
    ?>
 </select>
    </form>
    </td>
    </tr>
    </table>
  
<br>
      <?php
      if($teamselect=="Alle"){
      $query = mysql_query("SELECT games.*,r1.name as refereeteam1, r2.name as refereeteam2, t1.name as tableteam1, t2.name as tableteam2, t3.name as tableteam3 FROM `games` LEFT JOIN teams r1 ON games.refereeteam1id = r1.id LEFT JOIN teams r2 ON games.refereeteam2id = r2.id LEFT JOIN teams t1 ON games.tableteam1id = t1.id LEFT JOIN teams t2 ON games.tableteam2id = t2.id LEFT JOIN teams t3 ON games.tableteam3id = t3.id WHERE CURDATE() <= `date` AND `homegame`='1' ORDER BY `date`,`time` ASC");
      }else{
      $query = mysql_query("SELECT games.*,r1.name as refereeteam1, r2.name as refereeteam2, t1.name as tableteam1, t2.name as tableteam2, t3.name as tableteam3 FROM `games` LEFT JOIN teams r1 ON games.refereeteam1id = r1.id LEFT JOIN teams r2 ON games.refereeteam2id = r2.id LEFT JOIN teams t1 ON games.tableteam1id = t1.id LEFT JOIN teams t2 ON games.tableteam2id = t2.id LEFT JOIN teams t3 ON games.tableteam3id = t3.id WHERE CURDATE() <= `date` AND (refereeteam1id = ".$teamselect." OR refereeteam2id = ".$teamselect." OR tableteam1id = ".$teamselect." OR tableteam2id = ".$teamselect." OR tableteam3id = ".$teamselect.") AND `homegame`='1' ORDER BY `date`,`time` ASC");
      }
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

      ?>
      
</div>
</div>
