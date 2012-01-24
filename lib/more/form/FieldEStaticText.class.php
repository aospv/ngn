<?php

class FieldEStaticText extends FieldEAbstract {

  public function html() {
    return $this->options['text'];
  }

}
