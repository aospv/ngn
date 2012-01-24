<?php

q("
CREATE TABLE IF NOT EXISTS `dd_titles` (
  `itemId` int(11) NOT NULL,
  `title` varchar(255) default '',
  `strName` varchar(50) NOT NULL,
  `pageId` int(11) NOT NULL,
  UNIQUE KEY `itemId` (`itemId`,`strName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

q("TRUNCATE TABLE dd_titles");

foreach (db()->ddTables() as $table) {
  if (db()->fieldExists($table, 'title')) {
    $d = db()->query("SELECT id AS itemId, title, pageId FROM $table");
  } else {
    $d = db()->query("SELECT id AS itemId, pageId FROM $table");
  }
  foreach ($d as $row) {
    $row['strName'] = str_replace('dd_i_', '', $table);
    db()->query('INSERT INTO dd_titles SET ?a', $row);
  }
}