<?php

foreach (db()->query("SELECT * FROM dd_fields WHERE type='textarea'") as $v) {
  $v['type'] = 'typoTextarea';
  O::get('DdFieldsManager', $v['strName'])->update($v['id'], $v);
}
