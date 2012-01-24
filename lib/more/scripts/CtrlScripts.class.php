<?php

class CtrlScripts extends CtrlCommon {

  public $folder;

  protected function setAuthUserId() {}

  protected function init() {
    if (! $this->folder)
      throw new NgnException('$this->folder not defined');
    $this->hasOutput = false;
    
    if (!isset($this->params[1])) {
      // Если путь к с крипту не указан
      $this->printList();
      return;
    }
    
    // Получаем путь из исходного "s/path/to/script" в обрезаный "path/to/script"
    $path = preg_replace('/[^\/]*\/(.*)/', '$1', O::get('Req')->path);
    
    if (strstr($path, 'js/')) {
      header('Content-type: text/javascript; charset='.CHARSET);
    } elseif (strstr($path, 'css/')) {
      header('Content-type: text/css; charset='.CHARSET);
    } else {
      header("Content-type: text/html; charset=".CHARSET);
    }
    $scriptPath = 'more/scripts/' . $this->folder . '/' . $path . '.php';
    $scriptPath2 = 'more/scripts/' . $this->folder . '/' . $path;
    if (Lib::getPath($scriptPath, false)) {
      //prr($scriptPath);
      Lib::required($scriptPath);
    } elseif (Lib::getPath($scriptPath2, false)) {
      //prr($scriptPath2);
      Lib::required($scriptPath2);
    } else {
      throw new NgnException('Script "'.$this->folder.'/'.$path.'" not found');
    }
  }
  
  /**
   * Выводит список скриптов с сылками на них рекурсивно
   */
  function printList() {
    foreach (Dir::getFilesR(LIB_PATH.'/more/scripts/' . $this->folder) as $v) {
      $v = str_replace(LIB_PATH.'/more/scripts/scripts/', '', $v);
      $v = str_replace('.php', '', $v);
      print '<a href="'.Tt::getPath(1).'/'.
        str_replace(LIB_PATH.'/more/scripts/' . $this->folder, '', $v)
      .'">'.$v.'</a><br />'."\n";
    }
  }

}