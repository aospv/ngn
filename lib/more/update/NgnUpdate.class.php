<?php

/**
 * Производит распаковку архива и миграцию на новую версию NGN
 * 
 * Для выполнения метода update данного класса необходимо наличие архива
 * ngn.zip в папке $this->webroot;
 * 
 * Для выполнения методов updateUpdatingFile и updateIndexFile() необходимо наличие архива
 * dummy_project.zip в папке $this->webroot;
 * 
 */
abstract class NgnUpdate {
  
  protected $webroot;
  protected $tmpDir;
  protected $oldNgnBuildN;  
   
  abstract protected function updateUpdatingFile();
  abstract protected function updateIndexFile();
  
  public function __construct() {
    $this->webroot = __DIR__;
    $this->tmpDir = $this->webroot . '/temp';
  }
  
  protected function begin() {
    Dir::remove($this->tmpDir);
    Dir::make($this->tmpDir);
    Zip::extract($this->webroot.'/ngn.zip', $this->tmpDir);
    // Zip::extract($this->webroot.'/dummy_project.zip', $this->tmpDir); непонятно зачем для обновления NGN dummy project
  }
  
  protected function end() {
    Dir::remove($this->tmpDir);
    unlink($this->webroot . '/ngn.zip');
    unlink(__FILE__); // Удаляем инсталятор
    unlink($this->webroot . '/updating.php');
    print 'complete.';
  }
  
  private function dummyOn() {
    if (file_exists($this->webroot . '/index.php.bak')) unlink($this->webroot . '/index.php.bak');
    rename($this->webroot . '/index.php', $this->webroot . '/index.php.bak');
    rename($this->webroot.'/updating.php', $this->webroot.'/index.php');
  }
  
  private function dummyOff() {
    rename($this->webroot . '/index.php', $this->webroot.'/updating.php');
    rename($this->webroot . '/index.php.bak', $this->webroot . '/index.php');
  }
  
  public function update() {
    $this->begin();
    $this->updateUpdatingFile();
    $this->dummyOn();
    // Необходимо знать старый номер сборки.
    // Для того что бы определить какие патчи нужны, для того что бы добавить необходимые
    // классы
    StandAloneNgnMigration::migrate(
      $this->webroot,
      $this->webroot . '/ngn',
      $this->tmpDir . '/ngn'
    );
    $this->updateIndexFile();
    $this->dummyOff();
    $this->end();
  }

}
