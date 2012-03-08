<?php

/**
 * Page Block View User Group
 */
abstract class Pbvug {

  /**
   * @var PbvAbstract
   */
  protected $pbv;

  public function __construct(PbvAbstract $pbv) {
    $this->pbv = $pbv;
    $this->init();
  }
  
  protected function init() {}
  
  public function getData() {
    return $this->pbv->getData();
  }

}
