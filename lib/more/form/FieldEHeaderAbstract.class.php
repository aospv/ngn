<?php

class FieldEHeaderAbstract extends FieldEAbstract {

  public $options = array(
    'noRowHtml' => true,
    'noValue' => true
  );

  static $i = 0;
  
  protected function init() {
    parent::init();
    self::$i++;
    $this->options['name'] = $this->type.self::$i;
  }
  
  public function _html() {
    if (empty($this->options['title'])) return '';
    return '<h3>'.$this->options['title'].'</h3>';
  }
  
}
