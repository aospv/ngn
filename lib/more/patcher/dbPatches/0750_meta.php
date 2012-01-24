<?php

q("CREATE TABLE `pages_meta` (
`pageId` INT( 11 ) NOT NULL ,
`title` VARCHAR( 255 ) NOT NULL ,
`description` VARCHAR( 255 ) NOT NULL ,
`keywords` VARCHAR( 255 ) NOT NULL 
) ENGINE = InnoDB");

q("ALTER TABLE `pages_meta` ADD PRIMARY KEY ( `pageId` );");

q("CREATE TABLE `dd_meta` (
`itemId` INT( 11 ) NOT NULL ,
`strName` INT( 11 ) NOT NULL ,
`title` VARCHAR( 255 ) NOT NULL ,
`description` VARCHAR( 255 ) NOT NULL ,
`keywords` VARCHAR( 255 ) NOT NULL 
) ENGINE = InnoDB;");

q("ALTER TABLE `dd_meta` ADD PRIMARY KEY ( `itemId` , `strName` ) ;");
