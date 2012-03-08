<?php

// NGN core
require_once NGN_PATH.'/init/core.php';

// Определение этой константы должно проходить в "project/site/config/constants/core"
if (PROJECT_KEY == '')
  die('Constant PROJECT_KEY is empty');

// Для удачной инициализации NGN необходимо, что бы были определены
// следующие константы:
if (!is_dir(NGN_PATH))
  die('Dir "'.NGN_PATH.'" not exists');

// Проверка конфигурации веб-сервера:
// ================================================
// Проверка наличия установленного mod_rewrite
// @todo в некоторых случаях эта функция не существует. Например в апаче, 
// встроенном в Zend Studio 6. Для него не учитываем данную ситуацию ошибки
if (function_exists('apache_get_modules') and !in_array('mod_rewrite', apache_get_modules()))
  die('Apache module "mod_rewrite" is not installed');

if (!function_exists('imagecreate'))
  die('Extension "gd" is not loaded');
if (!function_exists('mb_strstr'))  
  die('Extension "mbstring" is not loaded');
if (!function_exists('mysql_connect'))  
  die('Extension "mysql" is not loaded');
if (!function_exists('finfo_file'))  
  die('Extension "fileinfo" is not loaded');
  
// Проверка версии PHP
list($ver) = explode('.', phpversion());
if ($ver < 5)
  die("Minimal PHP version for NGN is 5.0.4. Your version is ".phpversion());
  
// Проверка установки short_open_tag = On в php.ini
if (! ini_get('short_open_tag'))
  die("Change the value of php.ini short_open_tag = On");

// Статические константы (должны быть определены до первого использования не подключенных классов)
require_once LIB_PATH.'/more/config.php';
require_once LIB_PATH.'/more/config.php';

if (!is_writable(SITE_PATH.'/'.DATA_DIR.'/cache'))
  die('"'.SITE_PATH.'/'.DATA_DIR.'/cache" is not writable (init/more.php)');

// Включаем кэширование списка классов
// Кэшировать нужно с помощью NgnCache. Значит нужно его подключить
if (!defined('DATA_PATH'))
  define('DATA_PATH', SITE_PATH.'/'.DATA_DIR);
  
// Константы темы
require LIB_PATH.'/more/stm/constants.php';

require_once LIB_PATH.'/core/NgnCache.class.php';
// Очитка кэша. Нельзя помещать в web-init, потому что web-init включается уже после 
// включения кэширования библиотек
if (isset($_REQUEST['cc'])) NgnCache::clean();

// Переключаем загрузку классов на кэширующий метод
Lib::$isCache = true;

Err::noticeSwitch(true);

if (isset($_REQUEST['cc'])) {
  Memc::clean();
  UrlCache::clearCache();
  SFLM::clearJsCssCache();
}

require LIB_PATH.'/more/common/date.func.php';
require LIB_PATH.'/more/common/tpl.func.php';