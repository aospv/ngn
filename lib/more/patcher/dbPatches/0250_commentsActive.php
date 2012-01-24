<?php

q('ALTER TABLE `dd_items` CHANGE `pageId` `parentId` INT( 11 ) NOT NULL');
q('ALTER TABLE `dd_items` CHANGE `itemId` `id2` INT( 11 ) NOT NULL');
q('RENAME TABLE `dd_items` TO `comments_active`');
