<?php

class DmfaDdTags extends Dmfa {

  public function afterCreateUpdate(FieldEDdTags $el) {
    if (is_array($el->options['value'])) $el->options['value'] = '';
    DdTagsItems::create($this->oDM->strName, $el->options['name'], $this->oDM->id,
      Misc::quoted2arr($el->options['value']));
  }
  
  public function beforeDelete(FieldEDdTags $el) {
    DdTagsItems::delete($this->oDM->strName, $el->options['name'], $this->oDM->id);
  }

}