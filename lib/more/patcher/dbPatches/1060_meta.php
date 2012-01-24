<?php

q("ALTER TABLE `pages_meta` CHANGE `pageId` `id` INT( 11 ) NOT NULL");
q("ALTER TABLE `pages_meta` ADD `dateCreate` DATETIME NOT NULL AFTER `keywords` ,
ADD `dateUpdate` DATETIME NOT NULL AFTER `dateCreate`");

q("ALTER TABLE `dd_meta` CHANGE `itemId` `id` INT( 11 ) NOT NULL");
q("ALTER TABLE `dd_meta` ADD `dateCreate` DATETIME NOT NULL AFTER `keywords` ,
ADD `dateUpdate` DATETIME NOT NULL AFTER `dateCreate`");
