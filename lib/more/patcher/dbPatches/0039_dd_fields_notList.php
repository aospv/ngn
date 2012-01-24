<?php

q("ALTER TABLE `dd_fields` ADD `notList` INT( 1 ) NOT NULL DEFAULT '0' AFTER `maxlength`");
