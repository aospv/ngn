<?php

q("ALTER TABLE `userStoreSettings`  ADD COLUMN `dateCreate` DATETIME NULL AFTER `settings`,  ADD COLUMN `dateUpdate` DATETIME NULL AFTER `dateCreate`");
