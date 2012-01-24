<?php

class PageBlockStructure_iconLink extends PageBlockStructureAbstract {

  static public $title = 'Иконка с ссылкой';

  protected function initFields() {
    $this->fields[] = array(
      'title' => 'Ссылка',
      'name' => 'url',
      'type' => 'pageLink',
      'required' => true
    );
    $this->fields[] = array(
      'title' => 'Изображение',
      'name' => 'image',
      'type' => 'image',
      'required' => true
    );
    $this->fields[] = array(
      'title' => 'Текст',
      'name' => 'text',
      'type' => 'textarea',
      //'type' => 'wisiwig',
    );
  }

}