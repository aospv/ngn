<?php

q("
CREATE TABLE IF NOT EXISTS `grabber_channels` (
  `id` int(11) NOT NULL auto_increment,
  `oid` int(5) NOT NULL,
  `type` enum('html','rss') NOT NULL,
  `pageId` int(11) NOT NULL,
  `data` text NOT NULL,
  `attempts` int(6) NOT NULL default '0',
  `active` int(1) NOT NULL default '0',
  `dateCreate` datetime NOT NULL,
  `dateLastGrab` datetime NOT NULL,
  `dateLastCheck` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
");
