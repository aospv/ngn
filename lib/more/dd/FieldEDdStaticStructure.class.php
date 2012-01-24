<?php

class FieldEDdStaticStructure extends FieldESelect {

  protected function defineOptions() {
    $this->options['options'] = array('' => '— '.LANG_NOTHING_SELECTED.' —');
    $oS = new DbItems('dd_structures');
    $this->options['options'] = Arr::get($oS->getItems(), 'title', 'name');
  }

}