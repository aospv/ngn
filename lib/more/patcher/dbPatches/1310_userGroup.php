<?php

q("ALTER TABLE `userGroup`  ADD COLUMN `text` TEXT NOT NULL AFTER `image`");
