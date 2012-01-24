<?php

class DbItemsManager extends ItemsManager {

  /**
   * @var DbItems
   */
  public $oItems;
  
	public function __construct(DbItems $oItems, Form $oForm, array $options = array()) {
    parent::__construct($oItems, $oForm, $options);
  }
  
  public function getAttacheFolder() {
    return 'tbl/'.$this->oItems->table.'/'.$this->id;
  }
  
  public function setAuthorId($id) {
    parent::setAuthorId($id);
    $this->oItems->eventUserId = $id;
    return $this;
  }
  
  protected $oidAddMode = false;
  
  /**
   * Включает/выключает режим добавления oid'а к данным новой записи
   * 
   * @param   bool
   */
  public function setOidAddMode($flag) {
    $this->oidAddMode = $flag;
  }
  
  /**
   * Добавляет в массив с данными из формы, дополнительные значения:
   * ID пользователя, если он залогинен
   *
   * @param   array   Данные создаваемой записи
   */
  protected function addCreateData() {
    parent::addCreateData();
    if ($this->oidAddMode) {
      $lastTableOid = db()->selectCell(
        'SELECT oid FROM '.$this->oItems->table.' ORDER BY oid DESC LIMIT 1');
      $this->data['oid'] = $lastTableOid + 10;
    }
  }

}