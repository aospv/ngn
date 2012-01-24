<?php

class Block {
  
  /**
   * ID раздела, на котором находится блок
   *
   * @var integer
   */
  public $ownPageId;
  
  /**
   * ID раздела, с которого блок выбирает данные
   *
   * @var integer
   */
  public $pageId;
  
  /**
   * Порядковый номер блока
   *
   * @var integer
   */
  public $oid;
  
  public function __construct($ownPageId, $pageId) {
  }
  
}
