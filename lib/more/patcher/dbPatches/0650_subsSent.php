<?php

q("CREATE TABLE IF NOT EXISTS `subs_sent` (
  `id` int(11) NOT NULL,
  `type` enum('emails','users') NOT NULL,
  `subsId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
