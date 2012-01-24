<?php

foreach (db()->ddTables() as $table) {
  $fields = db()->fields($table);
  if (!in_array('rating', $fields) or in_array('rating_average', $fields)) continue;
  db()->query("
  ALTER TABLE $table
    ADD rating_average INT(11) NOT NULL DEFAULT '0',
    ADD rating_grade INT(11) NOT NULL DEFAULT '0'
  ");
}
