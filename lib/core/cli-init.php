<?php

set_time_limit(0);
setConstant('CLI', true);
setConstant('IS_DEBUG', true);
setConstant('LOG_OUTPUT', true);
setConstant('PROJECT_KEY', 'cli');
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL | E_STRICT);
R::set('plainText', true);
R::set('processTimeStart', getMicrotime());
R::set('showNotices', true);
$_SERVER['REQUEST_URI'] = '/';
