<?php

class FieldEStaticTitledText extends FieldEAbstract {

  public function _html() {
    return '<span class="text">'.$this->options['text'].'</span>';
  }

}