<?php

class MainTplSettingsForm extends Form {
  
  protected $pageId;
  
  protected $tplName;
  
  public function __construct($pageId, $tplName) {
    parent::__construct(new MainTplSettingsFields($tplName));
    $this->pageId = $pageId;
    $this->tplName = $tplName;
    $data = $this->setElementsData(MainTplSettings::get($tplName, $pageId));
  }
  
  protected function _update(array $data) {
    MainTplSettings::save($this->tplName, $this->pageId, $data);
  }
  
}