<?php

class DmfaDdTagsTreeMultiselect extends Dmfa {

  public function afterCreateUpdate(FieldEDdTagsTreeMultiselect $el) {
    if (empty($el->options['value'])) {
      DdTagsItems::delete($this->oDM->strName, $el->options['name'], $this->oDM->id);
    } else {
      DdTagsItems::createByIdsCollection(
        $this->oDM->strName,
        $el->options['name'],
        $this->oDM->id,
        O::get(
          'DdTagsTagsTree',
          O::get(
            'DdTagsGroup',
            $this->oDM->strName, $el->options['name']
          )
        )->getParentIds($el->options['value'])
      );
    }
  }
  
  public function beforeDelete(FieldEDdTagsTreeMultiselect $el) {
    DdTagsItems::delete($this->oDM->strName, $el->options['name'], $this->oDM->id);
  }
  
}