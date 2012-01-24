<?php

q("ALTER TABLE `users` CHANGE `actCode` `actCode` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");

foreach (db()->selectCol('SELECT id FROM users') as $id)
  db()->query('UPDATE users SET actCode=? WHERE id=?d', Misc::randString(), $id);