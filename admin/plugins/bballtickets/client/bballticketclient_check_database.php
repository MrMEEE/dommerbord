<?php

if(!mysql_num_rows(mysql_query("SHOW TABLES LIKE 'bballtickets_config'"))){
      mysql_query("CREATE TABLE `bballtickets_config` (`id` int(11) NOT NULL AUTO_INCREMENT, `hold` text NOT NULL, `template` text NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
}
if(!mysql_num_rows(mysql_query("SHOW TABLES LIKE 'bballticketclient_config'"))){
      mysql_query("CREATE TABLE `bballticketclient_config` (`id` int(11) NOT NULL AUTO_INCREMENT, `masterurl` text NOT NULL, `clientname` text NOT NULL, `clientid` text NOT NULL,`clientpass` text NOT NULL, `lastupdate` text NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
      $clientid=uniqid();
      $clientpass=uniqid();
      mysql_query("INSERT INTO `bballticketclient_config` (`id`,`clientid`,`clientpass`) VALUES ('1','$clientid','$clientpass')");
}
if(!mysql_num_rows(mysql_query("SHOW COLUMNS FROM `bballtickets_courts`"))){
      mysql_query("CREATE TABLE `bballtickets_courts` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` text NOT NULL,`address` text NOT NULL, `seats` int(11) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
      mysql_query("CREATE TABLE `bballtickets_seatgroups` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` text NOT NULL,`seats` int(11) NOT NULL, `court` int(11) NOT NULL, `priority` int(11) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
      mysql_query("CREATE TABLE `bballtickets_tickettypes` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` text NOT NULL,`seats` text NOT NULL,`group` text NOT NULL, `expires` date NOT NULL, `access` text NOT NULL ,PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
      mysql_query("CREATE TABLE `bballtickets_tickets` (`id` int(11) NOT NULL AUTO_INCREMENT, `name` text NOT NULL, `type` int(11) NOT NULL, `suspended` tinyint(1) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
      mysql_query("CREATE TABLE `bballtickets_checkins` (`id` int(11) NOT NULL AUTO_INCREMENT, `game` int(11) NOT NULL, `code` text NOT NULL, `status` int(11) NOT NULL, `seatgroup` int(11) NOT NULL, `new` text NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
      mysql_query("CREATE TABLE `games` (`id` int(8) unsigned NOT NULL AUTO_INCREMENT, `position` int(8) unsigned NOT NULL DEFAULT '0', `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '', `dt_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, `date` date NOT NULL, `time` time NOT NULL, `refereeteam1id` int(8) NOT NULL, `referee1name` text COLLATE utf8_unicode_ci NOT NULL, `refereeteam2id` int(8) NOT NULL, `referee2name` text COLLATE utf8_unicode_ci NOT NULL, `tableteam1id` int(8) NOT NULL, `table1id` int(8) NOT NULL, `tableteam2id` int(8) NOT NULL, `table2id` int(8) NOT NULL, `tableteam3id` int(8) NOT NULL, `table3id` int(8) NOT NULL, `status` int(8) NOT NULL, `place` text COLLATE utf8_unicode_ci NOT NULL, `homegame` tinyint(1) NOT NULL, `team` text COLLATE utf8_unicode_ci NOT NULL, `result` text COLLATE utf8_unicode_ci NOT NULL, PRIMARY KEY (`id`), KEY `position` (`position`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1000016");      
      mysql_query("CREATE TABLE `calendars` ( `id` int(11) NOT NULL AUTO_INCREMENT, `address` varchar(255) NOT NULL, `team` varchar(255) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");
}

?>
