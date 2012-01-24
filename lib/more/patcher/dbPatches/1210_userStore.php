<?php

q("CREATE TABLE IF NOT EXISTS `storeCart` (
  `sessionId` varchar(255) DEFAULT NULL,
  `pageId` int(11) DEFAULT NULL,
  `itemId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

q("CREATE TABLE IF NOT EXISTS `userStoreSettings` (
  `id` int(10) DEFAULT NULL,
  `settings` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
