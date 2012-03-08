<?php

if (!defined('DATA_PATH'))
  throw new NgnException('Site Theme cannot be loaded before set of DATA_PATH constant');

define('STM_WPATH', 'i/stm');
define('STM_PATH', NGN_PATH.'/'.STM_WPATH);

define('STM_DESIGN_WPATH', 'i/stm/design');
define('STM_DESIGN_PATH', NGN_PATH.'/'.STM_DESIGN_WPATH);

define('STM_MENU_WPATH', 'i/stm/menu');
define('STM_MENU_PATH', NGN_PATH.'/'.STM_MENU_WPATH);

define('STM_THEME_WPATH', 'i/stm/themes');
define('STM_THEME_PATH', NGN_PATH.'/'.STM_THEME_WPATH);

define('STM_DATA_PATH', DATA_PATH.'/stm');
