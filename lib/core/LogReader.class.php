<?php

class LogReader {

  /**
   * Парсит лог-файл и возвращает массив
   *
   * @param   string  Имя лог-файла
   * @return  array
   */
  static public function get($name) {
    $r = array();
    foreach (explode('=====+=====', file_get_contents(LOGS_PATH.'/r_'.$name.'.log')) as $v) {
      if (!preg_match('/(\d+.\d+.\d+ \d+.\d+.\d+): \((.*)\)\n(.*)\n<body>(.*)<\/body>\n<trace>(.*)<\/trace>/ms', $v, $m))
        continue;
      $params = explode(', ', $m[3]);
      $i['time'] = strtotime($m[1]);
      $i['body'] = $m[4];
      $i['trace'] = $m[5];
      foreach ($params as $param) {
        $p = explode(': ', $param);
        $i[$p[0]] = $p[1];
      }  
      $r[] = $i;
    }
    return empty($r) ? array() : Arr::sort_by_order_key($r, 'time', SORT_DESC);
  }

  static public function logs() {
    $logs = array();
    foreach (glob(LOGS_PATH.'/r_*') as $v) {
      $logs[] = preg_replace('/^r_(.*).log/', '$1', basename($v));
    }
    return $logs;
  }
  
  static public function delete($name) {
    unlink(LOGS_PATH.'/r_'.$name.'.log');
  }
  
  static public function cleanup($name) {
    file_put_contents(LOGS_PATH.'/r_'.$name.'.log', '');
  }
  
}
