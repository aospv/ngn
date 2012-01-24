<?php

q('ALTER TABLE `slices` ADD `title` VARCHAR( 255 ) NOT NULL AFTER `id`');
q('ALTER TABLE `slices` ADD `pageId` INT( 11 ) NOT NULL AFTER `id`');
