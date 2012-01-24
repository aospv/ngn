<?php

q("ALTER TABLE `subs_subscribers` ADD INDEX ( `n` , `subsId` )");
