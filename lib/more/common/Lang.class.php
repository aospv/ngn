<?php

class Lang {

  /**
   * Возвращает текущий язык проекта
   *
   * @param   string  Имя области применения. Пример: "admin"
   * @return  string  Язык в формате "en"
   */
  static function get($name) {
    $lang = Config::getVar('lang'); // Плучаем массив с настройками языков
    return $lang[$name];
  }
  
  /**
   * Подгружает языковый словарь
   *
   * @param string admin/...
   */
  static function load($name) {
    // Подгружаем константы языкового словарика
    Config::loadConstants(self::getFilename($name));  
  }
  
  static function getFilename($name) {
    return 'lang-'.$name.'-'.self::get($name);
  }
    
}
