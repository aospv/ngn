<?php

q("ALTER TABLE `subs_subscribes` CHANGE `subsDate` `subsBeginDate` DATETIME NOT NULL");

q("ALTER TABLE `subs_subscribes` ADD `subsEndDate` DATETIME NOT NULL AFTER `subsBeginDate`");

q("
CREATE TABLE IF NOT EXISTS `subs_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  `oid` int(11) NOT NULL,
  `type` enum('users','emails') NOT NULL,
  `subsId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
