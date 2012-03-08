<?php

class DmfaDdTagsTreeMultiselect extends DmfaDdTagsAbstract {

  protected function _afterCreateUpdate(FieldEAbstract $el) {
    if (empty($el->options['value'])) {
      $this->beforeDelete($el);
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
  
}