<?php

q("CREATE TABLE IF NOT EXISTS `users_pages` (
  `userId` int(11) NOT NULL default '0',
  `pageId` int(11) NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `path` varchar(255) NOT NULL default '',
  `dateCreate` datetime NOT NULL,
  PRIMARY KEY  (`userId`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;");

q('ALTER TABLE `users_pages` CHANGE `title` `title` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL');
