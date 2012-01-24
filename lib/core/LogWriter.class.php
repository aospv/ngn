<?php

class LogWriter {

  /**
   * Записывает "var_export" в лог-файл. Лог-файл поддерживается LogReader'ом
   *
   * @param   string  Имя лога
   * @param   string  Строка, которую нужно записать в лог-файл
   * @param   array   Дополнительные параметры
   */
  static public function v($name, $var, array $trace = array(), array $params = array()) {
    self::html($name, "<pre>".var_export($var, true)."</pre>", $trace, $params);
  }

  /**
   * Записывает HTML в лог-файл. Лог-файл поддерживается LogReader'ом
   *
   * @param   string  Имя лога
   * @param   string  Строка, которую нужно записать в лог-файл
   * @param   array   Дополнительные параметры
   */
  static public function html($name, $html, array $trace = array(), array $params = array()) {
    $s = '(' . __FILE__ . ':' . __LINE__ . ")\n";
    if (isset($_SERVER['REQUEST_URI'])) {
      $s .= 'url: '.$_SERVER['REQUEST_URI'].
        ', referer: '.(!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
      if ($params) $s .= ', ';
    }
    if ($params) $s .= Tt::enum($params, ', ', '$k.`: `.$v');
    $s .= "\n";
    $s .= "<body>".$html."</body>";
    $s .= "\n<trace>".($trace ? _getBacktrace($trace) : getBacktrace())."</trace>";
    $s .= "\n=====+=====\n";
    self::str('r_'.$name, $s);
  }

  /**
   * Записывает строку в лог-файл. Лог-файл не поддерживается LogReader'ом
   *
   * @param   string  Имя лога
   * @param   string  Строка, которую нужно записать в лог-файл
   */
  static public function str($name, $str, $logsPath = null) {
    if (defined('DO_NOT_LOG') and DO_NOT_LOG === true) return;
    if (!defined('LOGS_PATH')) return;
    $str = date('d.m.Y H:i:s') . ': ' . $str . "\n";
    $dir = $logsPath ? $logsPath : LOGS_PATH;
    if (!is_dir($dir))
      die("Error: Logs dir '$dir' does not exists. Define LOGS_PATH constant");
    $fp = fopen($dir.'/'.$name.'.log', 'a');
    fwrite($fp, $str);
    fclose($fp);
  }

}