<?php

class Url {
  
  static public $headers;
  static public $contents;
  static public $charset = 'utf-8';
  static public $cache = false;
  
  const INIT_ALL = 1;
  const INIT_HEADER = 2;
  
  private static function init($url) {
    if (self::$cache and isset(self::$headers[$url]))
      return;
    $urlp = parse_url($url);
    $urlp['port'] = isset($urlp['port']) ? $urlp['port'] : 80;
    if (! $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))
      return false;
    if (! $result = socket_connect($socket, $urlp['host'], $urlp['port'])) {
      return false;
    }
    // ------------ Header ------------
    if (isset($urlp['path']))
      $link = isset($urlp['query']) ? $urlp['path'] . '?' . $urlp['query'] : $urlp['path'];
    else $link = '/';
    $in = "GET " . $link . " HTTP/1.0\r\n";
    $in .= "Host: " . $urlp['host'] . "\r\n";
    $in .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
    $in .= "Accept-Language: ru,en-us;q=0.7,en;q=0.3\r\n";
    $in .= "Accept-Encoding: deflate\r\n";
    $in .= "Accept-Charset: " . self::$charset . ";q=0.7,*;q=0.7\r\n";
    $in .= "Keep-Alive: 300\r\n";
    $in .= "Connection: keep-alive\r\n";
    $in .= "User-agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9) Gecko/2008052906 Firefox/3.0\r\n";
    $in .= "Connection: Close\r\n\r\n";
    $out = '';
    socket_write($socket, $in, strlen($in));
    while (($o = @socket_read($socket, 2048)) !== false)
      $out .= $o;
    if (!$out) {
      self::$contents[$url] = '';
      return;
    }
    @socket_close($socket);
    $out = str_replace("\r", '', $out);
    $header = substr($out, 0, strpos($out, "\n\n"));
    //$header = explode("\n", $header);
    self::$headers[$url] = explode("\n", $header);
    // ------------ Content ------------ 
    $content = substr($out, strpos($out, "\n\n") + 2, strlen($out));
    preg_match('/Content-Type:.*charset=(.*)/i', $header, $m);    
    $charset = isset($m[1]) ? $m[1] : 'windows-1251'; // @todo непонятно почему именно windows-1251
    // нужно как-то узнать всё же кодировку, 
    // если её в заголовках нет
    if ($charset != self::$charset)
      $content = iconv($charset, self::$charset, $content);
    self::$contents[$url] = $content;
  }
  
  static public function exists($url) {
    $code = self::getCode($url);
    if ($code[0] == '2' or $code[0] == '3') return true;
    return false;
  }

  static public function getCode($url) {
    self::init($url);
    return preg_replace('/^[^\s]+ (\d+) .*$/', '$1', self::$headers[$url][0]);
  }

  static public function getContents($url) {
    self::init($url);
    return self::$contents[$url];
  }

  
  static public function copy($url, $to) {
    $out = fopen($to, 'wb'); 
    if ($out == false) { 
      print "File not opened<br>"; 
      exit; 
    } 
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); // 4 минуты
    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
    
    curl_setopt($ch, CURLOPT_FILE, $out);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
     
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_exec($ch); 
    //echo "<br>Error is : ".curl_error($ch); 
    curl_close($ch); 
  }
  

  /**
   * Обращается к ссылке, и если ты возвращает FLASE либо цифру, значит 
   * "потрогать" не удалось 
   *
   * @param   string  Ссылка, которую нужно "потрогать"
   * @return  bool
   */
  static public function touch($url, $outputResultHtml = false) {
    output("Touching url '$url'");
    file_get_contents($url); return true;
    $code = 200;
    $text = Url::getContents($url);
    if (preg_match('/<b>(Fatal error|Warning|Error)<\/b>: (.*)<br \/>/', $text, $m)) {
      output("{$m[1]} '".trim(strip_tags($m[1]))."' on url '$url':\n$text");
      return false;
    }
    if (!$code) {
      output("Touching url '$url'.");
      return false;
    }
    if ($code[0] == 4) {
      output(
        "Touching url '$url' (code: $code).\n------ Page Text ------\n$text"); //
      return false;
    }
    if ($outputResultHtml) output("Result output:\n--------------------\n$text");
    return true;
  }
  
}
