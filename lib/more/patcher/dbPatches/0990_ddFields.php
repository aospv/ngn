<?php

q("ALTER TABLE `dd_fields` ADD `active` INT(1) NOT NULL DEFAULT '1' AFTER `defaultDisallow`");
q("ALTER TABLE `dd_fields` ADD `dateCreate` DATETIME NOT NULL AFTER `active`");
q("ALTER TABLE `dd_fields` ADD `dateUpdate` DATETIME NOT NULL AFTER `dateCreate`");
