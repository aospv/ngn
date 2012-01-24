<?php

class DdFields extends Fields {

  public $strName;

  protected function defineOptions() {
    $this->options['getSystem'] = false;
    $this->options['getDisallowed'] = false;
  }
  
  public function __construct($strName, array $options = array()) {
    Misc::checkEmpty($strName);
    $this->strName = $strName;
    parent::__construct(array(), $options);
  }
  
  protected function init() {
    $fields = db()->select(
      "SELECT * FROM dd_fields WHERE strName=?
      ORDER BY oid", $this->strName);
    $fields[] = array(
      'title' => 'Дата создания',
      'name' => 'dateCreate',
      'type' => 'datetime',
      'system' => true,
      'defaultDisallow' => false
    );
    $fields[] = array(
      'title' => 'Дата изменения',
      'name' => 'dateUpdate',
      'type' => 'datetime',
      'system' => true,
      'defaultDisallow' => false
    );
    $fields[] = array(
      'title' => 'Дата публикации',
      'name' => 'datePublish',
      'type' => 'datetime',
      'system' => true,
      'defaultDisallow' => false
    );
    $fields[] = array(
      'title' => 'Дата последнего комментария',
      'name' => 'commentsUpdate',
      'type' => 'datetime',
      'system' => true,
      'defaultDisallow' => false
    );
    // ---- filters -----
    if (!$this->options['getSystem'])
      $fields = Arr::filter_by_value($fields, 'system', 0);
    if (!$this->options['getDisallowed'])
      $fields = Arr::filter_by_value($fields, 'defaultDisallow', 0);
    foreach ($fields as &$v) $v['dd'] = true;
    $this->setFields($fields);
  }

  public function exists($name) {
    return isset($this->fields[$name]);
  }
  
  public function getTagFields() {
    return array_filter($this->getFields(), function(&$v) {
      return DdTags::isTagType($v['type']);
    });
  }
  
  public function getDateFields() {
    return array_filter($this->getFields(), function(&$v) {
      return ClassCore::hasAncestor(FieldCore::getClass($v['type']), 'FieldEDate');
    });
  }
  
  /*
  public function getFormatedFields() {
    return array_filter($this->getFields(), function(&$v) {
      return DdFieldCore::isFormatType($v['type']);
    });
  }
  */
  
  public function getFieldsByType($type) {
    return Arr::filter_by_value($this->getFields(), 'type', $type);
  }


}