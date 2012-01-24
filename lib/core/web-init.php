<?php

set_time_limit(0);
define('IS_DEBUG', true);
define('LOG_OUTPUT', true);
define('PROJECT_KEY', 'web');
ini_set('display_errors', 'On');
ini_set('error_reporting', E_ALL | E_STRICT);
R::set('processTimeStart', getMicrotime());
R::set('showNotices', true);

