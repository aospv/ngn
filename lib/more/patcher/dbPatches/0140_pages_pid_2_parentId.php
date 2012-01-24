<?php

q("ALTER TABLE pages CHANGE pid parentId VARCHAR(11) NOT NULL DEFAULT '0'");