<?php

class DmfaDdTags extends DmfaDdTagsAbstract {

  protected function _afterCreateUpdate(FieldEAbstract $el) {
    if (is_array($el->options['value'])) $el->options['value'] = '';
    DdTagsItems::create($this->oDM->strName, $el->options['name'], $this->oDM->id,
      Misc::quoted2arr($el->options['value']));
  }

}