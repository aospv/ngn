<?php

class Curl {
   
  public $getHeaders = false; // headers will be added to output 
  public $getContent = true; // contens will be added to output 
  public $followRedirects = true; // should the class go to another URL, if the current is "HTTP/1.1 302 Moved Temporarily" 
  public $encoding = 'utf-8';
  public $decodeResult = true;
  public $defaultInputEncoding = 'windows-1251';
  private $fCookieFile; 
  public $fSocket; 
   
  public function __construct() { 
    $this->fCookieFile = tempnam("/tmp", "g_");
    $this->init();
  } 

  protected function init() { 
    $this->fSocket = curl_init();
    $this->loadDefaults();
  } 
   
  protected function loadDefaults() { 
    $this->setopt(CURLOPT_RETURNTRANSFER, true); 
    $this->setopt(CURLOPT_FOLLOWLOCATION, $this->followRedirects); 
    $this->setopt(CURLOPT_REFERER, "http://google.com");
    $this->setopt(CURLOPT_VERBOSE, false);  
    $this->setopt(CURLOPT_SSL_VERIFYPEER, false); 
    $this->setopt(CURLOPT_SSL_VERIFYHOST, false); 
    $this->setopt(CURLOPT_HEADER, $this->getHeaders); 
    $this->setopt(CURLOPT_NOBODY, !$this->getContent); 
    $this->setopt(CURLOPT_COOKIEJAR, $this->fCookieFile); 
    $this->setopt(CURLOPT_COOKIEFILE, $this->fCookieFile); 
    $this->setopt(CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.0; WOW64) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.120 Safari/535.2"); 
  } 
  
  public function setopt($opt, $value) { 
    return curl_setopt($this->fSocket, $opt, $value); 
  } 

  public function destroy() { 
    return curl_close($this->fSocket); 
  } 

  public function head($url) { 
    if (!$this->fSocket) return false; 
    /*
    $this->getHeaders = true; 
    $this->getContent = false; 
    $this->loadDefaults();
    $this->setopt(CURLOPT_REFERER, Misc::getHostUrl($url)); 
    $this->setopt(CURLOPT_POST, 0); 
    $this->setopt(CURLOPT_CUSTOMREQUEST,'HEAD'); 
    $this->setopt(CURLOPT_URL, $url);
    */
    $this->setopt(CURLOPT_URL, $url);
    $this->setopt(CURLOPT_REFERER, Misc::getHostUrl($url));
    $this->setopt(CURLOPT_RETURNTRANSFER, 1); 
    $this->setopt(CURLOPT_HEADER, 1); 
    $this->setopt(CURLOPT_NOBODY, 1);
    $this->setopt(CURLOPT_FOLLOWLOCATION, 1); 
    $result = curl_exec($this->fSocket); 
    //$this->destroy(); 
    return $result; 
  }

  public function exists($url) {
    $t = $this->head($url);
    LogWriter::str('curl-ex_'.Misc::translate($url), $t);
    $headers = HttpResult::parseWithHeaders($t, false);
    return $headers[0]['Code'] == '200';
  }

  public function get($url) { 
    if (!$this->fSocket) return false;
    output('curl get: '.$url); 
    //$this->setopt(CURLOPT_HEADER, 1); 
    $this->setopt(CURLOPT_NOBODY, 0);
    $this->setopt(CURLOPT_POST, 0); 
    $this->setopt(CURLOPT_URL, $url);
    $result = $this->exec();
    return $this->decodeResult ? $this->convert($result) : $result;
  }
  
  public function copy_($url, $file) {
    if (!$this->fSocket) return false;
    if (!$this->exists($url))
      throw new NgnException("File '$url' does not exists");
    $lfile = fopen($file, "w");
    $this->setopt(CURLOPT_URL, $url);
    $this->setopt(CURLOPT_HEADER, 0);
    $this->setopt(CURLOPT_RETURNTRANSFER, 1);
    $this->setopt(CURLOPT_FILE, $lfile);
    $this->exec();
    fclose($lfile);
    $this->destroy();
    return true;        
  }
  
  public function copy($url, $file) {
    $this->setopt(CURLOPT_URL, $url);
    $this->setopt(CURLOPT_BINARYTRANSFER, 1);
    $this->setopt(CURLOPT_TIMEOUT, 320);
    file_put_contents($file, $this->exec());
  }

  public function post($url, $postData, $arr_headers = array()) { 
    if (!$this->fSocket) return false;
    $this->setopt(CURLOPT_HEADER, 1);
    $this->setopt(CURLOPT_POST, 1); 
    if (!empty($postData)) {
      $postData = $this->compilePostData($postData);
      $this->setopt(CURLOPT_POSTFIELDS, $postData);
    }
    if (!empty($arr_headers)) 
      $this->setopt(CURLOPT_HTTPHEADER, $arr_headers); 
    $this->setopt(CURLOPT_URL, $url); 
    $result = curl_exec($this->fSocket); 
    curl_getinfo($this->fSocket, CURLINFO_HTTP_CODE); 
    return $this->convert($result);
  }
  
  public function exec() {
    return curl_exec($this->fSocket);
  }

  /**
   * @param   string      URL
   * @return  HttpResult
   */
  public function getObj($url) {
    $this->init();
    if (!$this->fSocket) return false; 
    $this->setopt(CURLOPT_URL, $url);
    $this->setopt(CURLOPT_REFERER, Misc::getHostUrl($url));
    $this->setopt(CURLOPT_RETURNTRANSFER, 1); 
    $this->setopt(CURLOPT_HEADER, 1); 
    $this->setopt(CURLOPT_NOBODY, 0);
    $this->setopt(CURLOPT_FOLLOWLOCATION, 1); 
    $result = curl_exec($this->fSocket);
    $this->destroy();
    if (!$result) throw new NgnException("No result by url '$url'");
    return new HttpResult($this->convert($result));
  }
  
  protected function convert($text) {
    return $this->detectUTF8($this->getParsed($text, '<title>', '</title>')) ?
      $text :
      iconv($this->defaultInputEncoding, $this->encoding.'//IGNORE', $text); 
  }

  protected function compilePostData($postData) { 
    $o = ''; 
    if (!empty($postData)) 
      foreach ($postData as $k=>$v) 
        $o .= $k.'='.urlencode($v).'&'; 
    return substr($o, 0, -1); 
  }
   
  public function getParsed($result, $bef, $aft = '') { 
    $len = strlen($bef); 
    $posBef = strpos($result, $bef); 
    if ($posBef === false) return ''; 
    $posBef+=$len; 
     
    if (empty($aft)) {
      // try to search up to the end of line 
      $posAft = strpos($result, "\n", $posBef); 
      if ($posAft === false)
        $posAft = strpos($result, "\r\n", $posBef); 
    } 
    else 
      $posAft = strpos($result, $aft, $posBef); 
     
    if($posAft !== false) 
      $rez = substr($result, $posBef, $posAft-$posBef); 
    else 
      $rez = substr($result, $posBef); 
     
    return $rez; 
  }

  public function detectUTF8($string) {
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
