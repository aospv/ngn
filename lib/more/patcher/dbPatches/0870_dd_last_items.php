<?php

q("TRUNCATE TABLE `dd_last_items`");
q("ALTER TABLE `dd_last_items` CHANGE `id` `itemId` INT( 11 ) NOT NULL AUTO_INCREMENT");
q("ALTER TABLE `dd_last_items` ADD `strName` VARCHAR( 50 ) NOT NULL AFTER `itemId`");
