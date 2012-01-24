<?php

q("ALTER TABLE `grabber_channels` CHANGE `type` `type` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
