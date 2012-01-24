<?php

/*

Вариант с БД:

- проверяем наличие билда
- скачиваем архив с билдом
- блокируем БД на апдейты
- распаковываем в NGN-NEW
- меняем путь к NGN
- запустили тесты
если есть ДБ патчи
- копируем базу с префиксом
- апдейтим базу
- удаляем БД с префиксом
- удаляем NGN, переименовываем NGN-NEW в NGN

===============================

Вариант без БД:
- проверяем наличие билда
- скачиваем архив с билдом
- распаковываем в NGN-NEW
- переименовываем NGN в NGN-OLD
- переименовываем NGN-NEW в NGN

===============================

VERSION

*/

function prrr($t) {
  print '<li>'.$t.'</li>';
}

class NgnUpdaterSingleSite {
  
  private $urlBase;
  
  private $ngnFolder;
  
  private $initialName;
  
  private $parentFolder;
  
  private $archName;
  
  private $updateFolder;
  
  private $webroot;
  
  public $rebuild = false;
  
  public function __construct($urlBase, $ngnFolder) {
    $this->urlBase = $urlBase;
    $this->ngnFolder = $ngnFolder;
    $this->initialName = basename($ngnFolder);
    $this->parentFolder = dirname($ngnFolder);
    $this->tempInitName = $this->parentFolder.'/'.$this->initialName.'-backup';
    $this->archName = 'TEMP_NGN_BUILD.zip';//basename($url);
    $this->updateFolder = $this->parentFolder.'/update';
    $this->webroot = WEBROOT_PATH;
    Dir::make($this->updateFolder);
  }

  public function update() {
    if (!$newBuildN = $this->newBuildExists()) return false;
    $this->download($newBuildN);
    $this->extract();
    $this->replace();
    
    // Патчить ли БД
    $patchDB = $this->dbPatchesExists();
    
    if ($patchDB) {
      prrr('Существуют патчи БД');
      $this->backupDB();
      $this->patchDB();
    }
    
    // -------------------------------------------
    
    if ($this->test()) {
      prrr('Тест прошел удачно');
      $this->delete();
      $this->mov_to_webroot();
    } else {
      prrr('Тест прошел неудачно');
      $this->rollback();
      if ($patchDB) $this->restoreDB();
    }
    
    if ($patchDB) db()->deleteBackup();
    
    return true;
  }
  
  private function newBuildExists() {
    prrr('Проверяем наличие нового билда по ссылке <a href="'.
      $this->urlBase.'/get-update.php" target="_blank">'.$this->urlBase.'/get-update.php</a>');
    $buildN = $this->getCurBuildN();
    $remoteBuildN = file_get_contents($this->urlBase.'/get-update.php');
    if ($this->rebuild or $buildN < $remoteBuildN) {
      prrr('Имеется новый билд №'.$remoteBuildN.'. Текущий: №'.$buildN);
      return $remoteBuildN;
    }
    prrr('Номер текущего билда ('.$buildN.') больше имеющегодся к апдейту ('.$remoteBuildN.')');
    return false;
  }

  private function download($buildN) {
    prrr('Скачиваем архив с билдом сюда "'.$this->updateFolder.'/'.$this->archName.'"');
    $url = $this->urlBase.'/ngn-'.$buildN.'.zip';
    copy($url, $this->updateFolder.'/'.$this->archName);
  }
  
  private function extract() {
    prrr('Распаковываем во временный каталог "'.$this->updateFolder.'"');
    $zip = new ZipArchive();
    $zip->open($this->updateFolder.'/'.$this->archName);
    $zip->extractTo($this->updateFolder);
  }
  
  private function getNewBuildFolder() {
    return $this->updateFolder.'/'.Arr::first(Dir::dirs($this->updateFolder));
  }
  
  private function test() {
    prrr('Тестируем');
    return false;
    $test = new TestSuite('Тесты проекта '.SITE_TITLE);
    O::inc('more/tests/TestIndex.class.php');
    $test->addTestCase(new TestIndex());
    return $test->run();
  }
  
  /**
   * Заменяет текущую версию NGN
   */
  private function replace() {
    $newBuildFolder = $this->getNewBuildFolder();
    prrr('Делаем бэкап "'.$this->ngnFolder.'" --> "'.$this->tempInitName.'"');
    rename($this->ngnFolder, $this->tempInitName);
    prrr('Перемещаем распакованный билд "'.$newBuildFolder.'" --> "'.$this->ngnFolder.'"');
    rename($newBuildFolder, $this->ngnFolder);
  }
  
  private function dbPatchesExists() {
    return O::get('DbPatcher')->need2patch();
  }
  
  private function backupDB() {
    prrr('Делаю бэкап БД');
    db()->backup();
  }
  
  private function patchDB() {
    O::get('DbPatcher')->patch();
  }
  
  private function restoreDB() {
    prrr('Восстанавливаю БД');
    db()->restore();
  }
  
  private function mov_to_webroot() {
    prrr('Перемещаем "'.($this->ngnFolder.'i').'" --> "'.($this->webroot.'i').'"');
    rename($this->ngnFolder.'i', $this->webroot.'i');
  }
  
  private function delete() {
    prrr('Удаляем "'.$this->tempInitName.'", "'.$this->updateFolder.'"');
    Dir::remove($this->tempInitName);
    Dir::remove($this->updateFolder);
  }
  
  private function rollback() {
    prrr('Откатываем');
    
    prrr('NGN в трэш "'.$this->ngnFolder.'" --> "'.$this->updateFolder.'"');
    rename($this->ngnFolder, $this->updateFolder.'/trash');
    
    prrr('Перемещаем бэкап в NGN "'.$this->tempInitName.'" --> "'.$this->ngnFolder.'"');
    rename($this->tempInitName, $this->ngnFolder);
    
    Dir::remove($this->updateFolder);
  }
  
  private function getCurBuildN() {
    return Config::getConstant(
      $this->ngnFolder.'/config/version.php',
      'BUILD');
  }
  
}
