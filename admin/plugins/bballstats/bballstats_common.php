<?php

function statsorderlist(){
          $query = mysql_query("SHOW COLUMNS FROM `bballstats_stats`");
          while($stats = mysql_fetch_assoc($query)){
                    if(($stats['Field']!="id") && ($stats['Field']!="spiller") && ($stats['Field']!="kampid")){
                              $statsorder[] = $stats['Field'];
                    }
          }
          return $statsorder;
}


?>
