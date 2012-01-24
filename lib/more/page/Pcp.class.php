<?php

abstract class Pcp {

  public $title;
  public $visible = true;
  public $editebleContent = false;
  
  public function getProperties() {
    return array(
      array(
        'name' => 'mainTpl', 
        'title' => 'Главный шаблон', 
        'type' => 'text'
      ),
      array(
        'name' => 'defaultAction', 
        'title' => 'Экшн по умолчанию', 
        'type' => 'select',
        'options' => DefaultAction::options()
      )
    );
  }
  
  public function getAfterSaveDialogs(PageControllerSettingsForm $oF) {
    return false;
  }

}