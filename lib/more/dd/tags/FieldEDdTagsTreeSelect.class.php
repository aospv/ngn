<?php

DdFieldCore::registerType('ddTagsTreeSelect', array(
  'dbType' => 'VARCHAR',
  'dbLength' => 255,
  'title' => 'Древовидный выбор одного тэга',
  'order' => 240,
  'tags' => true,
  'tagsTree' => true
));

class FieldEDdTagsTreeSelect extends FieldEText {
  
  public function _html() {
    $oTags = new DdTagsTagsTree(new DdTagsGroup($this->oForm->strName, $this->options['name']));
    if (!empty($this->oForm->ctrl->userGroup))
      $oTags->getCond()->addF('userGroupId', $this->oForm->ctrl->userGroup['id']);
    return Tt::getTpl('dd/tagsTreeSelect', array(
      'name' => $this->options['name'],
      'value' => $this->options['value'],
      'tree' => $oTags->getTree()
    ));
  }
  
  public function _js() {
    return $this->defaultJs();
  }
  
}
