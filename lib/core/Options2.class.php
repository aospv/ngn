<?php

abstract class Options2 extends Options {
  
  public function __construct(array $options = array()) {
    $this->defineOptions();
    $this->setOptions($options);
    $this->init();
  }
  
  protected function defineOptions() {}
  
  /**
   * Вызывается в тот момент, когда определение $this->options окончательно произошло
   */
  protected function init() {}
  
}
