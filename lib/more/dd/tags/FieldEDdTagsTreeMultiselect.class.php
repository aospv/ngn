<?php

DdFieldCore::registerType('ddTagsTreeMultiselect', array(
  'dbType' => 'VARCHAR',
  'dbLength' => 255,
  'title' => 'Древовидный выбор нескольких тэгов',
  'order' => 250,
  'tags' => true,
  'tagsTree' => true
));

class FieldEDdTagsTreeMultiselect extends FieldEText {
  
  public function _html() {
    $oTags = new DdTagsTagsTree(new DdTagsGroup(
      $el->oForm->strName, $el->options['name']));
    return Tt::getTpl('dd/tagsTreeMultiselect', array(
      'name' => $el->options['name'],
      'value' => $el->options['value'],
      'tree' => $oTags->getTree()
    ));
  }

}