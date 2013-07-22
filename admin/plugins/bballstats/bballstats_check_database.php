<?php

if(!mysql_num_rows(mysql_query("SHOW TABLES LIKE 'bballstats_config'"))){
      mysql_query("CREATE TABLE `bballstats_config` (`id` int(11) NOT NULL AUTO_INCREMENT, `hold` text NOT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
      mysql_query("INSERT INTO `bballstats_config` SET `id`=1,`hold`=''");
      mysql_query("CREATE TABLE `bballstats_players` (`id` int(11) NOT NULL AUTO_INCREMENT, `hold` int(11) NOT NULL , `fornavn` text NOT NULL,`efternavn` text NOT NULL, `beskrivelse` text NOT NULL, `nummer` text NOT NULL, `position` text NOT NULL, `photo` text NOT NULL,  PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
      mysql_query("CREATE TABLE `bballstats_stats` (`id` int(11) NOT NULL AUTO_INCREMENT, `spiller` int(11) NOT NULL , `kampid` int(11) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
}

if(!mysql_num_rows(mysql_query("SHOW COLUMNS FROM `bballstats_players` LIKE 'photo';"))){
      mysql_query("ALTER TABLE `bballstats_players` ADD `photo` TEXT NOT NULL");
}
?>
