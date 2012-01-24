<?php

class CtrlCommonTinyLink extends CtrlCommon {
  
  public function action_json_default() {
    $this->json['title'] = 'Вставка ссылки';
    return new Form(new Fields(array(
      array(
        'title' => 'Ссылка',
        'name' => 'link',
        'type' => 'pageLink',
        'required' => true
      ),
      /*
      array(
        'title' => 'Текст ссылки',
        'name' => 'title',
        'required' => true
      )
      */
    )), array(
      'submitTitle' => 'Вставить'
    ));
  }
  
}