<?php

q('
CREATE TABLE IF NOT EXISTS `page_blocks` (
  `id` int(11) NOT NULL auto_increment,
  `oid` int(11) NOT NULL,
  `ownPageId` int(11) NOT NULL,
  `pageId` int(11) NOT NULL,
  `method` varchar(50) NOT NULL,
  `settings` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `ownPageId` (`ownPageId`),
  KEY `ownPageId_2` (`ownPageId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
');
