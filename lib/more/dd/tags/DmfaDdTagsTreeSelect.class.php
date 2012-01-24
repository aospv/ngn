<?php

class DmfaDdTagsTreeSelect extends Dmfa {

  public function afterCreateUpdate(FieldEAbstract $el) {
    // Если данные этого поля пустые
    if (empty($el->options['value'])) {
      // Удаляем текущие итем записи
      DdTagsItems::delete($this->oDM->strName, $el->options['name'], $this->oDM->id);
      return;
    }
    // В массиве $parentIds должен быть только один элемент
    DdTagsItems::createByIds(
      $this->oDM->strName,
      $el->options['name'],
      $this->oDM->id,
      DdTags::get($this->oDM->strName, $el->options['name'])->
        getParentIds2(Arr::last((array)$el->options['value'])));
  }
  
  public function beforeDelete(FieldEDdTagsTreeSelect $el) {
    DdTagsItems::delete($this->oDM->strName, $el->options['name'], $this->oDM->id);
  }

}