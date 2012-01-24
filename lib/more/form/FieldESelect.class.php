<?php

class FieldESelect extends FieldEText {
  
  protected $requiredOptions = array('name');
  
  protected function init() {
    parent::init();
    if (!isset($this->options['options']))
      throw new NgnException("Options not set in element: ".getPrr($this->options));
    if (!is_array($this->options['options']))
      throw new NgnException('options[options] is not array. options: '.getPrr($this->options));
    if (!Arr::isAssoc($this->options['options']))
      $this->options['options'] = Arr::to_options($this->options['options']);
  }
  
  protected $defaultCaption = null;
  
  public function _html() {
    return
      '<select name="'.$this->options['name'].'"'.$this->getClassAtr().
      ' id="'.Misc::name2id($this->options['name']).'i">'.
      Html::select($this->options['name'], $this->options['options'], $this->options['value'],
        array(
          'noSelectTag' => true,
          'defaultCaption' => $this->defaultCaption
        )).
      '</select>';
  }
  
  public function titledValue() {
    $value = $this->value();
    return isset($this->options['options'][$value]) ?
      $this->options['options'][$value] : null;
  }

}