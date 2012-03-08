<?php

require_once NGN_PATH.'/init/core.php';

// пока очень сложно точно разделить что такое more и site
require_once SITE_PATH.'/config/constants/more.php';
require_once SITE_PATH.'/config/constants/site.php';

if (!defined('SITE_SET') or SITE_SET == '') throw new Exception('SITE_SET not defined');

define('SITE_SET_TPL_PATH', NGN_PATH.'/tpl/site/siteSet/'.SITE_SET);