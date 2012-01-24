<?php

q("ALTER TABLE `tags`  ADD COLUMN `dateCreate` DATETIME NOT NULL AFTER `cnt`,  ADD COLUMN `dateUpdate` DATETIME NOT NULL AFTER `dateCreate`");
