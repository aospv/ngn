<?php

foreach (db()->ddTables() as $table) {
  foreach (db()->select("SELECT id, dateCreate FROM $table") as $v) {
    db()->query("UPDATE $table SET datePublish=? WHERE id=?d", $v['dateCreate'], $v['id']);
  }
}