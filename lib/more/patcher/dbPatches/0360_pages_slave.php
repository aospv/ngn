<?php

q("ALTER TABLE `pages` ADD `slave` INT(1) NOT NULL DEFAULT '0' AFTER `active`");
