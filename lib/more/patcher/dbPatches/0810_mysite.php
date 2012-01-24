<?php

q("ALTER TABLE `pages` ADD `mysite` INT(1) NOT NULL DEFAULT '0' AFTER `strName`");
