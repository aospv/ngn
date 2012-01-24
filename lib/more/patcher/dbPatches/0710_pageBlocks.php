<?php

q("ALTER TABLE `page_blocks` CHANGE `method` `type` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
