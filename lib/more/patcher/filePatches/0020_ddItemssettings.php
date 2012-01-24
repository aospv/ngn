<?php

foreach (glob(WEBROOT_PATH.'/site/config/vars/tplDdItemsSettings*') as $file)
  rename($file, str_replace('tplDdItemsSettings', 'ddoItemsShow', $file));
