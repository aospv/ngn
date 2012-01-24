<?php

q("ALTER TABLE `subs_sent` CHANGE `id` `subscriberN` INT( 11 ) NOT NULL");
q("ALTER TABLE `subs_subscribers` ADD `status` ENUM( '', 'process', 'complete' ) NOT NULL");
