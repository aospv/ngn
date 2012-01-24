<?php

class PmiProductsSimple extends PmiDd {
  
  public $title = 'Каталог продукции';
  public $oid = 80;
  public $hasTopSlice = false;

  protected $ddFields = array(
    array(
      'title' => 'Название продукта',
      'name' => ' title',
      'type' => 'typoText',
      'required' => true
    ),
    array(
      'title' => 'Изображение',
      'name' => 'image',
      'type' => 'imagePreview'
    ),
    array(
      'title' => 'Описание',
      'name' => 'text',
      'type' => 'wisiwig'
    )
  );
  
}
