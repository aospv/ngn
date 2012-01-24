<?php

q("ALTER TABLE `dd_structures` ADD `dateCreate` DATETIME NOT NULL AFTER `indx` ,
ADD `dateUpdate` INT NOT NULL AFTER `dateCreate`");
