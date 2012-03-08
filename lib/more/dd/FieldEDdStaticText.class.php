<?php

class FieldEDdStaticText extends FieldEAbstract {

  public $options = array(
    'noRowHtml' => true,
    'noValue' => true
  );
	
  public function html() {
    return '<div class="staticText">'.$this->options['help'].'</div>';
  }

}