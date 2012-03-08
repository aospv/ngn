<?php

q("ALTER TABLE `storeCart`  ADD COLUMN `dateUpdate` DATETIME NOT NULL AFTER `cnt`;");