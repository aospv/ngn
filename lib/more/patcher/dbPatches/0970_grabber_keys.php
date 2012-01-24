<?php

q("CREATE TABLE IF NOT EXISTS `grabber_keys` (
  `strName` varchar(50) NOT NULL,
  `itemId` int(11) NOT NULL,
  `k` varchar(255) NOT NULL,
  `dateCreate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
