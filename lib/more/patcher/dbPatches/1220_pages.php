<?php

q("ALTER TABLE `pages`  CHANGE COLUMN `dateModif` `dateUpdate` DATETIME NOT NULL AFTER `dateCreate`;");
