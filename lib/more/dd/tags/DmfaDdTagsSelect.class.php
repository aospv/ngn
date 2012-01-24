<?php

class DmfaDdTagsSelect extends Dmfa {

  public function afterCreateUpdate(FieldEDdTagsSelect $el) {
    if (empty($el->options['value'])) {
      DdTagsItems::delete(
        $this->oDM->strName,
        $el->options['name'],
        $this->oDM->id
      );
    } else {
      DdTagsItems::createById(
        $this->oDM->strName,
        $el->options['name'],
        $this->oDM->id,
        $el->options['value']
      );
    }
  }
  
  public function beforeDelete(FieldEDdTagsSelect $el) {
    DdTagsItems::delete($this->oDM->strName, $el->options['name'], $this->oDM->id);
  }

}