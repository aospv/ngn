<?php

/**
 * Page Block View User Group
 */
abstract class Pbvug {

  /**
   * @var PageBlockViewAbstract
   */
  protected $pbv;

  public function __construct(PageBlockViewAbstract $pbv) {
    $this->pbv = $pbv;
    $this->init();
  }
  
  protected function init() {}
  
  public function getData() {
    return $this->pbv->getData();
  }

}
