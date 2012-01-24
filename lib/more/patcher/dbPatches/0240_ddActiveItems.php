<?php

q('CREATE TABLE IF NOT EXISTS `dd_items` (
  `pageId` int(11) NOT NULL,
  `itemId` int(11) NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY  (`pageId`,`itemId`)
) NGN=InnoDB DEFAULT CHARSET=utf8;
');

foreach (db()->ddTables() as $table) {
  foreach (db()->query("SELECT id AS itemId, pageId, active FROM $table") as $v) {
    db()->query('REPLACE INTO dd_items SET ?a', $v);
  }
}
