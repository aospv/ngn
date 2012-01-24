<?php

q("CREATE TABLE IF NOT EXISTS `subs_users` (
  `userId` int(11) NOT NULL,
  `listId` int(11) NOT NULL,
  PRIMARY KEY  (`userId`,`listId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
