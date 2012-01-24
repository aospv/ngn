<?php

class Fields extends Options2 {

  /**
   * Пример массива:
   * array(
   *   'name' => 'fieldName',
   *   'title' => 'Название поля',
   *   'type' => 'select',
   *   'descr' => 'Это поле - совсем не поле, а лужайка непаханая',
   *   'maxlength' => 255,
   *   'required' => 1,
   *   'options' => array(
   *     1, 2, 3
   *   )
   * )
   *
   */
  public $fields = array();
  
  public $options = array(
    'errorOnTypeNotExists' => false
  );
  
  protected $n = 1;

  public function __construct(array $fields = array(), array $options = array()) {
    parent::__construct($options);
    $this->setFields($fields);
  }
  
  public function setFields(array $fields) {
    foreach ($fields as $v) {
      if (!isset($v['name'])) $v['name'] = 'fld'.$this->n;
      if (!isset($v['type'])) $v['type'] = 'text';
      $this->fields[$v['name']] = $v;
      $this->n++;
    }
  }

  public function getFields() {
    return $this->fields;
  }

  public function getFieldsF() {
    return $this->getFields();
  }
  
  public function getInputFields() {
    return Arr::filter_func($this->fields, function($v) {
      return FieldCore::isInput($v['type']);
    });
  }
  
  public function getType($name) {
    return isset($this->fields[$name]['type']) ? $this->fields[$name]['type'] : false;
  }
  
  public function getTypes() {
    return Arr::get($this->getFields(), 'type', 'name');
  }
  
  /**
   * Возвращает только обязательные для заполнения поля
   *
   * @return array Массив с данными полей
   */
  public function getRequired() {
    $fields = array();
    foreach ($this->getFields() as $k => $v) {
      if (!empty($v['required'])) {
        $fields[$k] = $v;
      }
    }
    return $fields;
  }
  
  public function getTitle($name) {
    return $this->fields[$name]['title'];
  }
  
  public function getField($name) {
    return $this->fields[$name];
  }
  
  public function addField(array $field) {
    if (empty($field['type'])) $field['type'] = 'text';
    $this->fields[$field['name']] = $field;
  }

  public function getFieldsByAncestor($ancestorType) {
    $fields = array();
    foreach ($this->getFields() as $k => $v) {
      if (FieldCore::hasAncestor($v['type'], $ancestorType))
        $fields[$k] = $v;
    }
    return $fields;
  }
  
  public function getFileFields() {
    return $this->getFieldsByAncestor('file');
  }
   
  public function getDateFields() {
    return $this->getFieldsByAncestor('date');
  }
  
  public function exists($name) {
    return isset($this->fields[$name]);
  }
  
  public function isFileType($name) {
    return $this->hasAncestor($name, 'file');
  }
  
  public function hasAncestor($name, $ancestorType) {
    return FieldCore::hasAncestor($this->fields[$name]['type'], $ancestorType);
  }
  
  static public function keyAsName(array $fields) {
    foreach ($fields as $k => &$v) $v['name'] = $k;
    return $fields;
  }
  
}
