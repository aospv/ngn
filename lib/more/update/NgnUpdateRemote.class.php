<?php

/**
 * Копирует файлы на сервер при необходимости, получиая их из Репозитория
 */
class NgnUpdateRemote extends NgnUpdate {

  protected function updateUpdatingFile() {
    File::copy($this->tmpDir.'/updating.php', $this->webroot . '/updating.php');
  }
  
  protected function updateIndexFile() {
    File::copy($this->tmpDir.'/index.php', $this->webroot . '/updating.php');
  }  
  
}