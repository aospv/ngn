<?php

class ConfigManagerForm_c_more extends ConfigManagerForm {
  
  protected function afterUpdate(array $values) {
    // Если значение константы изменилось, очищаем кэш статических файлов
    if ($values['DEBUG_STATIC_FILES'] != $this->configDefaultData['DEBUG_STATIC_FILES']) {
      SFLM::clearJsCssCache();
    }
  }
  
}
