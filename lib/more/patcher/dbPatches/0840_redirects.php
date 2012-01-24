<?php

q("
CREATE TABLE redirects (
`type` VARCHAR( 50 ) NOT NULL ,
`userId` INT( 11 ) NOT NULL ,
`ip` VARCHAR( 15 ) NOT NULL ,
`url` VARCHAR( 255 ) NOT NULL 
) ENGINE = InnoDB;
");
