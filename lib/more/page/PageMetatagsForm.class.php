<?php

class PageMetatagsForm extends Form {
  
  public function __construct() {
    parent::__construct(new PageMetatagsFields());
  }
  
}
