<?php

function getConstant($name) {
  if (defined($name)) return constant($name);
  return false;
}

function setConstant($name, $value) {
  if (!defined($name)) define($name, $value);
}

function htmlspecialcharsR_a(&$item) {
  if (is_object($item))
    return;
  $item = htmlspecialchars($item);
}

function htmlspecialcharsR(&$data) {
  if (!is_array($data))
    return;
  array_walk_recursive($data, 'htmlspecialcharsR_a');
}

/**
 * Обёртка для HTML-вывода print_r()
 *
 * @param  $var
 */
function pr($var, $html = true, $trace = true) {
  if (!getConstant('IS_DEBUG')) return;
  if (R::get('plainText')) $html = false;
  if ($html) print '<pre>';
  if ($html) htmlspecialcharsR($var);
  print_r($var);
  if ($html) print '</pre>';
  if (!$html) print "\n";
  if ($trace) {
    print '<trace>';
    print getBacktrace($html);
    print '</trace>';
  }
  print $html ? '<hr />' : "-----------END-OF-PR----------\n";
}

function prr($var, $html = true) {
  pr($var, $html, false);
}

function getPrr($v, $html = true) {
  ob_start();
  prr($v, $html); // var_export($v, true); - в случаях рекурсий объектов вызывает fatal-ошибку
  $c = ob_get_contents();
  ob_end_clean();
  return $c;
}

function getPr($v, $html = true) {
  ob_start();
  pr($v, $html); // var_export($v, true); - в случаях рекурсий объектов вызывает fatal-ошибку
  $c = ob_get_contents();
  ob_end_clean();
  return $c;
}

function output($str, $output = false) {
  if (LOG_OUTPUT === true or $output)
    print (R::get('plainText') ? "" : "<p>").
      "LOG: ".$str.
      (R::get('plainText') ? "\n" : "</p>");
  LogWriter::str('output', $str);
}

function getBacktrace($html = true) {
  return _getBacktrace(debug_backtrace(), $html);
}

function _getBacktrace(array $trace, $html = true) {
  $s = '';
  for ($i = 0; $i < count($trace); $i++) {
    if (isset($trace[$i]['file'])) {
      $s .= 
        $trace[$i]['file'] . ':' . $trace[$i]['line'] . ($html ? '<br />' : "\n");
    }
  }
  return $s;
}

function getTraceText(Exception $e, $html = true) {
  $trace = array(
    array(
      'file' => $e->getFile(),
      'line' => $e->getLine()
    )
  );
  $s = ''; 
  foreach (Arr::append($trace, $e->getTrace()) as $v) {
    if (isset($v['file'])) {
      $s .= 
        $v['file'] . ':' . $v['line'] . ($html ? '<br />' : "\n");
    }
  }
  return $s;
}

function getTraceTextAndMessage(Exception $e, $html = true) {
  return $e->getMessage().
    ($html ? '<hr />' : "\n--------------------\n").
    getTraceText($e, $html);
}


function getFullTrace(Exception $e) {
  return Arr::append(
    array(
      array(
        'file' => $e->getFile(),
        'line' => $e->getLine()
      )
    ),
    $e->getTrace()
  );
}


function die2($t = '', $html = true) {
  pr($t, $html);
  die();
}

function die3($t) {
  die2(htmlspecialchars($t));
}

/**
 * Получает текущее значение секунд и милесекунд
 *
 * @return float
 */
function getMicrotime() {
  list($usec, $sec) = explode(" ", microtime());
  return ((float)$usec + (float)$sec);
}

function getProcessTime() {
  return getMicrotime() - R::get('processTimeStart');
}

/**
 * Возвращает тип операционной системы (win/unix)
 *
 * @return string win/unix
 */
function getOS() {
  if ((isset($_SERVER['SERVER_SOFTWARE']) and strstr($_SERVER['SERVER_SOFTWARE'], 'Win32')) or 
    (isset($_SERVER['OS']) and strstr($_SERVER['OS'], 'Win')))
    return 'win';
  else
    return 'linux';
}
  
function redirect($path) {
  $path = '/'.ltrim($path, '/');
  //$location = (!strstr($path, 'http://') ? O::get('Req')->getAbsBase().'/'.$path : $path);
  $location = $path;
  if (getConstant('JS_REDIRECT')) {
    header('Location: /c2/jsRedirect?r='.urlencode($location));
    print "\n"; // странность: если после хедера нет никакого вывода, редирект не осуществляется
    return;
  }
  header('Location: '.$location);
  print "\n"; // странность: если после хедера нет никакого вывода, редирект не осуществляется
}

function set_time_limit_q($n) {
  if (ini_get('safe_mode') or !function_exists('set_time_limit')) return;
  set_time_limit($n);
}
