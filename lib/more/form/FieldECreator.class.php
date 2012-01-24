<?php

/**
 * Используется в том случае, если сам элемент не создаем ни HTML-элементов, ни данных, а только
 * лишь другие элементы, но тем не менее может эти данные создаются дочерними элементами
 * и возвращаются функцией value() этого элемента
 */
class FieldECreator extends FieldEAbstract {
  
  protected $requiredOptions = array('name', 'fields');

  public $options = array(
    'noRowHtml' => true,
    'noValue' => true
  );
  
  protected function init() {
    foreach ($this->options['fields'] as &$v)
      $v['name'] = $this->options['name']."[{$v['name']}]";
    $oFields = new Fields($this->options['fields']);
    foreach ($oFields->getFields() as $v) {
      $v['noRowHtml'] = true;
      $v['value'] = $oFields->isFileType($v['name']) ?
         BracketName::getValue($this->oForm->defaultData, $v['name']) :
         BracketName::getValue($this->oForm->elementsData, $v['name']);
      $this->oForm->createElement($v);
    }
  }

}