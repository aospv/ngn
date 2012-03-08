<?php

class DmfaDdTagsMultiselect extends DmfaDdTagsAbstract {
  
  protected function _afterCreateUpdate(FieldEDdTagsMultiselect $el) {
    // Если данные этого поля пустые
    if (empty($el->options['value'])) {
      // Удаляем текущие итем записи
      $this->beforeDelete($el);
    } else {
      DdTagsItems::createByIds(
        $this->oDM->strName,
        $el->options['name'],
        $this->oDM->id,
        $el->options['value']
      );
    }
  }

}