<?php

q('CREATE TABLE `comments_srt` (
  `id` INT( 11 ) NOT NULL ,
  `active` INT( 1 ) NOT NULL ,
  `parentId` INT( 11 ) NOT NULL ,
  `id2` INT( 11 ) NOT NULL
) ENGINE = InnoDB;');

q('ALTER TABLE `comments_srt` ADD INDEX ( `id` , `active` , `parentId` , `id2` )');

foreach (q('SELECT id, active, parentId, id2 FROM comments ORDER BY id DESC LIMIT 100') as $v) {
  db()->query('INSERT INTO comments_srt SET ?a', $v);
}
