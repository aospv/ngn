<?php

class ConfigManagerForm_v_mysite extends ConfigManagerForm {
  
  protected function afterUpdate(array $values) {
    // Если значение константы изменилось, очищаем кэш статических файлов
    if ($values['enable'] and $values['enable'] != $this->configDefaultData['enable']) {
      UsersCore::generateNames();
    }
  }
  
}
