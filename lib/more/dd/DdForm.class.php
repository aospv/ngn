<?php

class DdForm extends Form {
	
  /**
   * @var DdFields
   */
  public $oFields;

  /**
   * Имя структуры формы
   *
   * @var string
   */
  public $strName;

  /**
   * ID раздела формы
   * 
   * @var integer
   */
  public $pageId;

  public function __construct(DdFields $oFields, $pageId, array $options = array()) {
    parent::__construct($oFields, $options);
    $this->strName = $this->oFields->strName;
    $this->pageId = $pageId;
  }
  
}
