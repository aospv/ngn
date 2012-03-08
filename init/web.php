<?php

// На веб-страницах не нужно выводить лог, только если не определен параметр Output Log ('ol')
define('LOG_OUTPUT', isset($_REQUEST['ol']) ? true : false);

require_once NGN_PATH.'/init/more.php';

/*
 * Это для ZendDebugger'а. Он генерит следующее значение $_SERVER['argv']:
 * debug_fastfile=1&amp;use_remote=1&amp;debug_port=10137&amp;start_debug=1&amp;debug_start_session=1&amp;debug_session_id=1015&amp;send_sess_end=1&amp;debug_host=127.0.0.1&amp;/god/pages/69/137/editContent
 * Нужно взять значение path для NGN из этой строки
 */
if (isset($_REQUEST['debugUri'])) {
  $_SERVER['REQUEST_URI'] = $_REQUEST['debugUri'];
  $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
  $_SERVER['HTTP_HOST'] = 'localhost';
}

if (!is_dir(SITE_PATH) or !is_dir(SITE_PATH.'/config'))
  die('Dir "'.SITE_PATH.'" or "SITE_PATH/config" not exists');
if (!is_file(WEBROOT_PATH.'/index.php'))
  die('Dir "'.WEBROOT_PATH.'" or "index.php" not exists');

require_once NGN_PATH.'/config/version.php';

if (get_magic_quotes_gpc()) {
  function stripslashes_deep($value) {
    $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes(
      $value);
    return $value;
  }
  $_POST = array_map('stripslashes_deep', $_POST);
  $_GET = array_map('stripslashes_deep', $_GET);
  $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
  $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

R::set('processTimeStart', getMicrotime());

if (isset($_REQUEST['plain'])) R::set('plainText', true);
