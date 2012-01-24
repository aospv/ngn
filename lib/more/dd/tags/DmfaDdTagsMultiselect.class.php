<?php

class DmfaDdTagsMultiselect extends Dmfa {
  
  public function afterCreateUpdate(FieldEDdTagsMultiselect $el) {
    // Если данные этого поля пустые
    if (empty($el->options['value'])) {
      // Удаляем текущие итем записи
      DdTagsItems::delete($this->oDM->strName, $el->options['value'], $this->oDM->id);
    } else {
      DdTagsItems::createByIds(
        $this->oDM->strName,
        $el->options['name'],
        $this->oDM->id,
        $el->options['value']
      );
    }
  }
  
  public function beforeDelete(FieldEDdTagsMultiselect $el) {
    DdTagsItems::delete($this->oDM->strName, $el->options['name'], $this->oDM->id);
  }

}