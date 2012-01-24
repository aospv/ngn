<?php

q("ALTER TABLE `rating_dd_voted_ips` ADD `votes` INT(5) NOT NULL AFTER `voteDate`");
q("ALTER TABLE `rating_dd_voted_users` ADD `votes` INT(5) NOT NULL AFTER `voteDate`");
