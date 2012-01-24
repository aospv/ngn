<?php

q("
ALTER TABLE `subs_emails` ADD `id` INT( 11 ) NOT NULL AUTO_INCREMENT FIRST ,
ADD PRIMARY KEY ( `id` ) ,
ADD UNIQUE (
`id` 
)
");
