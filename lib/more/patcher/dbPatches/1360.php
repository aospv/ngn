<?php

q("ALTER TABLE `users`  ADD COLUMN `phone` BIGINT(13) NULL DEFAULT NULL AFTER `email`;");
