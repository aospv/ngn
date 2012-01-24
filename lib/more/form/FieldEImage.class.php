<?php

class FieldEImage extends FieldEFile {

  protected $allowedMimes = array('image/jpeg', 'image/png', 'image/bmp', 'image/gif');
  
  public function defineOptions() {
    parent::defineOptions();
    $this->options['currentFileTitle'] = 'Текущее изображение';
    $this->options['currentFileClass'] = 'image';
  }
  
}
