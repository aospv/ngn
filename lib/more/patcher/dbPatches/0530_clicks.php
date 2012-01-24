<?php

foreach (db()->ddTables() as $table) {
  db()->query("UPDATE $table SET clicks=0");
}
