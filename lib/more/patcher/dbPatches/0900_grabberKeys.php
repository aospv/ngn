<?php

q("
CREATE TABLE IF NOT EXISTS `grabber_keys` (
  `k` varchar(255) NOT NULL,
  `dateCreate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

q("ALTER TABLE `grabber_keys` ADD `strName` VARCHAR( 50 ) NOT NULL FIRST ,
ADD `itemId` INT( 11 ) NOT NULL AFTER `strName`");
