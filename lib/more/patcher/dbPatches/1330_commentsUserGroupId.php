<?php

q("ALTER TABLE `comments_srt`  ADD COLUMN `userGroupId` INT(10) NULL AFTER `id2`,  ADD INDEX `userGroupId` (`userGroupId`)");
