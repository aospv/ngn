<?php

q("CREATE TABLE IF NOT EXISTS `subs_emails` (
  `listId` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

q("CREATE TABLE IF NOT EXISTS `subs_list` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `useUsers` int(1) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

q("CREATE TABLE IF NOT EXISTS `subs_returns` (
  `subsId` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `type` enum('users','emails') NOT NULL,
  `returnDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

q("CREATE TABLE IF NOT EXISTS `subs_subscribes` (
  `id` int(11) NOT NULL auto_increment,
  `subsDate` datetime NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
