<?php

$file = WEBROOT_PATH.'/site/config/constants/site.php';
if (!Config::getConstant($file, 'ACCESS_MODE', true)) {
  Config::replaceConstant(
    $file,
    'ACCESS_MODE',
    'all'
  );
}
