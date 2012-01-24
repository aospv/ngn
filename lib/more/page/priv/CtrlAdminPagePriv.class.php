<?php

class CtrlAdminPagePriv extends CtrlAdminPagesBase {

  static $properties = array(
    'title' => 'Привилегии',
    'descr' => 'Привилегии пользователей',
    'onMenu' => true,
    'order' => 40
  );
  
  public function action_json_editPage() {
    $this->json['title'] = 'Радактирование привилегий раздела «'.$this->page['title'].'»';
    return new Form(new Fields(array(
      array(
        'title' => 'SUCK',
        'name' => 'suck'
      )
    )));
  }
  
}