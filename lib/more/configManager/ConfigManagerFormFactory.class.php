<?php

class ConfigManagerFormFactory {
  
  /**
   * @param   string  Config type
   * @param   string  Config name
   * @return  ConfigManagerForm
   */
  static public function get($type, $name) {
    $class = 'ConfigManagerForm_'.$type[0].'_'.$name;
    if (Lib::exists($class))
      return O::get($class, $type, $name);
    else return new ConfigManagerForm($type, $name);
  }
  
}