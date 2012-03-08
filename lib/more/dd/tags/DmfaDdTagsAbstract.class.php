<?php

abstract class DmfaDdTagsAbstract extends Dmfa {

  public function afterCreateUpdate(FieldEAbstract $el) {
    //if (isset($this->oDM->data['active']) and !$this->oDM->data['active']) {
      //$this->beforeDelete($el);
      //return;
    //}
    $this->_afterCreateUpdate($el);
  }

  abstract protected function _afterCreateUpdate(FieldEAbstract $el);

  public function beforeDelete(FieldEAbstract $el) {
    DdTagsItems::delete($this->oDM->strName, $el->options['name'], $this->oDM->id);
  }

}