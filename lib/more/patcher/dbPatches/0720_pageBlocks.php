<?php

foreach (db()->select('SELECT id, pageId, settings FROM page_blocks') as $v) {
  $v['settings'] = unserialize($v['settings']);
  $v['settings']['pageId'] = $v['pageId'];
  db()->query('UPDATE page_blocks SET settings=? WHERE id=?d',
    serialize($v['settings']), $v['id']);
}
