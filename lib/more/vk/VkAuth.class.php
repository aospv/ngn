<?php

class VkAuth {
  
  protected $email;
  protected $pass;
  public $dataFolder;
  public $userDataFolder;
  public $baseUrl;

const loginKeyword = 'выйти';
  
  /**
   * @var Curl
   */
  public $curl;
  
  public function __construct($email, $pass, $dataFolder = null) {
    if (!$dataFolder) $dataFolder = TEMP_PATH.'/vk';
    ini_set('memory_limit', '1000M');
    set_time_limit_q(0);
    // -----------------------------------
    $this->email = $email;
    $this->pass = $pass;
    $this->dataFolder = $dataFolder;
    Dir::make($dataFolder);
    $this->baseUrl = 'http://vkontakte.ru';
    $this->curl = new Curl();
    $this->curl->decodeResult = true;
    $this->init();
  }
  
  protected function init() {
  }
  
  public $authorized = false;
  
  public function auth($required = true) {
    if ($this->authorized) return true;
    if ($this->authByPrevSessionId()) {
      $this->authorized = true;
    }
    elseif ($this->authByEmailAndPass()) {
      $this->authorized = true;
    }
    if ($this->authorized) $this->initUserData();
    if ($required and !$this->authorized)
      throw new NgnException('Vk authorization failed', 1022);
    return $this;
  }
  
  public $userId;
  public $chas;
  
  protected function initUserData() {
    preg_match('/vk = \{([^}]*)}/sm', $this->lastResult, $m);
    preg_match('/id: (\d+)/', $m[1], $m);
    $this->userId = $m[1];
    $this->userDataFolder = $this->dataFolder.'/'.$this->userId;
    Dir::make($this->userDataFolder);
    Misc::checkEmpty($this->userId);
  }
  
  protected function decodedHashes($hash) {
    $r = '';
    for ($i=0; $i<strlen($hash); ++$i)
      $r .= $hash[strlen($hash)-$i-1];
    $r = substr($r, 8, 13).substr($r, 0, 5);
    return $r;
  }

  protected function decHash($hash) {
    $this->hashes[$hash] = $this->decodedHashes(substr($hash, 0, strlen($hash)-5)+substr($hash, 4, strlen($hash)-12) ); 
  }

  protected function decodehash($hash) {
    $this->decHash($hash);
    return $this->hashes[$hash];
  }
  
  public function initChas($userId) {
    $this->get($this->baseUrl.'/id'.$userId);
    if (!preg_match("/chas: cur.decodehash\('([^']+)'\)/sm", $this->lastResult, $m))
      return false;
    $chas = $m[1];
    $this->chas = $this->decodedHashes($chas);
    return $this;
  }
  
  protected function authByPrevSessionId() {
    $file = $this->dataFolder.'/sess_'.$this->email.'.dat';
    if (!file_exists($file)) return false;
    $sessionId = file_get_contents($file);
    output($sessionId);
    $this->curl->setopt(CURLOPT_COOKIE, 'remixsid='.$sessionId);
    $c = $this->get($this->baseUrl);
    if (strstr($c, self::loginKeyword)) {
      output('AUTH BY SESSION SUCCESS');
      return true;
    } else {
      unlink($file);
      throw new NgnException('Vk session failed', 1023); 
    }
  }
  
  protected function authByEmailAndPass() {
    $c = $this->curl->get('http://vkontakte.ru');
    preg_match('/ip_h" value="(.*)"/', $c, $m);
    $this->curl->setopt(CURLOPT_HEADER, 1);
    $c = $this->curl->post('http://login.vk.com?act=login', array(
      'al_test' => '1',
      'email' => $this->email,
      'from_host' => 'vkontakte.ru',
      'from_protocol' => 'http',
      'q' => 1,
      'pass' => $this->pass,
    ));
    if (!$c) {
      throw new NgnException('Vk login page load error', 1026);
    }
    if (strstr($c, 'либо пароль неверный')) {
      $this->saveOutput($c);
      throw new NgnException('Vk auth error. Email or Password', 1025);
    }
    if (strstr($c, 'перезагружаем')) {
      throw new NgnException('Vk server reload');
    }
    //$r = HttpResult::parseWithHeaders($c);
    if (!preg_match("/remixsid=([^;]+);/s", $c, $m)) {
      file_put_contents($this->dataFolder.'/error_result.html', $c);
      throw new NgnException('Vk parse session ID error', 1024);
    }
    $sessionId = $m[1];
    
    file_put_contents($this->dataFolder.'/sess_'.$this->email.'.dat', $sessionId);
    $this->curl->setopt(CURLOPT_COOKIE, 'remixsid='.$sessionId);
    
    $c = $this->get($this->baseUrl);
    if (!strstr($c, self::loginKeyword)) {
      $this->saveOutput($c);
      throw new NgnException('Vk auth by email and password error', 1027);
    }
    output('AUTH BY EMAIL AND PASS SUCCESS');
    return true;
  }
  
  public $lastResult;
  
  public function get($url) {
    return $this->lastResult = $this->curl->get($url);
  }
  
  protected function saveOutput($c) {
    file_put_contents(
      $this->dataFolder.'/output_'.date('H-i-s').'_'.Misc::randString(3).'.html', $c);
  }
  
}