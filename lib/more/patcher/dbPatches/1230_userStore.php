<?php

q("CREATE TABLE IF NOT EXISTS `userStoreOrder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `data` text NOT NULL,
  `dateCreate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `dateUpdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

q("CREATE TABLE IF NOT EXISTS `userStoreOrderItems` (
  `itemId` int(11) DEFAULT NULL,
  `pageId` int(11) DEFAULT NULL,
  `orderId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

