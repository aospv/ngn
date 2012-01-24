<?
q('ALTER TABLE `pages_log` ADD `dateCreate` DATETIME NOT NULL FIRST');
q('ALTER TABLE `pages_log` ADD `userId` INT( 11 ) NULL AFTER `memory`');
q('ALTER TABLE `pages_log` ADD `info` TEXT NOT NULL AFTER `userId`');