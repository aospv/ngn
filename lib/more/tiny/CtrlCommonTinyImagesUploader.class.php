<?php

class CtrlCommonTinyImagesUploader extends CtrlCommonTinyImageUploader {
  
  
  public function action_default() {
    $this->d['pageTitle'] = 'Вставка изображений';
  }

  public function action_json_upload() {
    if (!isset($_FILES['Filedata'])) {
      $this->d['error'] = 'Ничего не загружено';
      $this->d['tpl'] = 'tiny/popup/error';
    }
    if (!is_uploaded_file($_FILES['Filedata']['tmp_name'])) {
      $this->d['error'] = 'Ошибка загрузки';
      $this->d['tpl'] = 'tiny/popup/error';
    }
    /*
    [Filedata] => Array
    (
      [name] => DSCN0857.JPG
      [type] => application/octet-stream
      [tmp_name] => C:\WINDOWS\Temp\php2BC.tmp
      [error] => 0
      [size] => 1515588
    )
    */
    $oTIM = new TinyImageManager($this->attachId, $this->d['dd']);
    $this->json = array(
      'imagePath' => $oTIM->process(
        $_FILES['Filedata']['tmp_name'],
        'image/jpeg'
      )
    );
  }
  
}