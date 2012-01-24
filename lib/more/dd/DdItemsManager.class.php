<?php

class DdItemsManager extends DbItemsManager {

  /**
   * Имя структуры текущих записей
   *
   * @var string
   */
  public $strName;

  /**
   * Массив типов полей текущей структуры
   *
   * @var array
   */
  public $types;

  /**
   * ID родительского объекта  
   *
   * @var integer
   */
  public $pageId;

  /**
   * @var Image
   */
  public $oImage;
  
  /**
   * @var DdItems
   */
  public $oItems;
  
  public function __construct(DdItems $oItems, DdForm $oForm, array $options = array()) {
    parent::__construct($oItems, $oForm, $options);
    $this->strName = $oItems->strName;
    $this->pageId = $oItems->pageId;
    Misc::checkEmpty($this->pageId);
    $settings = DbModelCore::get('pages', $this->pageId)->r['settings'];
    $this->imageSizes = Arr::filter_empties2(Arr::filter_by_keys(
      $settings,
      array_keys($this->imageSizes)
    ));
    if (!empty($settings['smResizeType'])) $this->smResizeType = $settings['smResizeType'];
  }

  /**
   * Добавляет ID раздела в данные создаваемой записи
   *
   * @param   array   Данные создаваемой записи
   */
  protected function addCreateData() {
    parent::addCreateData();
    ////////////////////////////////////////////////////////////
    // Если статус активности не определён (а это значит, что пользователь просто не 
    // может его редактировать и поле не было создано), назначаем его значение по умолчанию 
    if (!isset($this->data['active'])) $this->data['active'] = $this->defaultActive;
    $this->data['pageId'] = $this->pageId;
    if ($this->authorId) $this->data['userId'] = $this->authorId;
  }

  public function getAttacheFolder() {
    return DdCore::filesDir($this->strName).'/'.$this->id;
  }

  protected function beforeDelete() {
    Dir::remove(UPLOAD_PATH.'/dd/'.$this->strName.'/'.$this->id);
  }

  public function getTinyAttachItemId($itemId, $fieldName = '') {
    return 'dd-'.$this->strName.'-'.$itemId.'-'.$fieldName;
  }
  
  public function deleteAll() {
    $r = db()->selectCol("SELECT id FROM {$this->oItems->table} WHERE pageId=?d",
      $this->oItems->pageId);
    foreach ($r as $itemId) $this->delete($itemId);
    return $r;
  }
  
  
  protected function getItem($id) {
    return $this->oItems->getItemNonFormat($id);
  }

}
