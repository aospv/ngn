<?php

q("ALTER TABLE `storeCart`  ADD COLUMN `cnt` INT(11) NOT NULL DEFAULT '0' AFTER `itemId`;");