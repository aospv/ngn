<?php

q('CREATE TABLE IF NOT EXISTS `slices` (
  `id` varchar(255) character set utf8 NOT NULL,
  `text` text character set utf8 NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB;');
