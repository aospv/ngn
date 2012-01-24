<?php

foreach (db()->tables() as $t) {
  if (!strstr($t, 'dd_i_')) continue;
  foreach (db()->fields($t) as $f) {
    if ($f == 'staticId') {
      db()->renameField($t, $f, 'static_id');
    }
  }
}
db()->query("UPDATE dd_fields SET name='static_id', system=1 WHERE name='staticId'");