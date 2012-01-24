<?php

DdFieldCore::registerType('ddTagsMultiselect', array(
  'dbType' => 'VARCHAR',
  'dbLength' => 255,
  'title' => 'Выбор нескольких тэгов',
  'order' => 230,
  'tags' => true
));

class FieldEDdTagsMultiselect extends FieldEMultiselect {

  protected function init() {
    $this->options['options'] = Arr::get(DdTags::getTagsByGroup(
      $this->oForm->strName, $this->options['name']), 'title', 'id');
    parent::init();
  }
  
}
