<?php

q('ALTER TABLE `pages` ADD `module` VARCHAR( 50 ) NOT NULL AFTER `controller`');
