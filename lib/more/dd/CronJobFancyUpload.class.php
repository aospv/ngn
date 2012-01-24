<?php

class CronJobFancyUpload extends CronJobAbstract {
  
  public $period = 'every1h';
  public $enabled = true;
  protected $tempExpiresTime = 3600;
  
  public function _run() {
    $time = time();
    foreach (glob(UPLOAD_PATH.'/fancyTemp/*') as $dir) {
      $expires = true;
      foreach (glob($dir.'/*') as $file) {
        if ($time-$this->tempExpiresTime < filemtime($file)) {
          $expires = false;
          break;
        }
      }
      if ($expires) {
        Dir::remove($dir);
      }
    }
  }
  
} 
