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
    $opt = Arr::filter_by_keys($this->options, array('name', 'maxlength', 'value'));
    htmlspecialcharsR($opt);
    return $opt;
  }
  
  public function _html() {
    return
      '<input size="40" type="'.$this->inputType.'" '.
      'id="'.Misc::name2id($this->options['name']).'i" '.
      Tt::tagParams($this->getTagsParams()).$this->getClassAtr().' />';
  }
  
}
