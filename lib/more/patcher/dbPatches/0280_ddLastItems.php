<?

q('CREATE TABLE IF NOT EXISTS `dd_last_items` (
  `id` int(11) NOT NULL auto_increment,
  `hash` varchar(41) NOT NULL,
  `userId` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `hash` (`hash`,`userId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;');