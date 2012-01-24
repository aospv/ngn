<?php

/**
 * Page controller settings action
 */
abstract class Pcsa {
  
  /**
   * @var DbModelPages
   */
  public $page;
  
  public function __construct(DbModelPages $page) {
    $this->page = $page;
  }
  
}