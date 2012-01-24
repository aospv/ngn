<?php

class FieldEFieldSet extends FieldEFieldSetAbstract {

  protected $requiredOptions = array('name', 'fields');
  
  protected function getName($n, $name) {
    return $this->options['name']."[$n][$name]";
  }

}