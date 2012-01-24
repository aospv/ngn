<?php

class FieldEHeaderToggle extends FieldEHeaderAbstract {

  static protected $dd = true;

  static public $title = 'Заголовок-переключатель';

  static public $order = 70;
  
  public function _html() {
    return '<h3>'.$this->options['title'].
      '&nbsp;<input type="button" data-name="'.$this->options['name'].
      '" value="&nbsp;&nbsp;↓&nbsp;&nbsp;" /></h3>';
  }
  
}
