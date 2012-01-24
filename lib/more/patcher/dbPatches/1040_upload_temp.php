<?php

q("
CREATE TABLE IF NOT EXISTS `upload_temp` (
  `tempId` varchar(255) NOT NULL,
  `fieldName` varchar(255) NOT NULL,
  `fileName` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
