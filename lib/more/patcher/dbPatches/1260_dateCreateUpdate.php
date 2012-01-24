<?php

q("ALTER TABLE `slices`  ADD COLUMN `dateCreate` DATETIME NOT NULL AFTER `pageId`,  ADD COLUMN `dateUpdate` DATETIME NOT NULL AFTER `dateCreate`");
q("ALTER TABLE `pageBlocks`  ADD COLUMN `dateCreate` DATETIME NOT NULL AFTER `pageId`,  ADD COLUMN `dateUpdate` DATETIME NOT NULL AFTER `dateCreate`");