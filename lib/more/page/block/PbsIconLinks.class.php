<?php

class PbsIconLinks extends PbsAbstract {

  static public $title = 'Иконки с ссылками';

  protected function initFields() {
    $this->fields[] = array(
      'name' => 'items',
      'type' => 'fieldSet',
      'fields' => array(
        array(
          'title' => 'Ссылка',
          'name' => 'url',
          'type' => 'pageLink',
          'required' => true
        ),
        array(
          'title' => 'Изображение',
          'name' => 'image',
          'type' => 'image',
          'required' => true
        ),
        array(
          'title' => 'Текст',
          'name' => 'text',
          'type' => 'textarea',
        )
      )
    );
  }

}