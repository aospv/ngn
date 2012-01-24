<?php

class ConfigReset {

  /**
   * Переформировывает файлы со значениями констант по их структурам взятым из
   * указаного NGN-каталога. Удаляет значения констант, если они равны значениям по-умолчанию
   *
   * @param   string  site-каталог где будут производиться оптимизация констант
   * @param   string  NGN-каталог откуда будут браться структцры констант
   */
  static public function rebuildConstants($siteFolder, $masterNgnFolder) {
    $siteConstantsFolder = $siteFolder.'/config/constants';
    $siteConstants = Config::getAllConstantsFlat($siteConstantsFolder);
    $values = array();
    $struct = Config::getStruct($masterNgnFolder, 'constants');
    foreach ($struct as $strName => $strData) {
      foreach ($strData['fields'] as $name => $v) {
        // Если существует значение сайт-константы и оно не равно значению этой 
        // константы по умолчанию, добавляем это значение в результирующий массив
        //if (isset($siteConstants[$name])/* and $siteConstants[$name] != $v['default']*/) {
        $vv = isset($siteConstants[$name]) ? $siteConstants[$name] :
          (isset($v['default']) ? $v['default'] : '');
        $values[$strName][$name] = (isset($v['type']) and $v['type'] == 'bool') ? (bool)$vv : $vv;
        //}
      }
    }
    foreach ($values as $strName => $constants) {
      output("Create " . count($constants) . " '$strName' constants");
      Config::createConstants($siteConstantsFolder . '/' . $strName . '.php', 
        $constants);
    }
  }
  
  static public function rebuildCurrentSiteConstants() {
  	self::rebuildConstants(WEBROOT_PATH.'/site', NGN_PATH);
  }

}
