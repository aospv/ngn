<?php

q("CREATE TABLE memcache (
k INT(250) NOT NULL,
v LONGTEXT NOT NULL,
INDEX (k)
) ENGINE = InnoDB;");
