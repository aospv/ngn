<?php

class CtrlCommonTinyImagePreviewUploader extends CtrlCommonTinyImageUploader {
  
  protected $isThumb = true;
  protected $useParentPlugin = true;
  protected $title = 'Вставка изображения с предпросмотром';
  
  protected function init() {
    parent::init();
    Arr::checkEmpty($this->config, 'resizeType');
  }
  
  protected function getFields() {
    return array(
      array(
        'title' => 'Изображение',
        'name' => $this->fileFieldName,
        'type' => 'imagePreview',
        'required' => true
      ),
      array(
        'type' => 'static',
        'title' => "Размеры превьюшки: <b>{$this->config['smW']}</b>x<b>{$this->config['smH']}</b>"
      ),
      array(
        'name' => 'resizeType',
        'type' => 'radio',
        'default' => $this->config['resizeType'],
        'options' => array(
          'resample' => 'вписывать',
          'resize' => 'обрезать'
        )
      )
    );
  }
  
  protected function setJson(Form $oF) {
    $data = $oF->getData();
    $url = '/'.O::get('TinyImageManager',
      $this->tinyAttachId,
      $this->config,
      $this->isThumb,
      $data['resizeType']
    )->process($data[$this->fileFieldName]['tmp_name']);
    $this->json['resizeType'] = $data['resizeType'];
    $this->json['imageUrl'] = Misc::getFilePrefexedPath($url, 'md_', 'jpg');
    $this->json['smImageUrl'] = Misc::getFilePrefexedPath($url, 'sm_', 'jpg');
  }
  
}