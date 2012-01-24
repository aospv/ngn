<?php

foreach (db()->select('
SELECT tags_items.*, tags.tagId FROM tags_items
LEFT JOIN tags ON tags_items.name=tags.name AND
                  tags_items.id=tags.id AND
                  tags_items.type=tags.type
') as $v) {
  db()->query(
    'UPDATE tags_items SET tagId=?d WHERE name=? AND type=? AND id=?d',
    $v['tagId'], $v['name'], $v['type'], $v['id']);
}