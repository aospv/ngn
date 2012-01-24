<?php

class ConfigManagerForm_v_stat extends ConfigManagerForm {

  protected function afterUpdate(array $values) {
    if (!$this->getElement('enable')->valueChanged) return;
    $values['enable'] ? O::get('PiwikStat')->enable() : O::get('PiwikStat')->disable();
  }

}
