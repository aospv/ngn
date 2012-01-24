<?php

class FieldEFieldList extends FieldEFieldSetAbstract {

  protected function defineOptions() {
    $this->options['deleteTitle'] = 'Удалить поле';
    $this->options['cleanupTitle'] = 'Очистить поле';
  }

  protected function init() {
    $this->options['fields'] = array(
      array(
        'name' => 'dummy',
        'type' => empty($this->options['fieldsType']) ? 'text' : $this->options['fieldsType']
      )
    );
    parent::init();
  }

  protected function getName($n, $name) {
    return $this->options['name']."[$n]";
  }
  
}