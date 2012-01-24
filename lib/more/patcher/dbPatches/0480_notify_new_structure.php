<?php

q("DROP TABLE IF EXISTS `notify_subscribe_items`");
q("CREATE TABLE IF NOT EXISTS `notify_subscribe_items` (
  `userId` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `pageId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL,
  PRIMARY KEY  (`userId`,`type`,`pageId`,`itemId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

q("DROP TABLE IF EXISTS `notify_subscribe_pages`");
q("CREATE TABLE IF NOT EXISTS `notify_subscribe_pages` (
  `event` varchar(50) NOT NULL,
  `userId` int(11) NOT NULL,
  `pageId` int(11) NOT NULL,
  UNIQUE KEY `event` (`event`,`userId`,`pageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

q("DROP TABLE IF EXISTS `notify_subscribe_types`");
q("CREATE TABLE IF NOT EXISTS `notify_subscribe_types` (
  `id` int(11) NOT NULL auto_increment,
  `userId` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `dateCreate` datetime NOT NULL,
  `dateSent` datetime NOT NULL,
  PRIMARY KEY  (`userId`,`type`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
