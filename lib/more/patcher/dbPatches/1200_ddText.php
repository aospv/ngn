<?php

foreach (db()->query("SELECT * FROM dd_fields WHERE type='text'") as $v) {
  $v['type'] = 'typoText';
  O::get('DdFieldsManager', $v['strName'])->update($v['id'], $v);
}

