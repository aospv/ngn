<?php

q("ALTER TABLE `slices` ADD `type` VARCHAR( 255 ) NOT NULL DEFAULT 'html' AFTER `text`");
