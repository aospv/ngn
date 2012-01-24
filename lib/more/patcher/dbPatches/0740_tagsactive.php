<?php

q("ALTER TABLE `tags_items` ADD `active` INT( 1 ) NOT NULL DEFAULT '1' AFTER `collection`");
