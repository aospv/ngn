<?php

q("
CREATE TABLE IF NOT EXISTS `sound_play_time_log` (
  `strName` varchar(50) NOT NULL,
  `itemId` int(11) NOT NULL,
  `userId` varchar(255) NOT NULL,
  `sec` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
