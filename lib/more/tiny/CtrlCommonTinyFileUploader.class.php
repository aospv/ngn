<?php

class CtrlCommonTinyFileUploader extends CtrlCommonTinyUploader {
  
  protected function getFields() {
    return array(
      array(
        'title' => 'Файл',
        'name' => $this->fileFieldName,
        'type' => 'file',
        'required' => true
      ),
      array(
        'title' => 'Название',
        'name' => 'title'
      )
    );
  }
  
  protected function setJson(Form $oF) {
    $data = $oF->getData();
    $this->json = O::get('TinyFileManager', $this->tinyAttachId)->
      process($data[$this->fileFieldName]);
    $this->json['title'] = $data['title'] ? $data['title'] : basename($this->json['url']);
  }

}
