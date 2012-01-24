<?php

class DdFieldsManager extends DbItemsManager {

  public $strName;
  
  protected $oidAddMode = true;
  
  /**
   * @var DdFieldItems
   */
  public $oItems;

  /**
   * @var DdStructure
   */
  protected $oStr;
  
  public function __construct($strName) {
    Misc::checkEmpty($strName);
    $this->strName = $strName;
    $oI = new DdFieldItems($strName);
    $oI->cond->setOrder('oid');
    parent::__construct(
      $oI,
      new DdFormBase(new Fields(array(
        array(
          'type' => 'col'
        ),
        array(
          'name' => 'title',
          'title' => 'Название',
          'required' => true
        ),
        array(
          'name' => 'name',
          'title' => 'Имя',
          'type' => 'ddFieldName',
          'required' => true
        ),
        array(
          'name' => 'default',
          'title' => 'Значение по умолчанию'
        ),
        array(
          'name' => 'help',
          'title' => 'Описание',
          'type' => 'textarea',
          'help' => 'Будет выводится справа или под полем'
        ),
        array(
          'name' => 'maxlength',
          'title' => 'Максимальная длина',
          'help' => 'Оставьте пустым, если максимальная длина не нужна'
        ),
        array(
          'type' => 'col'
        ),
        array(
          'name' => 'type',
          'title' => 'Тип',
          'type' => 'ddFieldType',
          'required' => true
        ),
        array(
          'type' => 'col'
        ),
        array(
          'name' => 'required',
          'title' => 'обязательно для заполнения',
          'type' => 'bool'
        ),
        array(
          'name' => 'notList',
          'title' => 'не выводить поле в списках',
          'type' => 'bool',
        ),
        array(
          'name' => 'defaultDisallow',
          'title' => 'не доступно по умолчанию',
          'type' => 'bool',
          'help' => 'Используется в том случае, если поле отображается только при необходимых привилегиях'
        ),
        array(
          'name' => 'system',
          'title' => 'системное',
          'type' => 'bool',
          'help' => 'Используется в том случае, если изменение пользователем этого поля не предполагается'
        ),
      )), $this->strName)
    );
  }
  
  protected function replaceData() {
    parent::replaceData();
    // Заменяем значения из формы, дозволеными статическими значениями из типа поля
    $this->data = array_merge(
      $this->data,
      Arr::filter_by_keys(DdFieldCore::getTypeData($this->data['type']), array(
        'notList', 'system'
      ))
    );
    $this->data['strName'] = $this->strName;
  }
  
  protected function dbCreateField() {
    $data = DdFieldCore::getTypeData($this->data['type']);
    if ($data['dbType'] == 'VARCHAR' or
        $data['dbType'] == 'TEXT' or
        $data['dbType'] == 'LONGTEXT') {
      $charsetCond = 'CHARACTER SET '.db()->charset.' COLLATE '.db()->collate;
    } else $charsetCond = '';
    $default = null;
    $this->_dbFieldCreate($this->data['name'], $data['dbType'], 
      (isset($data['dbLength']) ? $data['dbLength'] : null), $charsetCond, $default);
    if (DdFieldCore::isFormatType($this->data['type'])) {
      // Если поле - форматируемое
      $this->_dbFieldCreate($this->data['name'].'_f', $data['dbType'],
        (isset($data['dbLength']) ? $data['dbLength'] : null), $charsetCond);
    }
  }
  
  /**
   * Добавляет новое поле в структуру таблицы
   *
   * @param string  Имя структуры
   * @param string  Имя поля
   * @param string  Тип
   * @param string  Максимальная длина значения
   * @param string  Кодировка
   */
  private function _dbFieldCreate($name, $type, $length, $charsetCond, $default = null) {
    $q = "
      ALTER TABLE ".DdCore::table($this->strName)."
      ADD $name $type".($length ? '('.$length.')' : '').
      " $charsetCond NULL".
      ($default !== null ? " DEFAULT '$default'" : '');
    return db()->query($q);
  }
  
  protected function beforeCreate() {
    $this->dbCreateField();
  }
  
  protected function afterCreate() {
    if (DdTags::isTagType($this->data['type'])) {
      DdTagsGroup::create($this->strName, $this->data['name'], 
        DdTags::isTagItemsDirectedType($this->data['type']), true, 
        DdTags::isTagTreeType($this->data['type']));
    }
    //$this->typeAction($this->data['type'], 'create', $this->data['name']);
    $this->typeAction($this->data['type'], 'updateCreate', $this->data['name']);
  }
  
  protected function beforeUpdate() {
    $this->dbUpdateField();
  }
  
  protected function afterUpdate() {
    if ($this->oForm->getElement('name')->valueChanged) {
      $this->renameImages($this->beforeUpdateData['name'], $this->data['name']);
    }
    if (DdTags::isTagType($this->data['type'])) {
      if (!DdTagsGroup::get($this->strName, $this->beforeUpdateData['name'])) {
        // Если тэг-группы ещё не существовало
        DdTagsGroup::create($this->strName, $this->data['name'], 
          DdTags::isTagItemsDirectedType($this->data['type']), true, 
          DdTags::isTagTreeType($this->data['type']));
      } else {
        DdTagsGroup::update($this->strName, $this->beforeUpdateData['name'], $this->data['name'],
          DdTags::isTagItemsDirectedType($this->data['type']), true, 
          DdTags::isTagTreeType($this->data['type']));
      }
    }
    if ($this->oForm->getElement('type')->valueChanged) {
      $this->typeAction($this->data['type'], 'delete', $this->beforeUpdateData['name']);
      //$this->typeAction($this->data['type'], 'update', $this->data['name']);
      $this->typeAction($this->data['type'], 'updateCreate', $this->data['name']);
    }
  }
  
  protected function typeAction($type, $action, $name) {
    $class = 'Ddfma'.ucfirst($type);
    if (!O::exists($class)) return;
    $o = O::get($class, $this->strName);
    if (!method_exists($o, $action)) return;
    $o->$action($name);
  }
  
  protected function dbUpdateField() {
    $data = DdFieldCore::getTypeData($this->data['type']);
    Arr::checkEmpty($data, 'dbType');
    if ($data['dbType'] == 'VARCHAR' or 
        $data['dbType'] == 'TEXT' or
        $data['dbType'] == 'LONGTEXT') {
      $charsetCond = 'CHARACTER SET '.db()->charset.' COLLATE '.db()->collate;
    } else {
      $charsetCond = '';
    }
    if ($this->beforeUpdateData['name'] != $this->data['name']) {
      // Если имя поля поменялось
      $this->_dbUpdateFieldChange(
        $this->beforeUpdateData['name'],
        $this->data['name'],
        $data['dbType'],
        empty($data['dbLength']) ? null : $data['dbLength'],
        $charsetCond
      );
    }
    if (DdFieldCore::isFormatType($this->data['type'])) {
      // НОВЫЙ тип - форматируемый
      if (!DdFieldCore::isFormatType($this->beforeUpdateData['type'])) {
        // ТЕКУЩИЙ тип - не форматируемый
        // Если тип поля до этого был неформатируемый, а стал форматируемый, т.е.
        // ф-поле до этого не существовало
        $this->_dbUpdateFieldAdd(
          $this->data['name'].'_f',
          $data['dbType'],
          empty($data['dbLength']) ? null : $data['dbLength'],
          $charsetCond
        );
      } else {
        // Если поле и раньше было форматируемого типа, апдейтим
        $this->_dbUpdateFieldChange(
          $this->beforeUpdateData['name'].'_f',
          $this->data['name'].'_f',
          $data['dbType'],
          empty($data['dbLength']) ? null : $data['dbLength'],
          $charsetCond
        );
      }
    } elseif (DdFieldCore::isFormatType($this->beforeUpdateData['type'])) {
      $this->dbDeleteField($this->beforeUpdateData['name'].'_f');
    }
  }
  
  /**
   * Изменяет поле в таблице структуры
   *
   * @param   string  Имя структуры
   * @param   string  Старое имя поля
   * @param   string  Новое имя поля
   * @param   string  Тип поля
   * @param   string  Длина поля
   * @param   string  Кодировки
   */
  protected function _dbUpdateFieldChange($oldName, $newName, $type, $length, $charsetCond) {
    return db()->query("
      ALTER TABLE ".DdCore::table($this->strName)."
      CHANGE $oldName $newName $type ".($length ? '('.$length.')' : '').
      " $charsetCond NULL");
  }
  
  /**
   * Добавляет поле в таблицу структуры
   *
   * @param   string  Имя структуры
   * @param   string  Имя поля
   * @param   string  Тип поля
   * @param   string  Длина поля
   * @param   string  Кодировки
   */
  protected function _dbUpdateFieldAdd($name, $type, $length, $charsetCond) {
    db()->query("
      ALTER TABLE ".DdCore::table($this->strName)."
      ADD $name $type ".($length ? '('.$length.')' : '').
      $charsetCond." NULL");
  }
  
  protected function dbDeleteField($fieldName) {
    db()->deleteCol(DdCore::table($this->strName), $fieldName, true);
  }
  
  protected function beforeDelete() {
    $this->dbDeleteField($this->data['name']);
    if (!DdFieldCore::typeExists($this->data['type'])) return;
    // Если поле - форматируемое
    if (DdFieldCore::isFormatType($this->data['type'])) {
      $this->dbDeleteField($this->data['name'].'_f');
    }
    // Тэги
    if (DdTags::isTagType($this->data['type'])) {
      $oTG = new DdTagsGroup($this->strName, $this->data['name']);
      $oTG->delete();
    }
    $this->typeAction($this->data['type'], 'delete', $this->data['name']);
  }
  
  protected function renameImages($oldFieldName, $newFieldName) {
    $strDir = UPLOAD_PATH.'/dd/'.$this->strName;
    if (!file_exists($strDir)) return;
    foreach (Dir::get($strDir) as $itemDir) {
      foreach (Dir::get($strDir.'/'.$itemDir) as $fileName) {
        if (!preg_match('/^(sm_|md_|)'.$oldFieldName.'(\.jpg)$/', $fileName))
          continue;
        $newFileName = preg_replace(
          '/^(sm_|md_|)'.$oldFieldName.'(\.jpg)$/',
          '$1'.$newFieldName.'$2',
          $fileName
        );
        rename($strDir.'/'.$itemDir.'/'.$fileName, $strDir.'/'.$itemDir.'/'.$newFileName);
      }
    }
  }
  
  /**
   * Удаляет все поля структуры
   *
   * @param   string   Имя структуры
   */
  public function deleteFields() {
    foreach (db()->ids('dd_fields', DbCond::get()->addF('strName', $this->strName)) as $id) {
      $this->delete($id);
    }
  }
  
  public function createIfNotExists(array $data) {
    if ($this->oItems->getItemByField('name', $data['name'])) return false;
    return $this->create($data);
  }

}
