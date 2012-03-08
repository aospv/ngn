<?php

class DdFieldCore {

  static public function isGroup($type) {
    return FieldCore::hasAncestor($type, 'headerAbstract');
  }

  static public function getIconPath($type) {
    return file_exists(NGN_PATH.'/i/img/icons/fields/'.$type.'.gif') ?
     './i/img/icons/fields/'.$type.'.gif' : './i/img/blank.gif';
  }

  static public function getFieldsFromTable($strName) {
    return Arr::get(db()->select("SHOW COLUMNS FROM dd_i_$strName"), 'Fields');
  }
  
  static public function isFormatType($type) {
    return in_array($type, array('textarea'));
  }
  
  /**
   * Регистрирует dd-поле
   * 
   * @param string Тип поля. Должен быть равен имени класса элемента поля этого типа, с обрезанным префиксом 
   * @param array  virtual - означает, что поле не создает данных и в таблице для него будет определена колонка с типом и длинной по умолчанию
   *               notList - не выводить значение поля
   *               system  - у поля нет редактируемого элемента
   *               noElementTag - при выводе нет обрамляющего тэга .element
   *   
   */
  static public function registerType($type, array $data) {
    Arr::checkEmpty($data, array('title', 'order'));
    if (!empty($data['virtual']))
      $data = array_merge($data, array('dbType' => 'INT', 'dbLength' => 1));
    Arr::checkEmpty($data, 'dbType');
    if (!preg_match('/(.*TEXT|DATE|TIME|DATETIME)/', $data['dbType']))
      Arr::checkEmpty($data, 'dbLength');
    self::$types[$type] = $data;
  }
  
  static protected $types = array();
  
  static public function getTypeData($type, $strict = true) {
    if (Lib::exists(FieldCore::getClass($type))) Lib::required(FieldCore::getClass($type));
    if (!isset(self::$types[$type])) {
      if ($strict)
        throw new EmptyException("There is no such registered ddType as '$type'");
      else
        return false;
    }
    return self::$types[$type];
  }
  
  static public function typeExists($type) {
    if (Lib::exists(FieldCore::getClass($type))) Lib::required(FieldCore::getClass($type));
    return isset(self::$types[$type]);
  }
  
  /**
   * Возвращает данные типов динамических полей
   * @return array
   */
  static public function getTypes() {
    foreach (ClassCore::getClassesByPrefix('FieldE') as $class)
      // Регистрация типа dd-поля происходит в классе элмента
      Lib::required($class);
    return Arr::sort_by_order_key(self::$types, 'order');
  }

}

$r = array(
  'col' => array(
    'title' => 'Колонка',
    'virtual' => true,
    'notList' => true,
    'order' => 15
  ),
  'text' => array(
    'dbType' => 'VARCHAR',
    'dbLength' => 255,
    'title' => 'Одностройчное поле',
    'order' => 20
  ),
  'typoText' => array(
    'dbType' => 'VARCHAR',
    'dbLength' => 255,
    'title' => 'Одностройчное поле (с типографированием)',
    'order' => 10
  ),
  'boolCheckbox' => array(
    'dbType' => 'int',
    'dbLength' => 1,
    'title' => 'Да / нет (чекбокс)',
    'order' => 20
  ),
  'bool' => array(
    'dbType' => 'int',
    'dbLength' => 1,
    'title' => 'Да / нет (радио)',
    'order' => 30
  ),
  'file' => array(
    'dbType' => 'VARCHAR',
    'dbLength' => 255,
    'title' => 'Файл',
    'order' => 40
  ),
  'imagePreview' => array(
    'dbType' => 'VARCHAR',
    'dbLength' => 255,
    'title' => 'Изображение',
    'order' => 50
  ),
  'email' => array(
    'dbType' => 'VARCHAR',
    'dbLength' => 255,
    'title' => 'E-mail',
    'order' => 60
  ),
  'date' => array(
    'dbType' => 'DATE',
    'title' => 'Дата',
    'order' => 70
  ),
  'time' => array(
    'dbType' => 'TIME',
    'title' => 'Время',
    'order' => 80
  ),
  'datetime' => array(
    'dbType' => 'DATETIME',
    'title' => 'Дата, время',
    'order' => 90
  ),
  'typoTextarea' => array(
    'dbType' => 'TEXT',
    'title' => 'Многострочное поле',
    'order' => 100
  ),
  'wisiwig' => array(
    'dbType' => 'TEXT',
    'title' => 'Текстовое поле с визуальным редактором (с поддержкой вложенных изображений, файлов, таблиц и пр.)',
    'order' => 110
  ),
  'wisiwigSimple' => array(
    'dbType' => 'TEXT',
    'title' => 'Текстовое поле с базовым визуальным редактором',
    'order' => 111
  ),
  'num' => array(
    'dbType' => 'INT',
    'dbLength' => 11,
    'title' => 'Целое число',
    'order' => 120
  ),
  'num' => array(
    'dbType' => 'INT',
    'dbLength' => 11,
    'title' => 'Целое число',
    'order' => 120
  ),
  'float' => array(
    'dbType' => 'float',
    'dbLength' => 11,
    'title' => 'Дробное число',
    'order' => 130
  ),
  'price' => array(
    'dbType' => 'FLOAT',
    'dbLength' => 11,
    'title' => 'Цена',
    'order' => 140
  ),
  'static' => array(
    'dbType' => 'VARCHAR',
    'dbLength' => 255,
    'title' => 'Статический текст',
    'virtual' => true,
    'order' => 150
  ),
  'ddStaticText' => array(
    'dbType' => 'VARCHAR',
    'dbLength' => 255,
    'title' => 'Статический текст в форме',
    'virtual' => true,
    'order' => 150
  ),
  'floatBlock' => array(
    'title' => 'Блок для обтекания',
    'order' => 160,
    'virtual' => true,
    'system' => true,
    'noElementTag' => true
  ),
  'header' => array(
    'title' => 'Заголовок',
    'order' => 160,
    'virtual' => true,
    //'system' => true,
  ),
  'groupBlock' => array(
    'title' => 'Блок для группировки',
    'order' => 160,
    'virtual' => true,
    'system' => true,
    'noElementTag' => true
  ),
  'url' => array(
    'dbType' => 'TEXT',
    'title' => 'Одна ссылка',
    'order' => 170
  ),
  'urls' => array(
    'dbType' => 'TEXT',
    'title' => 'Несколько ссылок',
    'order' => 180
  ),
  'icq' => array(
    'dbType' => 'INT',
    'dbLength' => 15,
    'title' => 'ICQ#',
    'order' => 190
  ),
  'skype' => array(
    'dbType' => 'VARCHAR',
    'dbLength' => 255,
    'title' => 'Skype',
    'order' => 200
  ),
  'phone' => array(
    'dbType' => 'VARCHAR',
    'dbLength' => 255,
    'title' => 'Телефон',
    'order' => 210
  ),
  'sound' => array(
    'dbType' => 'VARCHAR',
    'dbLength' => 255,
    'title' => 'Аудио',
    'order' => 220
  ),
  'user' => array(
    'dbType' => 'INT',
    'dbLength' => 11,
    'title' => 'Пользователь',
    'order' => 230
  ),
);

foreach ($r as $type => $data)
  DdFieldCore::registerType($type, $data);
