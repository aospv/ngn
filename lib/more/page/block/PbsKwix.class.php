<?php

class PbsKwix extends PbsAbstract {

  static public $title = 'Раздвигающиеся панели';
  
  protected function initFields() {
    $this->fields = array(
      array(
        'type' => 'col2'
      ),
      array(
        'title' => 'Высота блока',
        'name' => 'height',
        'type' => 'pixels',
        'required' => true
      ),
      array(
        'type' => 'col2'
      ),
      array(
        'title' => 'Цвет заголовка',
        'name' => 'titleColor',
        'type' => 'color'
      ),
      array(
        'type' => 'col2'
      ),
      array(
        'title' => 'Цвет текста',
        'name' => 'textColor',
        'type' => 'color'
      ),
      array(
        'type' => 'col2'
      ),
      array(
        'title' => 'Размер заголовка',
        'name' => 'titleSize',
        'type' => 'fontSize'
      ),
      array(
        'type' => 'col2'
      ),
      array(
        'title' => 'Размер текст',
        'name' => 'textSize',
        'type' => 'fontSize'
      ),
      array(
        'type' => 'header'
      ),
      array(
        'title' => 'Панели',
        'name' => 'items', 
        'type' => 'fieldSet', 
        'fields' => array(
          array(
            'title' => 'Ссылка',
            'name' => 'link',
            'type' => 'pageLink',
            'required' => true
          ),
          array(
            'title' => 'Заголовок',
            'name' => 'title',
            'type' => 'textarea',
            'required' => true
          ),
          array(
            'title' => 'Текст',
            'name' => 'text',
            'type' => 'textarea'
          ),
          array(
            'title' => 'Фоновое изображение',
            'name' => 'bg',
            'type' => 'image'
          ),
        )
      )
    );
  }

}