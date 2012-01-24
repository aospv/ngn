<?php

class BuildInstaller {
  
  private $url;
  
  public function __construct() {
    $this->url = 'http://'.UPDATER_URL.'/c2/updater';
  }
  
  public function getNewBuildN() {
    return file_get_contents($this->url.'?a=ajax_getBuildN');
  }
  
  public function install() {
    set_time_limit_q(0);
    $tempFolder = UPLOAD_PATH.'/temp';
    Dir::make($tempFolder.'/ngn');
    //copy($this->url.'?a=downloadNgn', $tempFolder.'/ngn.zip'); // - не поддерживаются редиректы почему-то
    copy('http://'.UPDATER_URL.'/u/temp/ngn.zip', $tempFolder.'/ngn.zip');
    sleep(5);
    Zip::extract($tempFolder.'/ngn.zip', $tempFolder);
    Dir::remove(NGN_PATH);
    Dir::move($tempFolder.'/ngn', NGN_PATH);
    Dir::remove($tempFolder);
  }
  
}
