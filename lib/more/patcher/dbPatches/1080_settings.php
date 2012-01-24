<?php

q("ALTER TABLE `settings` CHANGE `name` `id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
