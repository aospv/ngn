<?php

abstract class FieldEInput extends FieldEAbstract {

  const defaultMaxLength = 1000;

  public $inputType;
  
  protected function getClassAtr() {
    if (($classes = $this->getCssClasses()) !== false)
      return ' class="'.implode(' ', $classes).'"';
    return '';
  }
  
  protected function getTagsParams() {
  	$opts = $this->options;
  	if (isset($opts['value'])) $opts['value'] = $this->prepareInputValue($opts['value']);
    $opt = Arr::filter_by_keys($opts, array('name', 'maxlength', 'value'));
    htmlspecialcharsR($opt);
    return $opt;
  }
  
  protected function prepareInputValue($value) {
    return $value;
  }
  
  public function _html() {
    return
      '<input size="40" type="'.$this->inputType.'" '.
      'id="'.Misc::name2id($this->options['name']).'i" '.
      Tt::tagParams($this->getTagsParams()).$this->getClassAtr().' />';
  }
  
}
