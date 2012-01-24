<?php

db()->query('ALTER TABLE `tags` ADD `name` VARCHAR( 255 ) NOT NULL AFTER `title`');
foreach (db()->query('SELECT id, title FROM tags') as $v) {
  db()->query('UPDATE tags SET name=? WHERE id=?d', Misc::translate($v['title'], true), $v['id']);
}
