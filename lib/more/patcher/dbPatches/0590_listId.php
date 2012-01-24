<?php

q("ALTER TABLE `subs_subscribes` ADD `listId` INT( 11 ) NOT NULL AFTER `id`");
