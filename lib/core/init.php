<?php

if (!defined('NGN_PATH')) die('NGN_PATH not defined'); // @LibStorageRemove
define('LIB_PATH', NGN_PATH . '/lib');                  // @LibStorageRemove
require_once LIB_PATH.'/core/common.func.php';

setConstant('VENDORS_PATH', dirname(NGN_PATH).'/vendors'); // @LibStorageRemove

define('CHARSET', 'UTF-8');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', 'utf8_general_ci');

require_once LIB_PATH.'/core/R.class.php'; // Registry Class
require_once LIB_PATH.'/core/LogWriter.class.php';
require_once LIB_PATH.'/core/Err.class.php';
require_once LIB_PATH.'/more/common/misc.func.php';
require_once LIB_PATH.'/core/Dir.class.php'; // Directory processing functions
require_once LIB_PATH.'/core/Arr.class.php'; // Array processing functions
require_once LIB_PATH.'/core/File.class.php'; // File processing functions
require_once LIB_PATH.'/core/Misc.class.php'; // Miscellaneous functions
require_once LIB_PATH.'/core/Lib.class.php'; // Librarys, classes

date_default_timezone_set('Europe/Moscow');

// Важно! До установки Lib::$isCache = true никаких обращений к классам 
// без предварительного подключения быть не должно
spl_autoload_register(array('Lib', 'required'));         // @LibStorageRemove

Err::$show = true;

if (!file_exists(VENDORS_PATH))                                 // @LibStorageRemove
  die('Folder "'.VENDORS_PATH.'" does not exists (core/init)'); // @LibStorageRemove

// Здесь ищим сторонние библиотеки
define('INCL_PATH_DELIMITER', getOS() == 'win' ? ';' : ':');
set_include_path(VENDORS_PATH.INCL_PATH_DELIMITER.get_include_path());  // @LibStorageRemove

set_exception_handler(array('Err', 'exceptionHandler'));
set_error_handler(array('Err', 'errorHandler'));

// ------------------- config ------------------

/**
 * Каталог с логами
 */
define('LOGS_DIR', 'logs');

/**
 * Каталог с данными
 */
define('DATA_DIR', 'data');

/**
 * Каталог для загружаеммых на сервер файлов
 */
define('UPLOAD_DIR', 'u');
