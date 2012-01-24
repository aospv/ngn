<?php

foreach (db()->ddTables() as $table) {
  db()->query(
    "UPDATE $table SET active = 1 WHERE datePublish < ? AND active = 0",
    date('Y-m_d H:i:s'));
}
