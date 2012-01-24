<?php

DdFieldCore::registerType('ddTagsSelect', array(
  'dbType' => 'VARCHAR',
  'dbLength' => 255,
  'title' => 'Выбор одного тэга',
  'order' => 220,
  'tags' => true
));

class FieldEDdTagsSelect extends FieldESelect {

  protected function init() {
    $this->options['options'] = array('' => '—') +
      Arr::get(DdTags::getTagsByGroup(
        $this->oForm->strName, $this->options['name']), 'title', 'id');
    parent::init();
  }
  
  protected function prepareValue() {
    if (empty($this->options['value']) and !empty($this->options['default']))
      $this->defaultCaption = $this->options['default'];
  }
  
}