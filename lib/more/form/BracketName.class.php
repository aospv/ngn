<?php

class BracketName {

  static public function getKeys($bracketName, $exceptLastKey = false) {
    $m = array();
    // Получаем имя массива ('v') из строки вида v["asd"]["dfgv"]
    if (!preg_match('/([^[]+)\[/', $bracketName, $m)) return false;
    // Если имя поля - массив
    $keys = "['".$m[1]."']";
    // Получаем все ключи массива. Для v["asd"]["dfgv"] - это будут 'asd', 'dfgv'
    preg_match_all('/\[([^\]]*)\]/', $bracketName, $m);
    for ($i=0; $i < count($m[1]) - ($exceptLastKey ? 1 : 0); $i++)
      $keys .= "['".$m[1][$i]."']";
    return $keys;
  }
  
  static public function getNameWithoutKeys($bracketName) {
    return preg_replace('/(\w)\[.*/', '$1', $bracketName);
  }
  
  static protected function getLastKey($bracketName) {
    return preg_replace('/^.*\[([^\]]*)\]$/', '$1', $bracketName);
  }

  const modeNull = null;
  const modeString = '';
  const modeFalse = false;
  
  /**
   * Возвращает значение элемента массива по его значению, записанному в плоской форме
   * (форме строки "v['asd']['dfgh']")
   *
   * @param   array   Массив с данными для извлечения
   * @param   string  Строка элемента массива вида "v['asd']['dfgh']"
   * @return  mixed
   */
  static public function getValue(array $data, $bracketName, $noResultMode = self::modeNull) {
    if (($keys = self::getKeys($bracketName)) !== false) {
      return eval("return isset(\$data$keys) ? \$data$keys : \$noResultMode;");
    } else {
      return isset($data[$bracketName]) ? $data[$bracketName] : $noResultMode;
    }
  }
  
  static protected function valueExists(array $data, $bracketName) {
    if (($keys = self::getKeys($bracketName)) !== false) {
      return eval("return isset(\$data$keys);");
    } else {
      return isset($data[$bracketName]);
    }
  }
  
  static public function setValue(array &$data, $bracketName, $value) {
    if (($keys = self::getKeys($bracketName)) !== false) {
      eval("\$data$keys = \$value;");
    } else {
      $data[$bracketName] = $value;
    }
  }
	

}
