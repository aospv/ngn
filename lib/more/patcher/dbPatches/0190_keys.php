<?php

q('ALTER TABLE `pages` ADD INDEX ( `home` )');
q('ALTER TABLE `page_blocks` ADD INDEX ( `ownPageId` )');
