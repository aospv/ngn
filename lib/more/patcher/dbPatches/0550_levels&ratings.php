<?php

q("CREATE TABLE IF NOT EXISTS `level_items` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `type` enum('dd','comments') NOT NULL,
  `strName` varchar(50) NOT NULL,
  `weight` int(2) NOT NULL default '1',
  `usedLevel` int(2) NOT NULL default '0',
  `dateCreate` datetime NOT NULL,
  PRIMARY KEY  (`id`,`userId`,`type`,`strName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

q("CREATE TABLE IF NOT EXISTS `level_users` (
  `userId` int(11) NOT NULL,
  `level` int(2) NOT NULL,
  `nominateDate` datetime NOT NULL,
  PRIMARY KEY  (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

q("CREATE TABLE IF NOT EXISTS `rating_dd_voted_ips` (
  `strName` varchar(50) NOT NULL,
  `itemId` int(11) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `voteDate` datetime NOT NULL,
  PRIMARY KEY  (`strName`,`itemId`,`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

q("CREATE TABLE IF NOT EXISTS `rating_dd_voted_users` (
  `strName` varchar(50) NOT NULL,
  `itemId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `voteDate` datetime NOT NULL,
  PRIMARY KEY  (`strName`,`itemId`,`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
