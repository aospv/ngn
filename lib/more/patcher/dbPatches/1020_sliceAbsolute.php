<?php

q("ALTER TABLE `slices` ADD `absolute` INT( 1 ) NOT NULL DEFAULT '0' AFTER `type`");
