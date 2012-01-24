<?php

q("TRUNCATE TABLE `events`");
q("ALTER TABLE `events` ADD `id` INT( 11 ) NOT NULL FIRST");
q("ALTER TABLE `events` ADD UNIQUE (`id`)");
q("ALTER TABLE `events` CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT");