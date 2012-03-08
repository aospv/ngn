<?php

class DmfaDdTagsSelect extends DmfaDdTagsAbstract {

  protected function _afterCreateUpdate(FieldEAbstract $el) {
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

}