<?php

foreach (db()->select('SELECT id, settings FROM pages') as $v) {
  $v['settings'] = unserialize($v['settings']);
  if (!empty($v['settings']['strName'])) {
    db()->query('UPDATE pages SET strName=? WHERE id=?d',
      $v['settings']['strName'], $v['id']);
  }
}
