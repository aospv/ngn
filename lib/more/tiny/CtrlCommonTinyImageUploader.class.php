<?php

class CtrlCommonTinyImageUploader extends CtrlCommonTinyUploader {
  
  protected $isThumb = false;
  protected $config;
  protected $title = 'Вставка изображения';
  
  protected function init() {
    parent::init();
    $this->config = Config::getVar('dd');
    Arr::checkEmpty($this->config, array('smW', 'smH', 'mdW', 'mdH'));
  }
  
  protected function getFields() {
    return array(
      array(
        'title' => 'Изображение',
        'name' => $this->fileFieldName,
        'type' => 'image',
        'required' => true
      )
    );
  }
  
  protected function setJson(Form $oF) {
    $data = $oF->getData();
    $this->json['imageUrl'] = '/'.O::get('TinyImageManager',
      $this->tinyAttachId,
      $this->config,
      $this->isThumb
    )->process($data[$this->fileFieldName]['tmp_name']);
  }

}