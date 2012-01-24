<?php

/**
 * Для выполнения методов updateUpdatingFile и updateIndexFile() необходимо наличие архива
 * dummy_project.zip в папке $this->webroot;
 * 
 */
class NgnUpdateInternal extends NgnUpdate {
  
  private $url;
  
  protected function begin() {
    parent::begin();
    $this->url = 'http://'.Config::getConstant(
      $this->webroot.'/site/config/constants/site.php', 'UPDATER_URL').'/repos/ngn';
  }
  
  protected function updateUpdatingFile() {
    //File::copy($this->url . '/updating', $this->webroot . '/updating.php');
  }
  
  protected function updateIndexFile() {
    //File::copy($this->url . '/index', $this->webroot . '/index.php');
  }
  
}