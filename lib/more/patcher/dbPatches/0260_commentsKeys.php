<?php

q('ALTER TABLE `comments` ADD INDEX `allPublic` (`id2`, `dateCreate`, `active`)');
q('ALTER TABLE `comments` ADD INDEX `pagePublic` (`parentId`,`id2`,`dateCreate`,`active`)');
