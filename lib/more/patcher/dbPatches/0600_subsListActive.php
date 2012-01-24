<?php

q("ALTER TABLE `subs_list` ADD `active` INT( 1 ) NOT NULL DEFAULT '1' AFTER `id`");
