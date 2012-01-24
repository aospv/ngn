<?php

q('ALTER TABLE `pages` CHANGE `module` `controller` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
