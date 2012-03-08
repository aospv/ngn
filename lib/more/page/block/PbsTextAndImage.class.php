<?php

class PbsTextAndImage extends PbsAbstract {

  static public $title = 'Текст + изображение';
  
  protected function initFields() {
    $this->fields = array(
      array(
        'title' => 'Текст',
        'name' => 'text',
        'type' => 'wisiwigSimple2',
        'jsOptions' => array('tinySettings' => array(
          'content_css' => SFLM::getCssUrl('tinyPageBlocks')
        ))
      ),
      array(
        'title' => 'Изображение',
        'name' => 'image',
        'type' => 'imagePreview'
      )
    );
  }

}