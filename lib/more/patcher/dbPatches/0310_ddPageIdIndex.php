<?php

foreach (db()->ddTables() as $table)
  q("ALTER TABLE $table ADD INDEX (pageId)");
