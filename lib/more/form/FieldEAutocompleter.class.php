<?php

abstract class FieldEAutocompleter extends FieldEInput {

  protected function __html($acDefault) {
    return Tt::getTpl('common/autocompleter', 
      array(
        'name' => $this->options['name'], 
        'actionKey' => $this->type, 
        'acDefault' => $acDefault, 
        'default' => $this->options['value'], 
        'noJS' => true
      ));
  }

}