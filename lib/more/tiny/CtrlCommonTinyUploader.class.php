<?php

abstract class CtrlCommonTinyUploader extends CtrlCommonTinyDialog {
  
  protected $tinyAttachId;
  protected $fileFieldName = 'file';
  protected $title = 'Вставка файла';
  
  protected function init() {
    parent::init();
    $this->tinyAttachId = $this->oReq->reqNotEmpty('tinyAttachId');
  }
  
  abstract protected function getFields();
  abstract protected function setJson(Form $oF);
  
  public function action_json_default() {
    $oFUT = O::get('FancyUploadTemp');
    $oF = new Form(new Fields($this->getFields()), array(
      'submitTitle' => 'Вставить'
    ));
    $oFUT->extendFormOptions($oF);
    if ($oF->isSubmittedAndValid()) {
      $this->setJson($oF);
      $oFUT->delete();
      return;
    }
    $this->json['title'] = $this->title;
    return $oF;
  }

}