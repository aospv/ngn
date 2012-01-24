<?php

define('CORE_PAGE_MODULES_DIR', 'cpm');
define('CORE_PAGE_MODULES_PATH', LIB_PATH.'/'.CORE_PAGE_MODULES_DIR);

define('STATIC_DIR', 'i');
define('STATIC_PATH', NGN_PATH.'/'.STATIC_DIR);

setConstant('SITE_LIB_PATH', SITE_PATH.'/lib');

/**
 * Путь к каталогу с шаблонами по умолчанию
 */
define('TPL_PATH', NGN_PATH.'/tpl');
define('SITE_BASE_TPL_PATH', NGN_PATH.'/tpl/site/base');

/**
 * Путь к каталогу с шаблонами проекта
 */
define('SITE_TPL_PATH', SITE_PATH.'/tpl');

define('SITE_PAGE_MODULES_DIR', 'spm');
define('SITE_PAGE_MODULES_PATH', SITE_LIB_PATH.'/'.SITE_PAGE_MODULES_DIR);


define('TEMP_PATH', SITE_PATH.'/temp');

if (!defined('SITE_DOMAIN')) {
  if (isset($_SERVER['HTTP_HOST']))
    define('SITE_DOMAIN', $_SERVER['HTTP_HOST']);
}

if (!defined('SITE_DOMAIN') or !constant('SITE_DOMAIN'))
  throw new NgnException('Constant SITE_DOMAIN can not by empty');
  
define('SITE_WWW', 'http://'.SITE_DOMAIN);

if (!defined('LOGS_PATH')) {
  // Абсолютный путь к каталогу с логами
  define('LOGS_PATH', SITE_PATH.'/'.LOGS_DIR);
}

/**
 * Абсолютный путь до каталога загружаеммых на сервер файлов
 */
define('UPLOAD_PATH', WEBROOT_PATH.'/'.UPLOAD_DIR);

define('INLINE_IMAGES_DIR', 'ii');
define('INLINE_IMAGES_THUMB_DIR', 'ii_thmb');
define('INLINE_IMAGES_TEMP_DIR', 'ii_tmp');

define('PAGE_PATH_SEP', '.');

if (isset($_SERVER['REMOTE_ADDR']) and $_SERVER['REMOTE_ADDR'] == '127.0.0.1' and ! defined('IS_DEBUG'))
  define('IS_DEBUG', true);

ini_set('magic_quotes_gpc', 0);

define('INCLUDE_PATH_SEPARATOR', ';');

////////////////////////////////////////////////////////////////////

define('REGISTERED_USERS_ID', -1);
define('ALL_USERS_ID', -2);

define('VIDEO_1_W', 320);
define('VIDEO_1_H', 240);
define('VIDEO_2_W', 640);
define('VIDEO_2_H', 480);
define('VIDEO_3_W', 720);
define('VIDEO_3_H', 480);

