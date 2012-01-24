<?php

q("ALTER TABLE `page_blocks` ADD `colN` INT( 2 ) NOT NULL DEFAULT '0' AFTER `oid`");
