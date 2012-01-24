<?php

class CtrlCommonTinyImageProp extends CtrlCommon {
  
  public function action_json_default() {
    $this->json['title'] = 'Параметры изображения';
    return new Form(new Fields(array(
      array(
        'title' => 'Название изображения',
        'name' => 'alt',
        'help' => 'Альтернативный текст (тег "alt")'
      )
    )));
  }
  
}