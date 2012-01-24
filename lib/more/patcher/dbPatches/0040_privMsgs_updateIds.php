<?php

q("ALTER TABLE `priv_msgs` CHANGE `to_user_id` `toUserId` INT( 11 ) NOT NULL DEFAULT '0'");
q("ALTER TABLE `priv_msgs` CHANGE `from_user_id` `fromUserId` INT( 11 ) NOT NULL DEFAULT '0'");
q("ALTER TABLE `priv_msgs` CHANGE `user_id` `userId` INT( 11 ) NOT NULL DEFAULT '0'");
q("ALTER TABLE `priv_msgs` CHANGE `pair_id` `pairId` INT( 11 ) NOT NULL DEFAULT '0'");