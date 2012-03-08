<?php

class PmiStore extends PmiDd {

  public $title = 'Интернет-магазин';
  
  protected $ddFields = array(
    array(
      'title' => 'Название',
      'name' => 'title',
      'type' => 'typoText',
      'required' => true
    ),
    array(
      'title' => 'Главное изображение',
      'name' => 'image',
      'type' => 'imagePreview',
      'required' => true
    ),
    array(
      'title' => 'Цена',
      'name' => 'price',
      'type' => 'price',
      'required' => true
    ),
    array(
      'title' => 'Описание',
      'name' => 'descr',
      'type' => 'typoTextarea'
    )
  );

}
