<?php

echo '
<li class="dir">Billetter
        <ul>
                  <li class="first"><a href="http://' . $klubadresse . $klubpath . '/admin/plugins/bballtickets/bballtickets_courts.php">Baner/Pladser</a></li>
                  <li><a href="http://' . $klubadresse . $klubpath . '/admin/plugins/bballtickets/bballtickets_tickettypes.php">Billettyper</a></li>
                  <li><a href="http://' . $klubadresse . $klubpath . '/admin/plugins/bballtickets/bballtickets_tickets.php">Billetter/Kort</a></li>
                  <li><a href="http://' . $klubadresse . $klubpath . '/admin/plugins/bballtickets/bballtickets_statistic.php">Statistik</a></li>
                  <li><a href="http://' . $klubadresse . $klubpath . '/admin/plugins/bballtickets/bballtickets_importexport.php">Import/Eksport</a></li>
                  <li class="last"><a href="http://' . $klubadresse . $klubpath . '/admin/plugins/bballtickets/bballtickets_config.php">Konfiguration</a></li>
        </ul>
</li>';

?>