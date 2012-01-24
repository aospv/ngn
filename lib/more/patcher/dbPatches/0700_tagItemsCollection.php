<?php

q("ALTER TABLE `tags_items` ADD `collection` INT( 2 ) NOT NULL DEFAULT '0'");
