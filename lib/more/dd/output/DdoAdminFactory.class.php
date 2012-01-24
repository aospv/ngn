<?php

class DdoAdminFactory {
  
  /**
   * Массив с данными раздела
   *
   * @var array
   */
  protected $page;
  
  /**
   * Имя класса Ddo
   *
   * @var string
   */
  protected $tplDdItemsModuleClass;
  
  /**
   * Имя класса Ddo
   *
   * @var string
   */
  protected $tplDdItemsLayoutClass;
  
  public function __construct($page) {
    $this->page = $page;
    if (!empty($page['module']))
      $this->tplDdItemsModuleClass = PageModuleCore::getClass($page['module'], 'DdoApm');
    return $this;
  }

  public function get() {
    if (isset($this->tplDdItemsModuleClass) and Lib::exists($this->tplDdItemsModuleClass)) {
      return eval('return new '.$this->tplDdItemsModuleClass.
        '($this->page, "adminItems");');
    } elseif (isset($this->tplDdItemsLayoutClass) and Lib::exists($this->tplDdItemsLayoutClass)) {
      return eval('return new '.$this->tplDdItemsLayoutClass.
        '($this->page, "adminItems");');
    }
    $o = new DdoAdmin($this->page, 'adminItems');
    return $o;
  }
  
}
