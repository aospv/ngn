<?php

q("DROP TABLE IF EXISTS `rss_guid_storage`;");

q("
CREATE TABLE IF NOT EXISTS `grabber_keys` (
  `k` varchar(255) NOT NULL,
  `dateCreate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
