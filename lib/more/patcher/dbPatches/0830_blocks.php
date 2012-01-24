<?php

q("ALTER TABLE `page_blocks` ADD `static` INT( 1 ) NOT NULL DEFAULT '1' AFTER `type`");

q("ALTER TABLE `page_blocks` DROP INDEX `ownPageId_3`");

q("ALTER TABLE `page_blocks` DROP INDEX `ownPageId_2`");

q("ALTER TABLE `page_blocks` DROP INDEX `ownPageId`");

q("ALTER TABLE `page_blocks` DROP INDEX `id`");
