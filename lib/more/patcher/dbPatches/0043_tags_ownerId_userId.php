<?php

q('ALTER TABLE `tags` ADD `ownerId` INT( 11 ) NOT NULL AFTER `cnt`,
ADD `userId` INT( 11 ) NOT NULL AFTER `ownerId`');