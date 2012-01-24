<?php

q("ALTER TABLE `page_blocks` CHANGE `static` `global` INT( 1 ) NOT NULL DEFAULT '1'");
