<?php

class PmiUserStore extends PmiDd {

  public $title = 'Пользовательский интернет-магазин';
  
  protected $ddFields = array(
    array(
      'title' => 'Колонка 1',
      'name' => 'col1',
      'type' => 'col'
    ),
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
      'title' => 'Ключевые слова',
      'name' => 'tags',
      'type' => 'ddTags'
    ),
    array(
      'title' => 'Готовность',
      'name' => 'complete',
      'type' => 'ddTagsSelect',
      'required' => true
    ),
    array(
      'title' => 'Цена',
      'name' => 'price',
      'type' => 'price',
      'required' => true
    ),
    array(
      'title' => 'Разрешить предлагать свою цену',
      'name' => 'ownPrice',
      'type' => 'boolCheckbox'
    ),
    array(
      'title' => 'Описание',
      'name' => 'descr',
      'type' => 'typoTextarea'
    ),
    array(
      'title' => 'Колонка 2',
      'name' => 'col2',
      'type' => 'col'
    ),
    array(
      'title' => 'imgGroup',
      'name' => 'imgGroup',
      'type' => 'groupBlock'
    ),
    array(
      'title' => 'Изображение 2',
      'name' => 'image2',
      'type' => 'imagePreview'
    ),
    array(
      'title' => 'Изображение 3',
      'name' => 'image3',
      'type' => 'imagePreview'
    ),
    array(
      'title' => 'Изображение 4',
      'name' => 'image4',
      'type' => 'imagePreview'
    ),
    array(
      'title' => 'extraInfo',
      'name' => 'extraInfo',
      'type' => 'groupBlock'
    ),
    array(
      'title' => 'Материалы',
      'name' => 'meterials',
      'type' => 'ddTags'
    ),
    array(
      'title' => 'Рекомендации по уходу',
      'name' => 'recommend',
      'type' => 'typoTextarea'
    ),
    array(
      'title' => 'Срок изготовления',
      'name' => 'fabricPeriod',
      'type' => 'num',
      'help' => 'дней',
    ),
    array(
      'title' => 'Купить',
      'name' => 'buyBtn',
      'type' => 'static',
    ),
    array(
      'title' => 'Способы доставки',
      'name' => 'deliveryWays',
      'type' => 'static',
    ),
    array(
      'title' => 'Способы оплаты',
      'name' => 'paymentWays',
      'type' => 'static',
    ),
    array(
      'title' => 'Правила магазина',
      'name' => 'rules',
      'type' => 'static',
    ),
  );
  
  protected function afterCreate($node) {
    parent::afterCreate($node);
    $o = new DdTagsTagsFlat(new DdTagsGroup($this->strName, 'complete'));
    $o->create('Для примера');
    $o->create('Сделаю на зказа');
    $o->create('В процессе изготовления');
    $o->create('Готовый товар');
  }
  
  protected function getSettings() {
    return array_merge(
      parent::getSettings(),
      array(
        'ddItemsLayout' => 'tile',
        'itemTitle' => 'товар'
      )
    );
  }

}
