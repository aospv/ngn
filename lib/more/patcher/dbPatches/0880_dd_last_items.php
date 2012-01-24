<?php

q("ALTER TABLE `dd_last_items` CHANGE `itemId` `itemId` INT( 11 ) NOT NULL");
q("ALTER TABLE `dd_last_items` DROP INDEX `id`");
