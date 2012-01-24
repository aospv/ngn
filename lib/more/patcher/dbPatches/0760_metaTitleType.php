<?php

q("ALTER TABLE `pages_meta` ADD `titleType` ENUM( 'add', 'replace' ) NOT NULL AFTER `title`");
q("ALTER TABLE `dd_meta` ADD `titleType` ENUM( 'add', 'replace' ) NOT NULL AFTER `title`");
