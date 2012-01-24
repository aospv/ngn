<?php

class PcpTpl extends Pcp {
  
  public $title = 'Произвольный шаблон';
  
  public function getProperties() {
    return Arr::append(parent::getProperties(), array(
      array(
       'name' => 'tplName',
        'title' => 'Имя шаблона',
        'type' => 'text',
        'required' => 1
      )
    ));
  }
  
}
