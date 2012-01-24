<?php
q('ALTER TABLE `comments` CHANGE `nick` `nick` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL');
