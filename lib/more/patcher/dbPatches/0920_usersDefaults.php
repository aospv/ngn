<?php

q("ALTER TABLE `users` CHANGE `passClear` `passClear` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");
q("ALTER TABLE `users` CHANGE `lastIp` `lastIp` VARCHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");
q("ALTER TABLE `users` CHANGE `mat` `mat` INT( 6 ) NOT NULL DEFAULT '0'");
q("ALTER TABLE `users` CHANGE `sex` `sex` ENUM( 'm', 'w', '' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");
q("ALTER TABLE `users` CHANGE `access` `access` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");
q("ALTER TABLE `users` CHANGE `actCode` `actCode` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");
q("ALTER TABLE `users` CHANGE `userDataPageId` `userDataPageId` INT( 11 ) NOT NULL DEFAULT '0'");
