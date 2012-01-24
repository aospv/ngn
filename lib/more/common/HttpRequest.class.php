<?php

class HttpRequest {

  public function get($url) {
    $urlp = parse_url($url);
    $urlp['port'] = $urlp['port'] ? $urlp['port'] : 80;
    if (!$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP))
      return false;
    if (!$result = @socket_connect($socket, $urlp['host'], $urlp['port']))
      return false;
    $link = $urlp['query'] ? $urlp['path'] . '?' . $urlp['query'] : $urlp['path'];
    $in = "GET " . $link . " HTTP/1.0\r\n";
    $in .= "Host: " . $urlp['host'] . "\r\n";
    $in .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
    $in .= "Accept-Language: ru,en-us;q=0.7,en;q=0.3\r\n";
    $in .= "Accept-Encoding: deflate\r\n";
    $in .= "Accept-Charset: " . CHARSET . ";q=0.7,*;q=0.7\r\n";
    $in .= "Keep-Alive: 300\r\n";
    $in .= "Connection: keep-alive\r\n";
    //$in .= "User-agent: Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser; .NET CLR 1.1.4322)\r\n";
    $in .= "User-agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9) Gecko/2008052906 Firefox/3.0\r\n";
    $in .= "Connection: Close\r\n\r\n";
    $result = '';
    prr($in);
    socket_write($socket, $in, strlen($in));
    while ($o = @socket_read($socket, 2048)) $result .= $o;
    if (!$result) return false;
    @socket_close($socket);
    $result = str_replace("\r", '', $result);
    $header = substr($result, 0, strpos($result, "\n\n"));
    $content = substr($result, strpos($result, "\n\n") + 2, strlen($result));
    preg_match('/Content-Type:.*charset=(.*)/i', $header, $m);
    $charset = $m[1];
    if (!$charset) $charset = 'windows-1251'; // непонятно почему я сделал это... 
    // нужно как-то узнать всё же кодировку, 
    // если её в заголовках нет
    
    return $this->detectUTF8($this->get_parsed($result, '<title>', '</title>')) ?
      $result :
      iconv($charset, CHARSET, $result); 
  
  }

  function get_parsed($result, $bef, $aft="") 
  { 
    $line=1; 
    $len = strlen($bef); 
    $pos_bef = strpos($result, $bef); 
    if($pos_bef===false) 
      return ""; 
    $pos_bef+=$len; 
     
    if(empty($aft)) 
    { //try to search up to the end of line 
      $pos_aft = strpos($result, "\n", $pos_bef); 
      if($pos_aft===false) 
        $pos_aft = strpos($result, "\r\n", $pos_bef); 
    } 
    else 
      $pos_aft = strpos($result, $aft, $pos_bef); 
     
    if($pos_aft!==false) 
      $rez = substr($result, $pos_bef, $pos_aft-$pos_bef); 
    else 
      $rez = substr($result, $pos_bef); 
     
    return $rez; 
  }
    
  protected function detectUTF8($string) {
    return preg_match('%(?:
      [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
      |\xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
      |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
      |\xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
      |\xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
      |[\xF1-\xF3][\x80-\xBF]{3}         # planes 4-15
      |\xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
      )+%xs', 
    $string);
  }

}