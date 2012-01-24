<?php

q("ALTER DATABASE `".DB_NAME."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

q("DROP TABLE `pages_meta`");
q("DROP TABLE `dd_meta`");

q("CREATE TABLE IF NOT EXISTS `pages_meta` (
  `pageId` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `titleType` enum('add','replace') NOT NULL,
  `description` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  PRIMARY KEY  (`pageId`)
) ENGINE=InnoDB");

q("CREATE TABLE IF NOT EXISTS `dd_meta` (
  `itemId` int(11) NOT NULL,
  `strName` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `titleType` enum('add','replace') NOT NULL,
  `description` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  PRIMARY KEY  (`itemId`,`strName`)
) ENGINE=InnoDB");