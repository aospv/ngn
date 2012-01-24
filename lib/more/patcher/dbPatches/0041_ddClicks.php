<?php

foreach (db()->ddTables() as $t) {
  if (!db()->fieldExists($t, 'clicks'))
    q("ALTER TABLE `$t` ADD `clicks` INT( 11 ) NOT NULL DEFAULT '0' AFTER `userId`");
}
