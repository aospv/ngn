<?php

class ConfigManagerForm_c_colors extends ConfigManagerForm {
  
  protected function afterUpdate(array $values) {
    SFLM::clearJsCssCache();
  }
  
}
