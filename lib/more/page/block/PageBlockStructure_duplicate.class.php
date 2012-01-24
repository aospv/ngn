<?php

class PageBlockStructure_duplicate extends PageBlockStructureAbstract {

  static public $title = 'Дубликат блока';
  
  protected function initFields() {
    foreach (PageBlockCore::getDynamicBlocks(0) as $v)
      $options[$v['id']] = !empty($v['settings']['title']) ?
        $v['settings']['title'] :
        'id='.$v['id'].', type='.$v['type'];
    // ----------------------------------------
    $this->fields[] = array(
      'title' => 'Блок',
      'name' => 'duplicateBlockId',
      'type' => 'select',
      'required' => true,
      'options' => $options
    );
  }

}